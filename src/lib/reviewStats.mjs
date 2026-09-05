// Build-time Google review sync.
//
// The live Google rating, review count and newest reviews are fetched ONCE per
// build from the site's own /api/reviews.php endpoint (which caches Google
// Places server-side, so no API key is needed here) and baked into the JSON-LD
// schema, the header/footer counters and the homepage review cards. That keeps
// the server-rendered numbers Googlebot reads correct on every deploy without a
// manual edit. Visible counters also refresh live in the browser via
// syncReviewCounts() in Header.astro.
//
// The result is memoized at module scope so all ~45 pages in a build reuse a
// single fetch. On any failure (offline build, endpoint down, bad payload) the
// numbers fall back to the last known-good values below and the review list is
// empty (never invented), so a build never breaks and the schema is never empty.

const ENDPOINT = 'https://armstronglocksmithinc.com/api/reviews.php';

// Last manually-verified figures. Bump these to the current live values so the
// fallback is never badly stale if a build ever can't reach the endpoint.
const FALLBACK = { reviewCount: '777', ratingValue: '4.9' };

let cached = null;
let cachedReviews = [];

export async function getReviewStats() {
  if (cached) return cached;

  try {
    const res = await fetch(ENDPOINT, { signal: AbortSignal.timeout(8000) });
    if (res.ok) {
      const d = await res.json();
      const count = parseInt(d?.user_ratings_total, 10);
      const rating = parseFloat(d?.rating);
      if (Number.isFinite(count) && count > 0) {
        cached = {
          reviewCount: String(count),
          ratingValue: Number.isFinite(rating) && rating > 0 ? String(rating) : FALLBACK.ratingValue,
        };
        cachedReviews = Array.isArray(d?.reviews)
          ? d.reviews.filter((r) => r && r.author_name && (r.rating ?? 0) >= 4 && String(r.text || '').trim() !== '')
          : [];
        console.log(`[reviewStats] synced from live endpoint: ${cached.reviewCount} reviews, ${cached.ratingValue} stars, ${cachedReviews.length} review cards`);
        return cached;
      }
    }
    console.warn(`[reviewStats] endpoint returned no usable count (HTTP ${res.status}); using fallback ${FALLBACK.reviewCount}`);
  } catch (err) {
    console.warn(`[reviewStats] fetch failed (${err?.name || 'error'}); using fallback ${FALLBACK.reviewCount}`);
  }

  cached = { ...FALLBACK };
  return cached;
}

// Newest real Google reviews (4 stars and up, with text) from the same fetch.
// Empty when the endpoint could not be reached: the homepage then renders no
// cards server-side and fills them in the browser once /api/reviews.php answers.
export async function getLiveReviews() {
  await getReviewStats();
  return cachedReviews;
}
