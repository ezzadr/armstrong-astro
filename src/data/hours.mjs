// Business hours - the ONE place to change them.
//
// The mobile menu, footer, homepage, bottom call bar, discount popup, the
// contact / storefront / service-area / blog / make pages and the
// LocalBusiness schema in Layout.astro all read from this file.
//
// A few places can't import code, so they are typed by hand. Update them too
// whenever these change:
//   - public/llms.txt
//   - the shop hours line in two blog posts under src/content/blog/
//     (detex-exit-lock-... and maserati-granturismo-...)
//   - the Google Business Profile, which the schema must match
//
// Times are 24-hour "HH:MM". Sunday is closed for both. There is no 24/7,
// overnight or on-call service.

export const HOURS = {
  store: { weekdays: ['08:00', '18:00'], saturday: ['10:00', '16:00'] },
  mobile: { weekdays: ['08:00', '23:30'], saturday: ['10:00', '16:00'] },
};

// "08:00" -> "8 AM", "23:30" -> "11:30 PM"
export function fmt(hhmm) {
  const [h, m] = hhmm.split(':').map(Number);
  const h12 = h % 12 || 12;
  const suffix = h < 12 ? 'AM' : 'PM';
  return m ? `${h12}:${String(m).padStart(2, '0')} ${suffix}` : `${h12} ${suffix}`;
}

// "08:00" -> 480, for open/closed checks in the browser
export function minutes(hhmm) {
  const [h, m] = hhmm.split(':').map(Number);
  return h * 60 + m;
}

const range = ([open, close]) => `${fmt(open)}–${fmt(close)}`;

export const storeWeekdays = range(HOURS.store.weekdays); // "8 AM–6 PM"
export const storeSaturday = range(HOURS.store.saturday); // "10 AM–4 PM"
export const mobileWeekdays = range(HOURS.mobile.weekdays); // "8 AM–11:30 PM"
export const mobileSaturday = range(HOURS.mobile.saturday); // "10 AM–4 PM"
export const mobileUntil = fmt(HOURS.mobile.weekdays[1]); // "11:30 PM"

export const storeHoursLine = `Mon–Fri ${storeWeekdays} • Sat ${storeSaturday} • Sun closed`;
export const mobileHoursLine = `Mon–Fri ${mobileWeekdays} • Sat ${mobileSaturday} • Sun closed`;
