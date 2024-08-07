#!/bin/bash
cp -R nightingale/*       nightingale-show/
cp -R nightingale-child/* nightingale-show/
cp nightingale/functions.php nightingale/style.css nightingale-show/
cat nightingale-child/functions.php | tail -n+2 >> nightingale-show/functions.php
cat nightingale-child/style.meta.css nightingale/style.css nightingale-child/style.css > nightingale-show/style.css
