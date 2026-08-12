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
- **Bilingual** — WPML (license already owned; the old site's DB dump
  already carries `icl_*` tables, so PL/EN content is already structured
  through it — build on that rather than switching to Polylang). Separate
  `/pl/` `/en/` URLs (not a client-state toggle) for SEO and linkability.

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

1. ✅ Full DB dump of grid.net.pl imported into `grid-legacy`
   (`mysql20_mydevil_net.sql`, table prefix `wpsite_`, uploads at
   `wordpress/media/` via the site's `UPLOADS` override — not the default
   `wp-content/uploads`).
2. ✅ Inspected via WP-CLI/SQL. Findings:
   - Real `project` custom post type, 55 entries (48 published, 7 draft).
     Not registered in this bare WP-CLI environment (the theme/plugin code
     that registers it isn't installed here), but the raw data is intact
     and queryable directly.
   - Category **and** status are flattened into one taxonomy,
     `project_category`: `do mieszkania` (29), `komercyjne` (27),
     `zrealizowane` (32), `konkursy` (8). Maps to the new split as
     `do mieszkania`→category, `komercyjne`→category,
     `zrealizowane`/`konkursy`→status. No old-side equivalent for the new
     `w realizacji` / `koncepcja` statuses — those are new.
   - `post_content` and `post_excerpt` are empty on every project checked —
     the old site has no written descriptions at all. This confirms
     (doesn't newly discover) the README's outstanding-content list below:
     lead text, description paragraphs, and the whole metrics block are
     genuinely new content, not migratable.
   - 926 attachments, clustered by `post_parent` into per-project galleries
     (8–22 images each) — maps to the new gallery field.
   - `_yoast_wpseo_metadesc` postmeta gives a usable seed for SEO
     descriptions. `post_name` (slug) is what the redirect map is built
     from.
   - Active plugins of note: WPML (`sitepress-multilingual-cms` — license
     owned, carry forward), Contact Form 7 (current live form handler, not
     Gravity Forms — `gf_*` tables in the dump are stale leftovers from a
     since-removed plugin), Yoast SEO, `cookie-law-info` (current cookie
     banner), WPBakery/`js_composer` (built the old "Projekty" page as
     shortcode markup — irrelevant to the rebuild, just explains why the
     page itself has no structured content).
3. Write a transform script: `project` posts + `project_category` terms +
   attachment galleries → new `projekt` CPT + ACF fields, per the mapping
   above.
   - **No `award` or `publication` post type exists anywhere in the old
     DB** — confirmed by checking every distinct `post_type` present.
     `nagroda`/`publikacja` are genuinely new content, not migratable.
   - **`team` post type exists, 4 published entries** (Agnieszka Zając,
     Artur Toboła, Paulina Gogacz-Formella, Martyna Jóska) with real
     structured data in a `team_options` postmeta field: name, job title,
     contribution/role, LinkedIn, plus a featured photo. No bio paragraph
     — same gap pattern as projects. Migrates cleanly into the team grid
     block.
   - `testimonial` post type also exists (3 entries) — dropped. Client
     never mentioned testimonials for the redesign, not part of the
     approved design.
   - ACF is only used for global theme options (`analytics_id`, `logo`,
     `theme settings`, `theme header`) — not for project/team content, so
     no ACF field-group definitions need to be migrated. The `analytics_id`
     value is worth grabbing for GA continuity on the new site.
4. Generate an old→new URL diff for the 301 redirect map, using `post_name`
   slugs (old site indexed since 2017 — this matters for SEO at launch).
5. Delete `grid-legacy` once extraction is complete.

### ✅ First pass complete

`grid-core` plugin registers `projekt`/`zespol`/`nagroda`/`publikacja` CPTs,
`projekt_kategoria`/`projekt_status` taxonomies, and ACF field groups for
all four — all as code, version-controlled
(`site/wordpress/wp-content/plugins/grid-core/`).

Migrated into `grid`: **48 projects** (title, slug, year, category, status,
featured image) and **4 team members** (name, role, title, LinkedIn,
photo). Category/status counts: mieszkalne 26 / przemysłowe 12 / publiczne
10; zrealizowane 29 / koncepcja 12 / konkurs 7 / w realizacji 0.

- 32 projects are flagged `_migracja_do_recenzji` (postmeta) — every
  project touched by the `komercyjne` guess, plus ones with no old-side
  status equivalent (defaulted to `koncepcja`). Original old-site tags
  kept in `_migracja_stare_tagi` for reference. **Not yet reviewed against
  the client — treat these categories as provisional.**
- 4 projects have no image at all (`Dom jednorodzinny, Warszawa`,
  `Park nad Narwią`, `Dom w Brzozach`, `2Handle Polen, Kościan`) — zero
  photos on the old site, gallery included. Needs images from the client
  regardless of migration.
- **Gallery images intentionally skipped this pass** (only the featured
  image was imported per project) — the ~950 old gallery photos are
  low-res and due for replacement anyway. Full gallery import is still
  possible later: the export/import scripts and staged files are sitting
  in `grid-legacy/export-projects.php` and
  `site/wordpress/_migration-staging/` (gitignored, not deleted yet).
- Description/lead text and the metrics block (client, structure,
  services, contractor) were not migrated — confirmed empty on every old
  project, not a gap in extraction.
- `nagroda`/`publikacja` have no old-site source at all — content entry
  only, no migration possible.

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
- Asset prep (batch resize/export of hi-res photos into the sizes each
  component needs) — ACDSee Pro license already available for this

## Open technical decisions

- Where contact-form submissions go, and what confirmation the sender gets.
  The old site's DB shows Gravity Forms + EasyWP SMTP already in use — worth
  checking `gf_form_meta`/`gf_notifications` in `grid-legacy` for the
  current routing before deciding whether to carry that forward.
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
   ✅ 7 blocks built in `grid-blocks` (`@wordpress/scripts`, automatic block
   discovery + `blocks-manifest.php`): Hero, Stat Bar/Stat Item, Highlight
   Plate, Process Steps/Process Step (all static), Team Grid (dynamic —
   server-rendered from the `zespol` CPT via `render.php`, not hand-entered,
   since real team data already exists), Project Grid (dynamic + the
   Interactivity API — category filter and "show more"/"show fewer",
   verified live: filtering, expand, and collapse all work correctly
   end-to-end against the 48 real migrated projects). Still to build: awards
   table (dynamic), publications grid (dynamic), manifesto text.

   Learned along the way, worth remembering:
   - `InnerBlocks` renders its own `block-editor-block-list__layout`
     wrapper between the parent and its children — a flex/grid rule on the
     parent's own class has nothing to act on. Use `useInnerBlocksProps`
     (apiVersion 3) with block.json `supports.layout` instead of hand-rolled
     CSS on the block wrapper; only then do children become direct flex/grid
     items.
   - `theme.json`'s `dimensions.aspectRatios` does **not** generate a
     reusable `--wp--preset--aspect-ratio--*` custom property — it only
     feeds the core Image block's own aspect-ratio picker UI. Use a literal
     `aspect-ratio` value in block CSS instead. (The `settings.custom` key
     is different and does generate real `--wp--custom--*` vars — that part
     works as expected.)
   - `@wordpress/scripts`'s automatic block discovery needs
     `WP_BLOCKS_MANIFEST=true` (wired via `cross-env` in `package.json`) to
     actually emit `blocks-manifest.php` — it's opt-in, not automatic even
     with multiple `block.json` files present.
   - Same story for `viewScriptModule` (how a block ships an Interactivity
     API store): needs `WP_EXPERIMENTAL_MODULES=true` or wp-scripts silently
     skips building it — no error, the file just never appears in `build/`.
     Both flags now set in `package.json`'s `build`/`start` scripts.
   - An author stylesheet's `display` rule always beats the UA stylesheet's
     default `[hidden] { display: none }`, regardless of selector
     specificity, since normal-importance author rules beat normal-importance
     UA rules outright in the cascade. `data-wp-bind--hidden` silently does
     nothing unless the block's own CSS adds an explicit `&[hidden] {
     display: none }` override.
   - Found one real data bug via testing, not guessing: "Europan 7,
     Kristianstad" (the flagship award project, referenced throughout the
     mocks) shows year "203" instead of "2003" — a malformed `post_date` on
     the *old* site, migrated faithfully. Worth flagging to the client;
     not something to silently correct.
4. Templates/patterns assembled from blocks, matching the five mock pages.
5. Migration — DB dump → `grid-legacy` → transform script → `grid`.
6. Content gaps filled (client deliverables above).
7. QA (responsive across the four breakpoints, a11y, contrast), redirect
   map live, DNS cutover.
