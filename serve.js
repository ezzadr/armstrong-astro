import http from 'http';
import https from 'https';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const DIST_DIR = path.join(__dirname, 'dist');
const PORT = 4321;

// Twilio Config
const TWILIO_CONFIG = {
  accountSid: process.env.TWILIO_ACCOUNT_SID || 'AC1c283a892ca8f15081d8b000a2a5d5b2',
  authToken: process.env.TWILIO_AUTH_TOKEN || '795ea3068ae5a0193043254012d1c7b4',
  fromNumber: process.env.TWILIO_FROM_NUMBER || '+16293389619',
  toNumber: process.env.NOTIFICATION_PHONE || '+16156258000'
};

const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'text/javascript; charset=utf-8',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.webp': 'image/webp'
};

function sendTwilioSMS(data) {
  return new Promise((resolve, reject) => {
    let msgBody = `🚨 NEW LOCKSMITH LEAD!\n` +
                  `👤 Name: ${data.name || 'Anonymous'}\n` +
                  `📞 Phone: ${data.phone || 'No phone'}\n` +
                  `🔑 Service: ${data.service || 'General Locksmith'}\n` +
                  `🚗 Details: ${data.details || 'None'}\n`;
    if (data.notes) {
      msgBody += `📝 Notes: ${data.notes}\n`;
    }
    if (data.email) {
      msgBody += `✉️ Email: ${data.email}\n`;
    }
    msgBody += `⚡ Call customer back immediately!`;

    const postData = new URLSearchParams({
      From: TWILIO_CONFIG.fromNumber,
      To: TWILIO_CONFIG.toNumber,
      Body: msgBody
    }).toString();

    const req = https.request({
      hostname: 'api.twilio.com',
      port: 443,
      path: `/2010-04-01/Accounts/${TWILIO_CONFIG.accountSid}/Messages.json`,
      method: 'POST',
      auth: `${TWILIO_CONFIG.accountSid}:${TWILIO_CONFIG.authToken}`,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': Buffer.byteLength(postData)
      }
    }, (res) => {
      let resData = '';
      res.on('data', chunk => resData += chunk);
      res.on('end', () => {
        try {
          const parsed = JSON.parse(resData);
          console.log(`[Twilio SMS] Status: ${res.statusCode} | SID: ${parsed.sid} | To: ${TWILIO_CONFIG.toNumber}`);
          resolve({ statusCode: res.statusCode, body: parsed });
        } catch (e) {
          resolve({ statusCode: res.statusCode, body: resData });
        }
      });
    });

    req.on('error', (err) => {
      console.error('[Twilio Error]', err);
      reject(err);
    });

    req.write(postData);
    req.end();
  });
}

const server = http.createServer(async (req, res) => {
  const urlObj = new URL(req.url, `http://${req.headers.host}`);
  const pathname = urlObj.pathname;

  // Handle Twilio Contact Form POST API
  if ((pathname === '/api/contact' || pathname === '/api/contact.php') && req.method === 'POST') {
    let body = '';
    req.on('data', chunk => body += chunk);
    req.on('end', async () => {
      try {
        const data = JSON.parse(body || '{}');
        console.log('[Lead Submission Received]', data);
        const result = await sendTwilioSMS(data);
        res.writeHead(200, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' });
        res.end(JSON.stringify({ success: true, message: 'SMS dispatched successfully!', result }));
      } catch (err) {
        console.error('[API Error]', err);
        res.writeHead(500, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' });
        res.end(JSON.stringify({ success: false, error: err.message }));
      }
    });
    return;
  }

  // Handle Google Reviews API
  if (pathname === '/api/reviews.php' || pathname === '/api/reviews') {
    res.writeHead(200, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' });
    res.end(JSON.stringify({
      rating: 4.9,
      user_ratings_total: 120,
      reviews: [
        {
          author_name: 'Marcus Vance',
          rating: 5,
          relative_time_description: '2 weeks ago',
          text: 'Saved me over $600 compared to the BMW dealership! The dealer wanted to tow my 2021 BMW 5-Series and keep it for 10 days. Rahim came out with dealer-level equipment, cut the emergency physical blade, and programmed the smart key fob in 25 minutes flat.',
          service: 'BMW Key Replacement'
        },
        {
          author_name: 'Jessica Holloway',
          rating: 5,
          relative_time_description: '1 month ago',
          text: 'We just bought our home and needed all exterior deadbolts rekeyed immediately. Armstrong gave me an upfront quote over the phone with zero hidden fees. The technician arrived right on time and repinned all locks to one master key.',
          service: 'Residential Rekeying'
        },
        {
          author_name: 'Carlos Mendez',
          rating: 5,
          relative_time_description: '3 weeks ago',
          text: 'Having an actual physical storefront made all the difference. I walked in during my lunch break, Rahim cut and programmed a second smart key for my Audi A6 in less than 20 minutes.',
          service: 'Audi Smart Key Fob'
        }
      ]
    }));
    return;
  }

  // Handle Static Files
  let urlPath = decodeURIComponent(pathname);
  if (urlPath.endsWith('/')) {
    urlPath += 'index.html';
  }
  let filePath = path.join(DIST_DIR, urlPath);
  
  if (!fs.existsSync(filePath) && fs.existsSync(filePath + '.html')) {
    filePath += '.html';
  } else if (!fs.existsSync(filePath) && fs.existsSync(path.join(filePath, 'index.html'))) {
    filePath = path.join(filePath, 'index.html');
  }

  if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
    const ext = path.extname(filePath).toLowerCase();
    const contentType = MIME_TYPES[ext] || 'application/octet-stream';
    res.writeHead(200, { 'Content-Type': contentType, 'Access-Control-Allow-Origin': '*' });
    fs.createReadStream(filePath).pipe(res);
  } else {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('404 Not Found');
  }
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`Server running with active Twilio SMS handler at http://localhost:${PORT}/`);
});
