# NHSScotland WordPress Theme
This is a compiled theme that is based on the NHS Leadership Academy Nightingale Theme (https://en-gb.wordpress.org/themes/nightingale/).  
It has been customised to apply NHS Scotland colours, add additional custom templates, and change the font used (due to licensing).

![Example Image](./.github/screenshot.png)

## Usage
- Download the latest copy Nightingale SHOW from the releases - https://github.com/nhsshow/nightingale-show/releases/latest/download/theme.zip
- Extract this and drop it into `wp-content/themes`.
- Enable the `nightingale-show` theme.

## Development
Due to this theme being a compilation of two seperate themes, doing local development does unfortunately require said compilation prior to testing.
```bash
git clone --recurse-submodules git@github.com:nhsshow/nightingale-show.git
cd nightingale-show

cd src
bash ./build.sh
```

When the upstream repos need changes, a few things are required due to git submodules being awkward with tags.  
```bash
git submodule update --remote --merge --recursive # This may error, that's fine.

declare -A SUBMODULES=(
  ["src/nightingale"]="v2.7.5"
  ["src/nightingale-child/vendor/plugin-update-checker"]="v5.5"
)
for SUBMODULE in "${!SUBMODULES[@]}"; do
  TAG="${SUBMODULES[$SUBMODULE]}"
  (cd "$SUBMODULE" && git fetch && git switch --detach tags/$TAG)
  git submodule set-branch -b "tags/$TAG" "$SUBMODULE"
done
```

If you are pulling in new changes (if updated by others), it's required to update the submodule manually.
```bash
git submodule update --init --recursive
```

## Thanks
Thanks go to:
- NHS Leadership Academy for the original Nightingale theme - https://github.com/NHSLeadership/nightingale-2-0
- Plugin Update Checker for easy updating a non wp.org theme - https://github.com/YahnisElsts/plugin-update-checker
