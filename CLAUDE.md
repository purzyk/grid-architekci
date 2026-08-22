# GRID Architekci — WordPress rebuild

Custom WordPress FSE block theme + two companion plugins for GRID Architekci,
a Wrocław architecture firm. Rebuilt from Claude-generated Tailwind mockups
into a real site: `wp-content/themes/grid` (theme) + `wp-content/plugins/grid-blocks`
(custom Gutenberg blocks) + `wp-content/plugins/grid-core` (content model:
projekt/zespol/nagroda/publikacja post types, ACF fields, security hardening).

Live dev site: **https://grid.purzyk.usermd.net/** (mydevil.net shared
hosting, developer's own account — used for iterating/verifying changes
before they go to production).

**Production: https://grid.net.pl/** — the client's real domain, live since
2026-08-21. Runs on the client's own separate mydevil account (SSH alias
`grid-prod`, see below), not the dev account. Cutover was direct
(no staging subdomain): old site backed up, `public_html` cleared, new
WordPress core + this repo's theme/plugins + the dev site's DB (via
`wp search-replace`) + `uploads/` deployed in its place. The old site's
DB used table prefix `b1p8trw_` (kept as-is on import, not renamed).

## No Docker/ddev needed

Despite `.ddev/` being present in the repo, the entire established workflow
for this project does **not** use a local WordPress instance. Every change
gets made by editing the theme/plugin source locally, building with Node if
needed, deploying straight to the live dev server over SSH, and verifying
against the real site. ddev has been unreliable/down for stretches of this
project and was never actually needed — don't set it up unless you
specifically want a local preview environment; it's genuinely optional.

**What you actually need on a new machine:**
- Git
- Node.js + npm (only for the two build steps below)
- SSH access to the server (see next section)

## Server access

SSH alias `grid-deploy` is used throughout — every deploy command in this
project's history is `ssh grid-deploy "..."` / `scp ... grid-deploy:...`.
Set this up identically on any new machine so existing commands/instructions
just work without translation:

1. Generate a **machine-specific** key (don't copy private keys between
   machines — each one gets its own):
   ```
   ssh-keygen -t ed25519 -f ~/.ssh/grid_deploy -C "grid-deploy-<machine-name>"
   ```
2. Add to `~/.ssh/config`:
   ```
   Host grid-deploy
       HostName s7.mydevil.net
       User purzyk
       IdentityFile ~/.ssh/grid_deploy
       IdentitiesOnly yes
   ```
3. Get the new public key (`~/.ssh/grid_deploy.pub`) added to the server's
   `~/.ssh/authorized_keys` — either paste it to a Claude session that
   already has server access (fastest), or add it yourself via the mydevil
   panel / an existing working session.
4. **Use OpenSSH, not PuTTY**, for anything Claude Code will script — Windows
   ships OpenSSH's `ssh`/`scp` by default, and that's what every command in
   this project's history uses. PuTTY's `plink`/`pscp` use different syntax
   and won't match existing instructions.
5. Verify: `ssh grid-deploy "echo ok"`.

**Server paths (dev):**
- WP root: `~/domains/grid.purzyk.usermd.net/repo/site/wordpress`
  (`public_html` is symlinked to this — deploy is a `git pull` there, or
  direct `scp` for faster iteration during active work)
- `wp-cli` is available as `wp` on the server (always `cd` into the WP root
  first)
- Backups (when taking one before a risky change): a dedicated
  `~/domains/grid.purzyk.usermd.net/backups/` directory, outside the web
  root — never leave a DB dump there longer than needed, delete once the
  change is verified

### Production access — `grid-prod`

The client's own, separate mydevil account (`grid.net.pl`). Same setup
pattern as `grid-deploy`, own machine-specific key:

```
ssh-keygen -t ed25519 -f ~/.ssh/grid_prod -C "grid-prod-<machine-name>"
```
```
Host grid-prod
    HostName panel20.mydevil.net
    User gridarchitekci
    IdentityFile ~/.ssh/grid_prod
    IdentitiesOnly yes
```
Verify: `ssh grid-prod "echo ok"`.

**Server paths (production):**
- WP root: `~/domains/grid.net.pl/public_html` — plain directory (not a
  git-repo symlink like dev), files are deployed by direct `scp`, not
  `git pull`. There is no automated deploy for production yet; every
  change is a manual, deliberate `scp` + cache purge, same mechanics as
  dev but a second target.
