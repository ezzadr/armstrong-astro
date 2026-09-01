// Build-time review stats sync.
//
// The live Google review count + rating are fetched ONCE per build from the
// site's own /api/reviews.php endpoint (which caches Google Places server-side,
// so no API key is needed here) and baked into the JSON-LD schema. This keeps
// the schema's reviewCount/ratingValue accurate on every deploy without a
// manual edit. Visible on-page counts already sync live in the browser via
// syncReviewCounts() in Header.astro; this covers the one thing that can't —
// the server-rendered structured data that Googlebot reads.
//
// The result is memoized at module scope so all ~44 pages in a build reuse a
// single fetch. On any failure (offline build, endpoint down, bad payload) it
// falls back to the last known-good numbers below, so a build never breaks and
// the schema is never empty — worst case it's as stale as the fallback.

const ENDPOINT = 'https://armstronglocksmithinc.com/api/reviews.php';

// Last manually-verified figures. Bump these to the current live values so the
// fallback is never badly stale if a build ever can't reach the endpoint.
const FALLBACK = { reviewCount: '773', ratingValue: '4.9' };

let cached = null;

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
        console.log(`[reviewStats] synced from live endpoint: ${cached.reviewCount} reviews, ${cached.ratingValue} stars`);
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
