#!/usr/bin/env sh
# Copy the skills in this repo into your personal Claude Code skills folder.
set -e
here="$(cd "$(dirname "$0")" && pwd)"
dest="${CLAUDE_SKILLS_DIR:-$HOME/.claude/skills}"
mkdir -p "$dest"
for dir in "$here"/skills/*/; do
	name="$(basename "$dir")"
	rm -rf "$dest/$name"
	cp -R "$dir" "$dest/$name"
	echo "Installed $name -> $dest/$name"
done
echo "Restart Claude Code to pick up the skills."
