#!/bin/bash

version=$1
version=${version//-/.}

#上の階層へ
cd ../

# 不要なファイルを除いてzip化
zip -r swell.zip swell -x "*/.*" "*/__*" "*/node_modules*" "swell/vendor*" "swell/bin*" "*/package.json" "*/package-lock.json" "*/composer.*" "*/gulpfile.js" "*/webpack.config.*" "*/jsconfig.json" "*/phpcs.xml" "*/README.md" "swell/src/img*" "swell/src/js*" "swell/src/scss*" "swell/src/gutenberg/*.js"

# src削除
# zip -r swell.zip swell -x "swell/src/img*" "swell/src/js*" "swell/src/scss*" "swell/src/gutenberg/*.js" "swell/src/gutenberg/*.js" "!swell/src/(dir)"

# zipから不要なファイルを削除
zip --delete swell.zip  "swell/.*"

zip --delete swell.zip  "*/src/gutenberg/components*" "*/src/gutenberg/extension*" "*/src/gutenberg/format*" "*/src/gutenberg/hoc*" "*/src/gutenberg/utils*"

mv swell.zip _version/swell/swell-${1}.zip