- DB: `m1048_grid2026` on `mysql20.mydevil.net` (table prefix `b1p8trw_`,
  inherited from the dev DB it was imported from — don't assume `wp_` or
  any other prefix).
- `~/private-backup/` (outside the web root) holds the pre-cutover backup
  of the *old* grid.net.pl site (files tar.gz + DB dump) — don't delete
  without checking with the client first, it's the only safety net for
  anything from the old site that wasn't otherwise migrated.
- `~/backups/` is a **system-owned mydevil directory** (root-owned,
  automated host-level backups) — don't write into it, `mkdir`/`tar` there
  will just fail with a permission error. Use `~/private-backup/` (or
  another directory you create) for anything of your own.

### mydevil's `devil` CLI

Every account-level operation (subdomains/vhosts, DNS zone records, SSL
certs, mail/DKIM, MySQL databases) goes through the `devil` command over
SSH, not a web panel action — `devil <module>` prints full usage for that
module (e.g. `devil www`, `devil dns`, `devil ssl`, `devil mail`,
`devil mysql`). Discovered modules used so far: `www` (add/list vhosts,
per-vhost options like cache/gzip/WAF), `dns` (add/list/del zone records —
`devil dns add <domain> <record> <type> <content> [ttl]`), `ssl` (Let's
Encrypt via `devil ssl www add <ip> le le <domain>`), `mail` (mailboxes,
and `devil mail dkim sign`/`devil mail dkim dns` for DKIM), `mysql` (db/user
list — no `export` subcommand despite appearing in some docs; use
`mysqldump` directly instead, it's on the `$PATH`). `devil dns del` prompts
for interactive `y/N` confirmation, which hangs over a plain `ssh` command —
pipe `echo y |` in front of it.

## Deploy workflow

No CI/CD — everything is a manual SSH deploy. This describes the
`grid-deploy` (dev) target; `grid-prod` (production, `grid.net.pl`) follows
the same `scp` + cache-purge mechanics but is a separate, deliberate,
manual pass — verify on dev first, then repeat the relevant `scp` commands
against `grid-prod`'s paths. The established pattern:

1. Edit theme/plugin source locally.
2. If it touched `wp-content/plugins/grid-blocks/src/**`: `cd` into
   `grid-blocks/` and `npm run build` (webpack via `@wordpress/scripts`,
   bundles JS and copies `render.php`/`block.json` into `build/`). Deploy
   the `build/` output, not `src/`. If the block has a `view.js`
   (Interactivity API), deploy its `view.asset.php` in the same pass — see
   the gotcha below, skipping it leaves the browser serving a stale cached
   script indefinitely.
3. If it touched Tailwind classes anywhere in the theme (templates, parts,
   blocks, seed-content): `cd` into `themes/grid/` and `npm run build`
   (plain `tailwindcss` CLI, outputs `assets/css/tailwind-build.css`).
   **Easy to forget** — a class used only in a newly-added file won't be in
   the compiled CSS until this runs, even though the HTML looks right.
4. `scp` the changed files to the server (or `git push` + `ssh grid-deploy
   "cd .../repo && git pull"` if going through GitHub first — both patterns
   are used in this project's history, direct scp is faster for
   iterate-and-verify, git push is the system of record).
5. **Purge WP Super Cache after any change that affects rendered output**:
   `ssh grid-deploy "rm -rf ~/domains/grid.purzyk.usermd.net/repo/site/wordpress/wp-content/cache/supercache/*"`
   — otherwise you'll verify against a stale cached page and think the
   change didn't work.
6. Verify against the live site (`curl`, or a browser/Playwright check) —
   don't assume a deploy worked, this project has hit real regressions that
   only showed up on the actual server (see gotchas below).
7. Commit and push to GitHub if not already done. **If you edited a file
   directly on the server** (fixing something urgently, or via `wp eval`),
   always pull it back down and commit it — several past regressions came
   from server state silently diverging from the repo.

## What's tracked in git vs not

See `.gitignore` for the authoritative list, but the short version: WP core
(`wp-admin/`, `wp-includes/`, `wp-*.php`), `wp-config.php`, uploads, and
third-party plugins are **not** tracked — only `grid-core/` and
`grid-blocks/` under `wp-content/plugins/` are ours and tracked. `.htaccess`
and `.user.ini` **are** tracked despite living at the WP root, and are easy
to forget since they mostly get edited directly on the server — always sync
them back.

## Known gotchas (learned the hard way on this project)

- **Never gate visible markup or a script's presence on `$_COOKIE`/session
  state in PHP** — WP Super Cache serves one static HTML file per URL to
  every anonymous visitor, generated from whichever request happened to
  trigger it. A server-side `if ($_COOKIE['x'] === 'y')` bakes in *that*
  visitor's state and serves it to everyone else regardless of their own
  cookie. Hit this with the cookie-consent banner: accept on the homepage,
  navigate away, navigate back — cached page, banner reappears, cookie
  ignored. Same trap would have applied to gating GA4 itself on consent in
  PHP. Fix: render identical markup/scripts for every visitor, and read
  `document.cookie` client-side (in the block's `view.js`) to decide what
  to show/run — exactly why `theme-toggle.js` never touches PHP for
  dark/light state either. Logged-in requests are the exception (WP Super
  Cache doesn't cache those), so role checks (`is_user_logged_in()`) are
  still safe to gate in PHP; anything that varies per anonymous visitor
  is not.
- **`open_basedir` jail**: mydevil's PHP-FPM is jailed to `public_html`
  (`site/wordpress/`) — PHP cannot `require`/read anything outside it, even
  though SSH/shell can see the whole account. The classic "move
  `wp-config-db.php` above the web root" security trick doesn't work here;
  it breaks the site instantly. Keep such files inside the web root and
  rely on `.htaccess` deny rules instead.
- **Simple Custom Post Order plugin**: treats `menu_order === 0` as
  "unplaced" and silently auto-repositions it via its `save_post` hook —
  never seed/assign `0` as a real position, start from `1`.
- **`get_posts()` defaults to `suppress_filters => true`**, and that same
  plugin's `pre_get_posts` hook specifically rewrites a literal
  `orderby => 'date'` to `menu_order` in that case — pass
  `'suppress_filters' => false` (or deactivate the plugin) if you need a
  query's *real* date order for something like a one-off migration script,
  otherwise it'll silently lie to you.
- **`wp eval` over SSH with inline PHP and escaped quotes is unreliable**
  for anything beyond trivial one-liners — Polish diacritics, apostrophes,
  and nested quoting through bash→ssh→PHP routinely break it. Prefer
  writing the PHP to a local file, `scp` it up, and run
  `wp eval-file <path>` instead. More reliable every time this was tested.
- **WP Super Cache + PHP-level compression are two separate on/off
  switches** (`zlib.output_compression` in `.user.ini` vs.
  `$cache_compression` in `wp-content/wp-cache-config.php`) — disabling one
  without checking the other leaves the site either double-compressing or
  not compressing at all. Verify with
  `curl -H "Accept-Encoding: gzip, br" -D -` and check for
  `Content-Encoding` in the response.
- **WordPress's default `sizes` attribute** on responsive images assumes an
  image fills the viewport below its registered width — wrong for anything
  in a CSS grid/column layout, and it costs real bandwidth silently. Always
  pass an explicit `sizes` matching the actual rendered box width when a
  block places an image in a multi-column layout.
- **Project ordering is manual** (`menu_order`, via the Simple Custom Post
  Order plugin), not by date — `project-nav`'s prev/next deliberately does
  *not* use core's `get_previous_post()`/`get_next_post()` (those are
  date-based and would drift out of sync) — it walks the same
  `orderby=menu_order` ID list `project-grid` queries by.
- **A block's `view.js` (Interactivity API) must always be deployed together
  with its `view.asset.php`** — that file carries the build's version hash,
  which is what ends up in the `<script src="...view.js?ver=HASH">` tag, and
  that URL is served with `Cache-Control: public, max-age=2592000` (30
  days). Deploy a new `view.js` without its matching `view.asset.php` and
  the `?ver=` stays the old hash, so browsers that already loaded the page
  keep serving the stale cached script indefinitely — WP Super Cache being
  clean doesn't help, since this is the *browser's* cache on a versioned
  asset URL, not the page cache. Symptom: a JS fix looks like it did
  nothing, even though `curl`ing the file directly shows the new content.
  Always `scp` both files (and re-check the `?ver=` on the page after
  deploying, e.g. via a Playwright `document.querySelector(...).src`
  check, not just a `curl` of the raw file).
- **`grid.net.pl`'s DNS has a wildcard `* CNAME grid.net.pl` record** — any
  subdomain that isn't otherwise explicitly defined (including special
  ones like `_dmarc` or a DKIM selector) silently resolves through it. This
  is *convenient* for throwaway subdomains (`archiwum.grid.net.pl` worked
  the moment it was created in `devil www add`, zero DNS steps) but it also
  means a lookup succeeding doesn't prove a real record exists — a
  wildcard match and an explicit record are indistinguishable from a
  single `nslookup`. An explicit record for an exact name always wins over
  the wildcard, so adding one (e.g. the DMARC record) works fine; just
  don't assume "it resolves" means "it's configured."
- **Archiving a legacy site into flat HTML with `wget --mirror` on Windows**:
  do the actual crawl and any post-processing (renaming files, rewriting
  links) *inside* a Linux container against the mirror directory, not with
  Windows-native tools (Node.js, PowerShell) against the same files even if
  they're bind-mounted into the container. Versioned assets (`style.css?ver=X`)
  get mirrored with a literal `?` in the filename, which is illegal on
  NTFS — Docker Desktop's Linux↔Windows filesystem bridge silently
  represents that character differently depending which side reads it, so
  a Windows-native script seeing "the same" files gets different (mangled)
  filenames than a Linux one does, and a regex/rename pass that works
  perfectly via `sh`/`sed` inside the container silently matches nothing
  when run via Node on the host. Symptom: a page loads but is completely
  unstyled (every asset 404s) even though the files are visibly right
  there when you `ls` them from Windows.
- **WPML assumes every theme/plugin string is written in English by
  default** — wrapping a Polish string in `__( '...', 'grid' )` and running
  WPML's "Lokalizacja motywu i wtyczek" scan does NOT make it show up for
  translation the way you'd expect: WPML registers it with source language
  = English, so the "+" translate icon in Tłumaczenie ciągów znaków asks
  you to supply the *Polish* text (treating your Polish string as if it
  needs translating *into* Polish from an English original that doesn't
  exist). Fix is a one-time setting per text domain: WPML → Tłumaczenie
  ciągów znaków → "Ustaw oryginalny język motywów i wtyczek" → pick the
  `grid` domain → reassign its existing strings' source language to
  Polski, and tick "use this as the default for new strings in this
  domain" so future scans get it right automatically. Do this *before*
  translating anything, or the translations you enter will be backwards.
  Separately: the String Translation popup's textarea saves on plain
  **Enter** (Shift+Enter inserts a newline) — there's no visible Save
  button, and Tab/blur alone does not persist the value.

## Credentials — none of these live in the repo

- DB credentials: `wp-config-db.php` on the server only — dev has it as a
  standalone `require`d file, production keeps `DB_*` in `wp-config.php`
  directly (see the open_basedir gotcha above for why it's not stored
  outside the web root either way).
- WP admin login: username is `purzycki @` (yes, with a literal space and
  `@`) — password lives in the account owner's password manager, not here.
  Same login works on production (its DB was imported from dev).
- `archiwum.grid.net.pl` (old-site static archive) is behind HTTP Basic
  Auth — credentials live in the account owner's password manager, not
  here; `.htpasswd` sits outside `public_html` on `grid-prod` at
  `~/domains/archiwum.grid.net.pl/.htpasswd`.
- Never commit secrets to this repo, even though it's currently private —
  it may get handed to the client eventually.

## Deferred / pending

- ~~SMTP / mail delivery~~ — done post-launch: Easy WP SMTP routes through
  `mail20.mydevil.net`, DKIM signing enabled (`devil mail dkim sign` +
  `devil mail dkim dns`) and a `_dmarc.grid.net.pl` TXT record added
  (`p=none`, monitor-only — `rua` reports to `info@grid.net.pl`, not
  `noreply@`, since reports need a monitored inbox). Confirmed a test
  email landed in the inbox, not spam.
- ~~GA4~~ — confirmed live and firing on the real domain post-launch
  (verified via the consent banner + checking `dataLayer`/the gtag
  request in a real browser).
- **Google Search Console** — still pending: verify domain ownership,
  submit `sitemap_index.xml` for `grid.net.pl`.
- Old site's Universal Analytics property (`UA-36260392-1`) is fully
  sunset — not worth carrying forward.
- Archive subdomain (`archiwum.grid.net.pl`, see the archival gotcha
  below) — agree a retention window with the client, then tear it down.

## Related repo

`wp-starter-kit` (github.com/purzyk/wp-starter-kit) — reusable hardening
mu-plugin, `.htaccess` snippets, a project-agnostic runbook, and a theme/
blocks-plugin scaffold, extracted from this project for reuse on future
WordPress builds. Worth a look before solving a problem here that's
actually generic (hosting/security/caching), not GRID-specific.
