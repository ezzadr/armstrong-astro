# Book Online photo upload fix — released, 2026-09-04

Current status: live. Dispatch receiver/migration is live at 7c98289 after its
verified encrypted backup and 260 passing release tests. Website source and the
private-home rollback change are at 7669f8be. Protected website run 33904827267
passed all tests/build, created and read-back verified the rollback copy at
/home/master/.armstrong-site-backups/btfdkcdpdw/4f43ea8096c64bf5b4368d4ca448f67c/index.html,
installed SHA-256 1ab9647ac3da4e57398999854f1bc30b177b1fa0669e7c122b958c871908cd65,
and passed the live marker/photo acknowledgement checks. An independent HTTPS
check also returned 200 with the exact release marker, multipart FormData code,
and photo_received acknowledgement. The backup directory is outside public_html
and is restricted to the existing deployment account; no server-wide permission,
other-application, shared-service, or Nginx change was made.
Roadmap scope is unchanged; this completes the already-approved upload path.

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

Canonical target verified by read-only preflight: the master application alias
resolves to /home/1506337.cloudwaysapps.com/btfdkcdpdw/public_html. The publisher
pins this exact path, not an arbitrary resolved location. First releases stopped
before changing any file because the original alias was deliberately rejected.

Rollback: restore the recorded prior index.html bytes to the exact recorded
book-online/index.html target, verifying the checksums.json before/after digest;
use a same-directory temporary file plus atomic rename. Do not reset Git or touch
other applications. Never run rollback automatically over later website edits.

Local checks: four mocked browser-submit tests passed; Astro built all 47 pages.
No live test bookings, customer messages, photo uploads, or production changes.
Generated dist is preview-only; do not commit generated root output locally.
The pre-existing untracked scripts/serve.mjs is unrelated and untouched.
