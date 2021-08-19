const { src, dest } = require('gulp');

// エラー時処理
const plumber = require('gulp-plumber'); // 続行
const notify = require('gulp-notify'); // 通知

// sass・css系
const sass = require('gulp-sass'); // sassコンパイル
const sassGlob = require('gulp-sass-glob'); // glob (@importの/*を可能に)
const autoprefixer = require('gulp-autoprefixer'); // プレフィックス付与
const gcmq = require('gulp-group-css-media-queries'); // media query整理
const cleanCSS = require('gulp-clean-css');

// JS Concat
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
// const rename = require('gulp-rename');
const concat = require('gulp-concat');

/**
 * パス
 */
const path = {
	src: {
		scss: 'src/scss/**/*.scss',
		plugins: 'src/js/plugin/*.js',
		js: 'src/js/**/*.js',
	},
	dest: {
		css: 'assets/css',
	},
};

/**
 * scss > css
 */
const compileScss = () => {
	return (
		src(path.src.scss)
			.pipe(
				plumber({
					errorHandler: notify.onError('<%= error.message %>'),
				})
			)
			.pipe(sassGlob())
			.pipe(sass())
			.pipe(
				autoprefixer({
					cascade: false,
				})
			)
			.pipe(gcmq())
			// .pipe(sass({ outputStyle: 'compressed' }))  //gcmqでnestedスタイルに展開されてしまうので再度compact化。
			.pipe(cleanCSS())
			.pipe(dest(path.dest.css))
	);
};

/*
 * プラグインスクリプトをまとめる
 */
const concatPlugins = () => {
	return src(path.src.plugins)
		.pipe(
			plumber({
				errorHandler: notify.onError('<%= error.message %>'),
			})
		)
		.pipe(concat('plugins.js'))
		.pipe(dest('assets/js'));
};

exports.compileScss = compileScss;
exports.concatPlugins = concatPlugins;
