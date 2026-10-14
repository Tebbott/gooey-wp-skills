# Gotchas (learned the hard way on the Crucible build)

## WordPress and ACF

- **Masters leaked into live listings for signed-in admins.** WordPress adds private posts to queries for logged-in editors. Fix: the global
  `pre_get_posts` filter in `inc/master.php` adds `wl_not_master()` to every front-end query except explicit single-post lookups
  (`p`, `name`, `post__in`, attachments, menus, patterns). The installer's own queries pass `meta_key => '_wl_master'` to opt out.
- **`get_pages()` drops the children of a private parent**, so the hub's directory looked empty. Use `get_posts()` with
  `post_status => array( 'publish', 'private' )` and `meta_key => '_wl_master'`.
- **`wlm_status()` must look at all post types** (`array_values( get_post_types() )`) or it misses the entries.
- **Seeded repeater rows fall back to the top-level field value** when a nested field is omitted (ACF behaviour). The seed/blk helper
  writes empty strings for omitted nested fields. Do the same in any new seeding code.
- **Titles with `&`** created duplicate posts when looked up by title. Look up by sanitised slug with `get_page_by_path()`.
- **Permalink structure** set through a `pre_option` filter never reached the database, so custom permalinks 404ed. Enforce it with
  `update_option()` in `admin_init`, not a filter.
- Gravity Forms consent fields warn if the field has a `description` key; set `description` to `''`. Purge revisions if the warning persists.
- **Editor CSS and body classes**: the block editor canvas is an iframe and `add_editor_style()` rules are auto-prefixed with
  `.editor-styles-wrapper`. A rule such as `.post-type-career .editor-styles-wrapper ...` can never match. Do not use admin body classes
  in editor styles.
- The `render_block_core/image` filter is the right hook to wrap article images (for an image effect).
- Do not leave a literal backspace in a regex: writing `\b` inside a non-raw Python string produced an invisible `\x08` in the PHP.
  Write PHP with the file tools, not shell heredocs.

## Local tooling (Windows, MAMP)

- Bash heredocs mangle `$` and apostrophes: write PHP and SCSS with the Write/Edit tools.
- Git Bash rewrites leading `/` paths: use `MSYS_NO_PATHCONV=1` or give full URLs.
- `robocopy` from Git Bash returns odd codes: use PowerShell for robocopy.
- Headless Chrome with a tall window inflates `vh` based sections. Capture at a 900px-high viewport with `captureBeyondViewport`.
- Parallel screenshot runs can lose the login cookie: run signed-in captures one at a time.
- If the whole local site suddenly returns "Error establishing a database connection", check `wp-config.php` was not rewritten by
  another tool (compare `DB_NAME` and the table prefix with the database that holds the site).

## Content and design

- Sample photographs: 8 MB in total is plenty; use WebP/JPG at about 1400px wide for heroes.
- The guide and masters must not mention hosting, DNS, migration or deployment steps.
- Keep a master's example text realistic but obviously sample (no real client quotes with real names).
- Feedback overlays (`design-feedback`) and scratch folders must be removed before anything goes to a client; the masters themselves are
  the only dev-only content that ships, and only because they are private.
