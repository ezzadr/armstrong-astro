# Book Online photo upload fix — local only, 2026-09-04

The live form sent only the selected filename to contact.php. BookingForm now
sends actual multipart photo bytes to the Dispatch website-bookings endpoint
before calling the existing contact.php notification flow. Matching phone and
service preserve the existing short duplicate window. Text-only submissions
remain unchanged. Photo receipt is required before showing success.

Coordinated Dispatch changes and the additive attachment table were explicitly
approved by Rahim. Deploy the Dispatch receiver/migration before website changes.
Never deploy or configure Armstrong Portal.

Rahim approved replacing the unsafe release process. The new workflow verifies
source on main pushes without publishing. An explicit workflow_dispatch with
publish_booking_page=true builds in Linux and publishes only book-online/index.html.
It requires matching already-installed _astro assets; missing/changed assets stop
the release rather than silently altering other website files. It creates a
read-back SHA-256 verified rollback copy outside public_html, then atomically
replaces this one public file and verifies its installed checksum and live marker.
No Git reset, generated-root commit, Nginx write/reload or cache purge occurs.
Backups contain only public HTML (no customer records or credentials), with 0600
files inside a 0700 directory. They are server-local, not off-site disaster recovery.
Larger website releases need a separately reviewed scope; this workflow does not
silently publish the entire site. Publish only after the Dispatch receiver is live.

Rollback: restore the recorded prior index.html bytes to the exact recorded
book-online/index.html target, verifying the checksums.json before/after digest;
use a same-directory temporary file plus atomic rename. Do not reset Git or touch
other applications. Never run rollback automatically over later website edits.

Local checks: four mocked browser-submit tests passed; Astro built all 47 pages.
No live test bookings, customer messages, photo uploads, or production changes.
Generated dist is preview-only; do not commit generated root output locally.
The pre-existing untracked scripts/serve.mjs is unrelated and untouched.
