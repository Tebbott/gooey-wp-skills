# Master pages: how they are built and put on another site

The master (example) pages are **built by code that ships in this theme**. You do not need a database export to put them on the dev site (or anywhere else).

## Put them on the dev site

1. Upload the theme (the whole folder, including `master/`) and the plugins the site uses (ACF Pro, Gravity Forms, FacetWP, Yoast SEO, Site Guide). Activate the theme and ACF Pro.
2. In the WordPress admin go to **Tools > Master pages**.
3. **Empty or new site:** tick *Also add the sample content* and press **Build / refresh the master pages**. This adds the sample photographs, services, case studies, insights, team, careers, testimonials and FAQs (only entries that do not already exist), then builds every master page.
   **Site that already has real content:** leave the box ticked off. The masters then use whatever services, testimonials and so on the site has.
4. Open **/master-pages/** while signed in. Everyone else gets a 404: the pages are private.

With WP-CLI instead: `wp {{THEME_SLUG}} masters build --seed` (add `--only=05` to run one builder, `--publish` to leave them published for previewing), `wp {{THEME_SLUG}} masters private`, `wp {{THEME_SLUG}} masters remove [--with-sample]`, `wp {{THEME_SLUG}} masters status`.

Running the build again updates the pages in place (matched by slug); it never duplicates them.

## What is where

| Path | What |
| --- | --- |
| `master/builders/NN-*.php` | One builder per set of pages. Each returns a function that creates pages/entries. Run in file-name order. |
| `master/lib.php` | Helpers the builders use: `wlm_blk()`, `wlm_note()`, `wlm_page()`, `wlm_entry()`, `wlm_img()`, core-block helpers. |
| `master/installer.php` | Media import, sample content, build, visibility, removal. |
| `master/admin.php`, `master/cli.php` | The Tools screen and the WP-CLI command. |
| `master/media/` | The sample photographs and `manifest.json` (imported into the Media Library on build). |
| `master/seed/content.json` | The sample content (services, case studies, insights...), photographs by file name and related posts by slug. |
| `master/fieldmaps/*.json` | For each content template, which field feeds which part of the page. The hub page prints them. |
| `blocks/master-note/` | The dashed label block used above each example. |
| `inc/master.php` | `wl_not_master()`, `wl_master_status()`, `wl_rows()`, and the filters that keep masters out of archives, search and robots. |

## Rules
- Masters are real pages and entries flagged with the post meta `_wl_master` (and `_wl_noindex`). They sit under **/master-pages/**, are titled "Master: ...", and are made **private** at the end of every build.
- Any new listing query that could return them for a signed-in editor must use `wl_not_master()`. Archives, taxonomy pages and search already do this through `pre_get_posts`.
- When blocks or templates change, update the matching builder file so the master still shows every option, then rebuild.
- Remove everything with `wp {{THEME_SLUG}} masters remove --with-sample` or the Remove button (the sample-content tick also removes the photographs and sample entries the installer added).
