# Writing the Site Guide

The guide is a single admin screen (plugin `site-guide`, file `views/guide.php`). Its readers are the client's editors. Aim for
**5 to 6 minutes of reading**: about nine short sections, tables for lists, no walls of text. The Crucible guide
(`examples/crucible-guide.php`) is the model.

## Structure (keep the order and the numbers; drop a section only if the site really has nothing for it)

| # | Section (id) | What goes in it |
| --- | --- | --- |
| 01 | The big picture (`big-picture`) | One lede paragraph (pages are stacked blocks; you change content, not layout). Three cards: **Pages**, **Content types**, **Site-wide settings**, each naming this site's real examples. One tip: blocks pull from content types automatically, with one real example. |
| 02 | Content types (`content-types`) | A table: content type (link to its admin list), what it is, where it appears. Group rows ("What visitors read", "Behind the scenes"). Mention the tags/taxonomies editors must set. |
| 03 | Page blocks (`blocks`) | A table of every block (name, what it does), grouped ("Page openers", "Sections", "Closing blocks"). One line each; call out which block gives the page its heading (the H1). Say where to find them: **+ button, then Page Sections**. |
| 04 | Shared content (`shared`) | Anything edited once and shown in many places: synced patterns, Theme Options (logo, contact, social, footer, newsletter), menus, reusable testimonials/FAQs. Where to edit each. |
| 05 | Common jobs (`tasks`) | Numbered recipes for the 4 to 6 things editors do most ("Add a case study", "Add an article", "Add a vacancy", "Change the order of anything"). Click-by-click, tied to this site's field names. |
| 06 | Listings and filters (`listings`) | How listings are filled, what the filters use (which taxonomy), pagination, why an item might not show (draft, missing tag, date). |
| 07 | Forms (`forms`) | Which forms exist, where each is used, where entries are stored/emailed, who to ask to change recipients. Do not describe integrations that are not built yet. |
| 08 | Master pages (`masters`) | What they are, link to `/master-pages/` and to Tools > Master pages (only if the page exists), the three steps to copy a block or page, the note that they are private and can be rebuilt. |
| 09 | Good habits (`tips`) | 5 to 7 bullets: do not type what the theme adds (for example the coral full stop), alt text, image size and format, short headlines, SEO box, preview on a phone. |

End with the sign-off panel: "Need something changed that is not in here?" and one line telling them whom to ask.

## Where the content comes from

Take it from the code, not from memory: block titles and descriptions from `block.json`; field names and select choices from the ACF JSON;
what a block reads from `template.php`; menus and post types from `inc/`. If two blocks look alike, say how to choose.
If something is described in the guide, it must exist on the site now. Check every admin link (`edit.php?post_type=...`) and every
block name against the editor.

## Tone and format

- British English, "you", present tense, short sentences, no jargon (explain *block*, *content type* and *alt text* once).
- Say what to click, in the order they will see it: "Choose **Page Sections** and pick the block".
- Use `<table>`, `<ol>`, `.tip` and `.card` as the CSS provides (see `assets/site-guide.css`). Do not add new styles per project.
- All strings through `esc_html_e( '...', 'site-guide' )`; admin links through the `$sg_admin()` closure with `esc_url`.
- No code, no file paths, no hosting or deployment details, no agency branding. The client's logo on the cover is the only brand.
- The plugin is unbranded on purpose: neutral colours (`--dark`, `--accent` variables in the CSS), so one plugin serves every client.
  If a project needs the client's accent colour, change those two variables only.

## Checks before you call it done

- Open the guide as an editor (not only as admin): `edit_pages` is the capability required.
- Every link works; the Master pages section links appear only when the master pages exist.
- The Dashboard panel shows and links to the guide; the menu item sits just under Dashboard.
- Read it start to finish as a client: nothing assumes knowledge they do not have.
