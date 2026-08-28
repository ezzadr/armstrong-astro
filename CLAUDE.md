## Read first

**`AGENT_HANDOVER.md` is the source of truth for this project.** Read it before
proposing changes — especially section 6 (Hard-Won Constraints) and section 7
(Cloudflare configuration). It records decisions that are expensive to
rediscover and easy to undo by accident.

The five that bite hardest:

1. **`npm run build` already syncs `dist/` to the repo root** and prunes stale
   `_astro/` bundles. Never copy `dist/` by hand.
2. **Pushing to `main` deploys.** The GitHub Action runs `git reset --hard
   origin/main` on Cloudways and does **not** build — so a commit touching only
   `src/` will never reach production. Always build before committing.
3. **Do not create per-city landing pages.** They existed, were penalised in a
   Google update, and were deliberately deleted.
4. **Do not "correct" the schema hours to 23:30.** They intentionally mirror the
   Google Business Profile storefront hours (Mon–Fri 08:00–18:00, Sat 10:00–16:00).
5. **Never gate behaviour on the Lighthouse/PageSpeed user agent.** Two such
   gates were removed as cloaking. Deferring work is fine; hiding it is not.

`.htaccess` is inert for static files here — Nginx serves them directly. Cache
and security headers live in Cloudflare, documented in `AGENT_HANDOVER.md` §7.

**Tailwind arbitrary breakpoint variants (`min-[360px]:`, `[@media(...)]:`) are
silently dropped in this setup** — the class stays in the HTML but no rule is
generated, so `hidden min-[360px]:inline` hides the element at every width. Use
plain CSS in `src/styles/global.css` for custom breakpoints, and check the built
bundle (`grep <class> _astro/*.css`) rather than trusting the dev server.

## Development

When starting the dev server, use background mode:

```
astro dev --background
```

Manage the background server with `astro dev stop`, `astro dev status`, and `astro dev logs`.

## Documentation

Full documentation: https://docs.astro.build

Consult these guides before working on related tasks:

- [Adding pages, dynamic routes, or middleware](https://docs.astro.build/en/guides/routing/)
- [Working with Astro components](https://docs.astro.build/en/basics/astro-components/)
- [Using React, Vue, Svelte, or other framework components](https://docs.astro.build/en/guides/framework-components/)
- [Adding or managing content](https://docs.astro.build/en/guides/content-collections/)
- [Adding styles or using Tailwind](https://docs.astro.build/en/guides/styling/)
- [Supporting multiple languages](https://docs.astro.build/en/guides/internationalization/)
