---
name: gooey-site-guide-and-masters
description: Create the Site Guide plugin (a plain-English "how your website works" guide inside WordPress) and the Master pages (one private, fully populated example of every page style, block and content template) for a Gooey WordPress build made of ACF blocks (wl-base theme). Use when asked for "the site guide", "the guide and master pages", "master pages", "example pages", "block library", "document the build for the client", or when a Gooey build is nearly finished and needs documenting before handover. Does both from start to finish so anyone on the team gets the same result.
---

# Site Guide and Master pages (Gooey WordPress builds)

One request, two deliverables, same style on every project:

1. **Master pages**: private pages under `/master-pages/` showing every block (every layout and option), every page style and one fully
   filled entry of every content template (case study, article, vacancy...). They are built by **code that ships inside the theme**, so
   they go to the dev or live site with the theme and are created from **Tools > Master pages** (or WP-CLI). No database export.
2. **Site Guide**: an unbranded plugin that adds "Site Guide" to the WordPress menu and a panel on the Dashboard. A short, plain-English
   guide: how pages, blocks, content types and settings fit together, the common jobs, forms, the master pages and good habits.

The guide mentions and links to the master pages, so build the masters first, then the guide.

Reference worked example: the Crucible build. Its files are in `examples/` (builders, the finished guide, field maps, sample content).
Do not copy Crucible's words into another client's guide; copy the **structure, tone and level of detail**.

## Before you start

- A theme built on **wl-base** (ACF Pro blocks in `blocks/<slug>/` with `block.json`, `template.php` and `acf-json/group_wl_block_<slug>.json`,
  `wl_` function prefix, `wl` text domain). If the theme is not wl-base, stop and ask the user: the helper library reads that structure.
- The blocks and templates are stable and the real content (photos, services, people, posts) exists. The masters borrow real content.
- The site runs locally (MAMP: `http://localhost/<site>/`, PHP at `E:/MAMP/bin/php/php8.0.1/php.exe -c E:/MAMP/conf/php8.0.1/php.ini`).
  Adapt the paths if the user's machine differs. There is usually no WP-CLI: run PHP scripts that `require wp-load.php`.
- Work in the theme's repo on a branch. Commit when each phase is verified.

Ask the user only what you cannot find out: the client name for the guide cover, and (if more than one) which theme/site folder.
Everything else (which blocks, which page styles, what to write) you work out from the code.

## Phase 1: inventory (read, do not guess)

1. List blocks: `blocks/*/block.json` (title, description) and each block's ACF group (`acf-json/group_wl_block_<slug>.json`): every
   field, select choice and conditional. The templates (`blocks/<slug>/template.php`) show what each option really does.
2. List content types (`inc/post-types.php`, `single-*.php`, `archive-*.php`, `acf-json/group_wl_<type>.json`), taxonomies, and
   which blocks read from them (so the guide can say "adding X updates Y").
3. List the page styles that exist (home, about, services, service detail, contact, careers, legal, thank you...) and the site-wide
   settings (Theme Options, menus, forms, newsletter, cookie/consent, SEO plugin).
4. Write the inventory into a scratch file; the builders, the hub and the guide all come from it.

## Phase 2: install the master-pages framework in the theme

Copy `templates/theme/` into the theme (it is the Crucible implementation, generalised):

| From `templates/theme/` | To the theme |
| --- | --- |
| `master/lib.php, installer.php, admin.php, cli.php, README.md` | `master/` |
| `master/builders/98-finish.php, 99-hub.php` | `master/builders/` |
| `inc/master.php` | `inc/master.php` (and load it from `functions.php` like the other `inc` files) |
| `blocks/master-note/*`, `acf-json/group_wl_block_master_note.json` | same paths |
| `sass/_master-note.scss` | `assets/sass/blocks/_master-note.scss` (add the `@use`) |

Then replace the placeholder `{{THEME_SLUG}}` (WP-CLI command namespace, use the theme slug) in `master/cli.php`, `installer.php` and
`README.md`, check the text domain and prefix match the theme, and run the theme build (`npm run build`).

If the theme already has `inc/master.php`, `blocks/master-note` and `master/`, check they match the templates and **do not overwrite**
customised builders; update only the framework files that are older.

Read `reference/masters-method.md` before writing builders.

## Phase 3: write the builders

