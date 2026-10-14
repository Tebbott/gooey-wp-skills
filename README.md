# Gooey WordPress skills for Claude Code

Skills that make the whole team produce the same thing, the same way.

## gooey-site-guide-and-masters

Ask Claude, in a Gooey WordPress build (wl-base theme, ACF blocks):

> Create the site guide and the master pages for this site.

and it will, from start to finish:

1. inventory the blocks, content types and page styles from the code,
2. install the master-pages framework in the theme (`master/`, `inc/master.php`, the Master note block),
3. write the builders that create private example pages for every block, page style and content template,
4. build and verify them (private, noindex, hidden from listings, no PHP errors),
5. create the unbranded **Site Guide** plugin and write the guide in the house style.

The masters are code in the theme, so they go to the dev or live site with the theme (Tools > Master pages), with no database export.

See `skills/gooey-site-guide-and-masters/SKILL.md` for the full method, and `examples/` for the finished Crucible build.

## Install

You need [Claude Code](https://claude.com/claude-code) and Git.

```bash
git clone <this repo's URL> gooey-wp-skills
cd gooey-wp-skills
./install.sh            # Mac, Linux, Git Bash
# or on Windows PowerShell:
./install.ps1
```

The scripts copy `skills/*` into `~/.claude/skills/` (your personal skills folder). Restart Claude Code, then ask for the guide and
master pages in the site's folder. To update later, `git pull` and run the script again.

If you previously used the older `master-pages` skill, delete `~/.claude/skills/master-pages`: this skill replaces it.

## Contributing

Improve the skill by editing the files here (the templates are the Crucible implementation, generalised), test on a real build, and
commit. Keep client-specific words out of `templates/`; put worked examples in `examples/`.
