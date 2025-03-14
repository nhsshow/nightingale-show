#!/bin/bash

# Clear out directory
rm -R nightingale-show/*

# Initialize theme with the base nightingale theme
cp -R nightingale/*       nightingale-show/

# Apply our override theme
cp -R nightingale-child/* nightingale-show/

# Re-apply the functions.php/style.css from the base theme as they work a bit differently.
cp nightingale/functions.php nightingale/style.css nightingale-show/

# Append our override functions.php to the compiled theme
cat nightingale-child/functions.php | tail -n+2 >> nightingale-show/functions.php

# Compile style.css from our WP meta css, the base style.css, and our override style.css
cat nightingale-child/style.meta.css nightingale/style.css nightingale-child/style.css > nightingale-show/style.css