Builders are PHP files in `master/builders/NN-name.php`; each `return function () { ... };` and creates pages/entries with the helpers.
The numbered files run in order; `99-hub.php` runs last. Suggested split (it follows the Crucible build, see `examples/crucible-builders/`):

- `01` content blocks (heroes, text, image/video, gallery, statistics, banners, CTA)
- `02` cards, listings and proof (logos, services, projects, insights, testimonials, pricing, tabs, FAQ)
- `03` people, forms, contact and careers blocks
- `04` page styles (one finished page per style)
- `05` content templates (one filled entry per content type), plus `master/fieldmaps/<type>.json` saying which field feeds which part
- `98` finish (jump links), `99` hub (adapt the arrays at the top to the pages you built)

**Every block gets: every layout, background and option shown at least once, each with a dashed Master note** (name, what it is for, the
options shown, where to find it in the editor). Every repeater gets rows; every optional field is filled in on at least one example.

Large builds: split the builder files between parallel workers (one file each, no two touching the same file), each with a brief
containing the helper API, the media list and the rules in `reference/masters-method.md`. Then run everything together.

Sample content (formats in `reference/masters-method.md`; edit `wlm_seed_content()`'s `$order` for the site's post types): `master/seed/content.json` (services, case studies, insights, people, vacancies, testimonials, FAQs; photographs by file
name in `master/media/` with a `manifest.json`). Keep the media small (about 8 MB in total). The installer only adds entries that do not
exist, and only when the user ticks "Also add the sample content" (empty or new sites). On a site with real content the masters use that.

## Phase 4: build, verify, make private

Run the build, then verify (all of these, not some):
- Every master page returns 200 for a signed-in admin and 404 for visitors (they are private); `robots` says noindex.
- No PHP notices in the debug log; no broken blocks in the editor (open two or three masters in the block editor, console clean).
- One H1 per page, alt text on every image, no placeholder lorem left on the masters themselves.
- Masters are **excluded from every listing, archive, search, menu and sitemap** (a signed-in admin must not see them in a "latest
  insights" block). This is `wl_exclude_masters_from_listings` in `inc/master.php`; test a listing page while signed in.
- Re-run the build: it updates in place, never duplicates (pages are matched by slug).
- Fresh-site test when you can: copy the theme and plugins to a scratch install, press the button, count pages, check visitors get 404.
  Delete the scratch site afterwards.

## Phase 5: the Site Guide plugin

1. Copy `templates/site-guide-plugin/` to `wp-content/plugins/site-guide/`. It is deliberately **unbranded** (neutral colours, no agency
   name); the only branding is the client's logo on the cover (`assets/logo.png`, a PNG of the client logo, white version if the cover is
   dark) and the client name in the text.
2. Write `views/guide.php` from the skeleton in `templates/site-guide-plugin/views/guide.php`, following `reference/guide-writing.md`
   (sections, tone, length, what to link). Use `examples/crucible-guide.php` as the model for style and level of detail.
3. Activate the plugin. Check the menu item (position 3), the Dashboard panel, the cover logo, every admin link, and that the Master
   pages section links to the real `/master-pages/` page and Tools screen.
4. Keep the plugin out of the theme (it is a plugin so it survives theme changes) but commit it somewhere the team can find it: the
   project's repo under `plugins/site-guide/` or a note in the handover that it must be uploaded.

## Phase 6: wrap up

- Commit the theme (framework, builders, seed, media) and the plugin.
- Tell the user, briefly: where the guide and masters are, how to put them on the dev site (upload theme + plugin, Tools > Master pages,
  tick "Also add the sample content" on an empty site, press Build), and anything they must do by hand.
- The handover email (`gooey-handover` skill) points to the Site Guide; mention it there.
- Never leave feedback overlays, scratch files or test sites behind.

## Style rules (so every guide and master set looks the same)

- British English, plain words, no jargon ("block" and "content type" are explained once, early). Short sentences. Second person ("you").
- The guide is read by the client's editors, not developers: say where to click, what happens, what to avoid. No code, no file paths.
- Titles of masters: "Master: <name>". Slugs: `master-pages/<slug>` for pages; `master-<slug>` for entries.
- Master notes: dashed grey box, one per block, same four parts (name, purpose, options shown, where to find it).
- Never type the coral full stop in headings if the theme adds it in code (check the theme's rule).
- Never mention hosting, deployment or migration details in the guide.

## Gotchas learned on the Crucible build

See `reference/gotchas.md`. Read it before debugging anything odd.
