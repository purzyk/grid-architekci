# GRID Architekci — redesign & rebuild spec

Working spec for taking the approved design (`projekt/`) from static Tailwind
mocks to a live WordPress site. Also doubling as a portfolio piece — noted
where that shapes a decision.

Source design: `projekt/*.dc.html` + `projekt/handoff/` (README, tailwind
mapping, tailwind.config.js). Live preview: https://purzyk.usermd.net/grid-8/
Current live site (being replaced): https://grid.net.pl/

## Goals

1. Ship the client a WordPress site matching the approved design, editable
   without touching code.
2. Build it as a portfolio piece demonstrating both the design and the
   modern WP block-development skill set.

## Architecture decisions

- **Full Site Editing (FSE) block theme.** `theme.json` carries the design
  tokens (colors, type scale, spacing) ported directly from
  `projekt/handoff/tailwind.config.js` — nothing invented, same source of
  truth as the mocks.
- **Custom Gutenberg blocks**, not fixed PHP templates, for anything the
  client should be able to rearrange or reuse:
  - Static/editable: hero, stat bar, highlight plates, process steps, team
    grid, manifesto text.
  - Dynamic (server-rendered from CPTs): project grid + filter, awards
    table, publications grid.
- **Filtering / "show more"** rebuilt on the WP **Interactivity API**
  against real `WP_Query` data. The mocks' `DCLogic` component (hardcoded
  JS array, client-side filter) is a behavior spec only — see
  `projekt/handoff/README.md`, not production code.
- **Data model** — three CPTs, fields per the handoff README:
  - `projekt` — id, title PL/EN, year, category (mieszkalne | publiczne |
    przemysłowe), status (zrealizowane | w realizacji | konkurs |
    koncepcja), award (optional), main photo, short/long description PL/EN,
    gallery, site plan drawing, spec block (client, year, status, scope,
    team, structure, services, contractor, photos, awards).
  - `nagroda` (award) — year, competition name PL/EN, project PL/EN,
    result PL/EN, featured (bool — drives the 3 highlight plates), link to
    project.
  - `publikacja` (publication) — title, type PL/EN, year, cover, link.
- **Bilingual** — Polylang or WPML, separate `/pl/` `/en/` URLs (not a
  client-state toggle) for SEO and linkability.

## Dev environment

Two separate DDEV projects — **never merge them**:

- **`grid`** (`e:\Repos\grid\site\`) — the real project. Fresh
  `wp core install`, empty DB. This is what the theme/blocks/CPTs get built
  against and what eventually goes live.
- **`grid-legacy`** (`e:\Repos\grid-legacy\`, sibling to the repo, not
  git-tracked) — disposable. Old site's DB dump imported here only, to
  explore the schema and extract content via WP-CLI/SQL. Deleted
  (`ddev delete -O`) once migration is done. Its data never touches `grid`
  directly — migration is a script that reads from here and writes clean
  records into `grid`, not a DB merge.

## Migration approach

1. Get a full DB dump of grid.net.pl (hosting/DB access confirmed
   available).
2. Import into `grid-legacy`, inspect via WP-CLI.
3. Write a transform script: old posts/attachments → new `projekt` /
   `nagroda` / `publikacja` CPTs + ACF fields.
4. Generate an old→new URL diff for the 301 redirect map (old site indexed
   since 2017 — this matters for SEO at launch).
5. Delete `grid-legacy` once extraction is complete.

## Outstanding — blocked on the client

From `projekt/handoff/README.md`, unchanged:

- High-res photos (current assets are ~500px, pulled from the old site)
- Logo in SVG (current is a soft 1018px PNG)
- Links or scans for the 8 publications
- Directions/map graphic
- Contact section photo (marked "do wymiany" / to replace in the mocks)
- Project metrics: structure, services, contractor, photo credits
- Approved FAQ copy (mocks have draft text only — commercial commitments)
- Native-speaker QA on the English translations

## Open technical decisions

- Where contact-form submissions go, and what confirmation the sender gets
- Cookie/privacy policy (mocks only have a GDPR clause under the form)
- Touch fallback for the hover mask (`group-hover` doesn't exist on touch —
  README flags `active:opacity-90` or mask-on-`:active` as options, undecided)
- FAQ section layout on the Kontakt page (client decision in progress per
  the README)

## Rules that hold everywhere (from tailwind-mapping.md)

- No rounded corners anywhere.
- Rules, not boxes — `border-t-2 border-divider` between sections, 1px
  `border-hairline` between table rows. No cards, no shadows.
- Flush left — nothing centred, not headings, not button labels.
- Photos desaturate at rest (`saturate-60`), full color on hover/active.
  Publication covers are `object-contain`; everything else `object-cover`.
- One easing (`cubic-bezier(.2,.7,.2,1)`), three durations (450/600/700ms).
- Accent `#ff6633` fails contrast at body text size — use `accent-700`
  (`#c9420f`) for small text, full accent only for large type/chrome.

## Milestones

1. Environment — DDEV `grid` running, theme scaffold, `theme.json` tokens
   ported.
2. CPTs + ACF fields registered in `grid`.
3. Block library — static blocks first, then dynamic (CPT-backed) blocks.
4. Templates/patterns assembled from blocks, matching the five mock pages.
5. Migration — DB dump → `grid-legacy` → transform script → `grid`.
6. Content gaps filled (client deliverables above).
7. QA (responsive across the four breakpoints, a11y, contrast), redirect
   map live, DNS cutover.
