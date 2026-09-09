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
// single fetch. On any failure (offline build, endpoint down, blocked runner,
// bad payload) everything falls back to the last-known-good snapshot committed
// at src/data/reviews-fallback.json — real reviews previously returned by the
// live endpoint, never invented — so a build never breaks, the schema is never
// empty, and Googlebot still gets review cards in the server-rendered HTML.
//
// That snapshot refreshes itself: any build that DOES reach the endpoint writes
// the new reviews back to the file, and CI commits it with the build output.

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ENDPOINT = 'https://armstronglocksmithinc.com/api/reviews.php';

const SNAPSHOT_PATH = path.join(
  path.dirname(fileURLToPath(import.meta.url)),
  '..',
  'data',
  'reviews-fallback.json',
);

// Last-resort figures, used only if the snapshot file is missing or unreadable.
// Bump these to the current live values when you notice them drifting.
const FALLBACK = { reviewCount: '778', ratingValue: '4.9' };

let cached = null;
let cachedReviews = [];

// Only reviews that are 4 stars and up and actually have text ever get shown.
function usableReviews(list) {
  return Array.isArray(list)
    ? list.filter(
        (r) => r && r.author_name && (r.rating ?? 0) >= 4 && String(r.text || '').trim() !== '',
      )
    : [];
}

function readSnapshot() {
  try {
    const snap = JSON.parse(fs.readFileSync(SNAPSHOT_PATH, 'utf8'));
    const count = parseInt(snap?.user_ratings_total, 10);
    const rating = parseFloat(snap?.rating);
    return {
      reviewCount: Number.isFinite(count) && count > 0 ? String(count) : FALLBACK.reviewCount,
      ratingValue: Number.isFinite(rating) && rating > 0 ? String(rating) : FALLBACK.ratingValue,
      reviews: usableReviews(snap?.reviews),
      syncedAt: snap?.synced_at || 'unknown',
    };
  } catch {
    return null;
  }
}

// Keep the committed snapshot current whenever a build reaches the endpoint.
// Writes only on an actual change, so untouched builds stay diff-free. Never
// throws: a read-only checkout must not break the build.
function writeSnapshot(reviews, count, rating) {
  if (reviews.length === 0) return;
  try {
    const next = {
      _comment:
        'Last-known-good Google review snapshot, refreshed automatically by src/lib/reviewStats.mjs on any build that reaches /api/reviews.php. Real Google reviews only - never hand-write entries here.',
      synced_at: new Date().toISOString(),
      rating: Number(rating),
      user_ratings_total: Number(count),
      reviews,
    };
    const sameAsDisk = (() => {
      try {
        const prev = JSON.parse(fs.readFileSync(SNAPSHOT_PATH, 'utf8'));
        return (
          JSON.stringify({ ...prev, synced_at: null }) === JSON.stringify({ ...next, synced_at: null })
        );
      } catch {
        return false;
      }
    })();
    if (sameAsDisk) return;
    fs.mkdirSync(path.dirname(SNAPSHOT_PATH), { recursive: true });
    fs.writeFileSync(SNAPSHOT_PATH, `${JSON.stringify(next, null, 2)}\n`);
    console.log(`[reviewStats] refreshed fallback snapshot (${reviews.length} reviews)`);
  } catch (err) {
    console.warn(`[reviewStats] could not refresh snapshot (${err?.code || 'error'}); continuing`);
  }
}

function useSnapshot(why) {
  const snap = readSnapshot();
  if (snap) {
    cached = { reviewCount: snap.reviewCount, ratingValue: snap.ratingValue };
    cachedReviews = snap.reviews;
    console.warn(
      `[reviewStats] ${why}; using snapshot from ${snap.syncedAt}: ${cached.reviewCount} reviews, ${cachedReviews.length} review cards`,
    );
  } else {
    cached = { ...FALLBACK };
    cachedReviews = [];
    console.warn(`[reviewStats] ${why} and no readable snapshot; using ${FALLBACK.reviewCount}`);
  }
  return cached;
}

export async function getReviewStats() {
  if (cached) return cached;

  try {
    const res = await fetch(ENDPOINT, { signal: AbortSignal.timeout(8000) });
    if (res.ok) {
      const d = await res.json();
      const count = parseInt(d?.user_ratings_total, 10);
      const rating = parseFloat(d?.rating);
      if (Number.isFinite(count) && count > 0) {
        const ratingValue =
          Number.isFinite(rating) && rating > 0 ? String(rating) : FALLBACK.ratingValue;
        cached = { reviewCount: String(count), ratingValue };
        cachedReviews = usableReviews(d?.reviews);
        console.log(
          `[reviewStats] synced from live endpoint: ${cached.reviewCount} reviews, ${cached.ratingValue} stars, ${cachedReviews.length} review cards`,
        );
        writeSnapshot(cachedReviews, count, ratingValue);
        return cached;
      }
    }
    return useSnapshot(`endpoint returned no usable count (HTTP ${res.status})`);
  } catch (err) {
    return useSnapshot(`fetch failed (${err?.name || 'error'})`);
  }
}

// Newest real Google reviews (4 stars and up, with text). Live when the build
// reached the endpoint, otherwise the last-known-good snapshot, so the homepage
// always server-renders cards. The browser still refreshes them on load via
// syncLiveGoogleReviews() in index.astro.
export async function getLiveReviews() {
  await getReviewStats();
  return cachedReviews;
}
