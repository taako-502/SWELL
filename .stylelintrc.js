module.exports = {
    extends: [
        '@wordpress/stylelint-config/scss',
        'stylelint-config-rational-order',
    ],
    ignoreFiles: ['./src/scss/foundation/base/**/*.scss', './src/**/*.js', './assets/**/*', './build/**/*'],
    rules: {
        'max-line-length': null, //max文字数を無視
        'selector-class-pattern': null,
        indentation: 4, //スペースでサイズは4
        'length-zero-no-unit': null,
        'selector-id-pattern': null,
        // 'string-quotes': 'double', //ダブルクォーテーションに (wordpress でそうなってる)
        // 'no-duplicate-selectors': null, //同じセレクタの出現に関するエラーを出さない
        'function-url-quotes': 'never', //不必要なクォーテーションを禁止( これだけ自動Fixできない )
        'no-descending-specificity': null, //セレクタの詳細度に関する警告を出さない
        'number-leading-zero': 'never', //0.5emなどは.5emに
        'font-weight-notation': null, //font-weightの指定は自由
        'font-family-no-missing-generic-family-keyword': null, //sans-serif / serifを必須にするか。object-fitでエラーださないようにする。
        'at-rule-no-unknown': null, //scssで使える @include などにエラーがでないように
        'scss/at-rule-no-unknown': true, //scssでもサポートしていない @ルールにはエラーを出す
        // 'order/properties-alphabetical-order': true, //ABC順
    },
};
