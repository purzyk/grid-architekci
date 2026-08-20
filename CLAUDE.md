# GRID Architekci — WordPress rebuild

Custom WordPress FSE block theme + two companion plugins for GRID Architekci,
a Wrocław architecture firm. Rebuilt from Claude-generated Tailwind mockups
into a real site: `wp-content/themes/grid` (theme) + `wp-content/plugins/grid-blocks`
(custom Gutenberg blocks) + `wp-content/plugins/grid-core` (content model:
projekt/zespol/nagroda/publikacja post types, ACF fields, security hardening).

Live dev site: **https://grid.purzyk.usermd.net/** (mydevil.net shared hosting,
not yet the client's real domain).

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

**Server paths:**
- WP root: `~/domains/grid.purzyk.usermd.net/repo/site/wordpress`
  (`public_html` is symlinked to this — deploy is a `git pull` there, or
  direct `scp` for faster iteration during active work)
- `wp-cli` is available as `wp` on the server (always `cd` into the WP root
  first)
- Backups (when taking one before a risky change): a dedicated
  `~/domains/grid.purzyk.usermd.net/backups/` directory, outside the web
  root — never leave a DB dump there longer than needed, delete once the
  change is verified

## Deploy workflow

No CI/CD — everything is a manual SSH deploy. The established pattern:

1. Edit theme/plugin source locally.
2. If it touched `wp-content/plugins/grid-blocks/src/**`: `cd` into
   `grid-blocks/` and `npm run build` (webpack via `@wordpress/scripts`,
   bundles JS and copies `render.php`/`block.json` into `build/`). Deploy
   the `build/` output, not `src/`.
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

## Credentials — none of these live in the repo

- DB credentials: `wp-config-db.php` on the server only (inside the web
  root, protected by an `.htaccess` deny rule — see the open_basedir
  gotcha above for why it's not stored outside the web root).
- WP admin login: username is `purzycki @` (yes, with a literal space and
  `@`) — password lives in the account owner's password manager, not here.
- Never commit secrets to this repo, even though it's currently private —
  it may get handed to the client eventually.

## Deferred / pending

- SMTP / mail delivery (Easy WP SMTP) — explicitly deferred by the client
  to migration time onto the real production domain.
- GA4 / Google Search Console — waiting on real IDs from the client's
  Google account; the old site's Universal Analytics property
  (`UA-36260392-1`) is fully sunset and not worth carrying forward.

## Related repo

`wp-starter-kit` (github.com/purzyk/wp-starter-kit) — reusable hardening
mu-plugin, `.htaccess` snippets, a project-agnostic runbook, and a theme/
blocks-plugin scaffold, extracted from this project for reuse on future
WordPress builds. Worth a look before solving a problem here that's
actually generic (hosting/security/caching), not GRID-specific.
