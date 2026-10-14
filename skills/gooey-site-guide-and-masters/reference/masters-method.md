# Master pages: method and helper API

Everything lives in the theme (`master/`), so it travels with the theme. The installer is run from **Tools > Master pages**
(`master/admin.php`), from WP-CLI (`wp <theme-slug> masters ...`, `master/cli.php`) or from a script that loads WordPress and calls
`wl_master_load()` then `wlm_build()`.

## How the pieces fit

- `inc/master.php` (loaded on every request): `wl_not_master()` (a meta query clause), `wl_master_status()`, `wl_rows()` (clean repeater
  rows), the global `pre_get_posts` filter that hides masters from every listing, search, archive and block query, the noindex robots
  filter, the "Private:" title cleaner. Loads `master/admin.php` only in the admin and `master/cli.php` only under WP-CLI.
- `master/lib.php` (loaded only when building): the helpers below.
- `master/installer.php`: `wlm_media_import()`, `wlm_seed_content()`, `wlm_build()`, `wlm_set_visibility()`, `wlm_remove()`, `wlm_status()`.
- `master/builders/NN-*.php`: run in file-name order. Each `return function () { ... return 'short result line'; };`.
- `master/media/` + `manifest.json`: the sample photographs. `master/seed/content.json`: sample entries. `master/fieldmaps/*.json`: for each
  content template, which field feeds which part of the page (the hub prints them).
- Every master is flagged with the post meta `_wl_master` (and `_wl_noindex`), sits under the `/master-pages/` parent, is titled
  "Master: ...", and is made **private** at the end of every build.

## Helpers (master/lib.php)

| Helper | Use |
| --- | --- |
| `wlm_blk( $slug, $values, $anchor )` | The serialised ACF block comment. `$values` is keyed by field name; nested repeaters are arrays of rows; omitted nested fields are written as empty strings (otherwise ACF falls back to top-level values). |
| `wlm_note( $title, $purpose, $options, $where, $kind, $anchor )` | The dashed grey Master note above an example. `$kind` is `block`, `page` or `tip`; give blocks an `$anchor` so the long pages get jump links. |
| `wlm_lnk( $title, $url, $target )` | A link field value. |
| `wlm_img( $key )`, `wlm_imgs( $theme )` | Attachment ID(s) of sample photographs by key, imported by the installer. |
| `wlm_page( $slug, $title, $content, $extra )` | Create or update a master page under `/master-pages/` (matched by slug, so a rebuild updates in place). `$extra['meta']` for post meta. |
| `wlm_entry( $type, $slug, $title, $args )` | Create or update a master entry of any content type. `$args`: `fields` (ACF), `terms` (taxonomy => names), `thumbnail`, `meta`, any `wp_insert_post` arg. |
| `wlm_mark( $id, $label )` | Flag a post as a master (called by the two above). |
| `wlm_h`, `wlm_p`, `wlm_list`, `wlm_table` | Core-block markup for headings, paragraphs, lists and tables (hub, legal pages). |
| `wlm_fetch( $url )`, `wlm_check( $id )` | Verification: fetch a page and check status/robots. |

## Rules for builders

1. One example of **every layout, background, variant and option** of every block; the Master note lists what is shown.
2. Fill every optional field somewhere. Repeaters get at least two rows (three for sliders and grids).
3. Use the sample media (`wlm_img`) and existing posts (look them up by slug), never remote URLs.
4. Never put real client secrets, emails or phone numbers in a master; use the site's own Theme Options values if you need them.
5. Titles "Master: ..."; entry slugs `master-...`; keep slugs short and stable (links between masters use them).
6. Do not change settings, menus or Theme Options from a builder. Builders create master pages and master entries only.
7. Idempotent: running twice must not duplicate anything.
8. Keep each file's result a short string (counts, IDs); the installer prints them.

## Hub (99-hub.php)

The hub is the page at `/master-pages/`: what the masters are, how to copy a block or a page, the list of page styles and templates
(links), a directory of every block (read from where each block appears on the masters), a question-and-answer block and the field maps.
Edit the arrays at the top (block pages, page styles, templates) to match what you built; the rest is generic. If a hub link target does
not exist it is left out, so a partial build still works.

## Put them on another site

1. Upload the theme (whole folder, including `master/`) and the plugins the site uses; activate the theme and ACF Pro.
2. Tools > Master pages. Empty or new site: tick **Also add the sample content**, press **Build / refresh the master pages**. Site
   with real content: leave the box unticked; the masters then use the services, testimonials and so on the site has.
3. Open `/master-pages/` signed in. Visitors get a 404.

Remove: the Remove button (the sample-content tick also removes the photographs and sample entries the installer added), or
`wp <theme-slug> masters remove --with-sample`.

## Keeping them current

When a block or template changes, update the matching builder so the master still shows every option, then rebuild. New listing queries
that could return masters for a signed-in editor must add `wl_not_master()` to their meta query.

## Sample media and sample content (formats)

`master/media/manifest.json`: a list, one object per photograph in `master/media/`:

```json
{ "file": "hero-poster.jpg", "key": "hero-poster", "theme": "hero", "width": 1600, "height": 900, "alt": "Describe the picture" }
```

`key` is what builders pass to `wlm_img( 'hero-poster' )`; `theme` groups pictures for `wlm_imgs( 'hero' )`. The installer imports them into
the Media Library once (recorded with the `_wlm_source` meta) and stores the key-to-ID map. Take the pictures from the site's design
export or its own library, resize to about 1400px wide, keep the whole folder around 8 MB, and always write real alt text.

`master/seed/content.json`: a list of entries; `examples/crucible-builders/seed-content.example.json` shows the shape:

```json
{ "type": "testimonial", "slug": "jane-smith-acme", "title": "Jane Smith, Acme", "excerpt": "", "menu_order": 1, "days_ago": 1,
  "content": "", "thumbnail": "", "terms": { "category": ["Marketing"] },
  "fields": { "quote": "...", "avatar": "avatar-jane.jpg", "service": "post_object-or-relationship values as 'type:slug'" } }
```

Image fields take a file name from the manifest; post-object and relationship fields take `"type:slug"` (the target must exist, so
`wlm_seed_content()` creates types in the order of its `$order` array: put the types others point to first). **Edit `$order` and the
`function_exists()` hooks at the end of `wlm_seed_content()` for the site's own post types.** Only entries that do not exist are created,
and they are flagged `_wlm_seed` so Remove can delete them.
