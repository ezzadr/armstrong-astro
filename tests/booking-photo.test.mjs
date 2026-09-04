import { readFileSync } from 'node:fs';
import { runInNewContext } from 'node:vm';
import { test } from 'node:test';
import assert from 'node:assert/strict';

const source = readFileSync(new URL('../src/components/BookingForm.astro', import.meta.url), 'utf8');
const handler = source.match(/form.addEventListener\('submit', async function \(e\) \{([\s\S]*?)\n      \}\);/)[1];

async function submit(photo, photoSaved = true) {
  const requests = [];
  const stages = [];
  const element = (value = '') => ({ value, disabled: false, textContent: '', classList: { add() {}, remove() {} } });
  const context = {
    FormData, Error, String, Object, Math, Date, console,
    submitBtn: element(), apiError: element(), nameIn: element('Test Customer'),
    phoneIn: element('6155550142'), honeypot: element(), errName: element(), errPhone: element(),
    serviceInput: element('Rekey'), locationInput: element('Shop'), dateInput: element('Today'),
    timeInput: element('ASAP'), photoIn: { files: photo ? [photo] : [] }, formStartedAt: 1,
    document: { getElementById: () => element() },
    updateStepper: (stage) => stages.push(stage),
    fetch: async (url, options) => {
      requests.push({ url, ...options });
      return { ok: true, json: async () => ({ photo_received: photoSaved }) };
    },
  };
  await runInNewContext('(async function(e) {' + handler + '})', context)({ preventDefault() {} });
  return { requests, stages, context };
}

test('photo bytes go to Dispatch before the notification, with matching duplicate keys', async () => {
  const result = await submit(new File(['image bytes'], 'door.png', { type: 'image/png' }));
  assert.equal(result.requests.length, 2);
  const upload = result.requests[0];
  assert.equal(upload.url, 'https://booking.armstronglocksmithinc.com/api/website-bookings');
  assert.equal(await upload.body.get('photo').text(), 'image bytes');
  assert.equal(upload.headers['Content-Type'], undefined);
  assert.equal(upload.body.get('service_type'), JSON.parse(result.requests[1].body).service);
  assert.deepEqual(result.stages, [4]);
});

test('missing photo acknowledgment stops success and notification', async () => {
  const result = await submit(new File(['image'], 'door.png'), false);
  assert.equal(result.requests.length, 1);
  assert.deepEqual(result.stages, []);
  assert.equal(result.context.submitBtn.disabled, false);
  assert.match(result.context.apiError.textContent, /could not be saved/);
});

test('oversized photo is rejected before any request', async () => {
  const result = await submit({ size: 5 * 1024 * 1024 + 1, name: 'large.jpg' });
  assert.equal(result.requests.length, 0);
  assert.deepEqual(result.stages, []);
  assert.match(result.context.apiError.textContent, /smaller than 5 MB/);
});

test('text-only bookings keep the existing contact flow', async () => {
  const result = await submit(null);
  assert.equal(result.requests.length, 1);
  assert.equal(result.requests[0].url, '/api/contact.php');
  assert.deepEqual(result.stages, [4]);
});
