// const webpack = require('webpack');
const path = require('path');
const srcDir = 'src/js/admin';

// ビルドするファイル群
const entries = {
	admin_script: path.resolve(srcDir, 'admin_script.js'),
	ad_tag: path.resolve(srcDir, 'ad_tag.js'),
	// balloon: path.resolve(srcDir, 'balloon.js'),
	// colorpicker: path.resolve(srcDir, 'colorpicker.js'),
	// count_title: path.resolve(srcDir, 'count_title.js'),
	// mediauploader: path.resolve(srcDir, 'mediauploader.js'),
	// settings: path.resolve(srcDir, 'settings.js'),
	// tinymce: path.resolve(srcDir, 'tinymce.js'),
};

module.exports = {
	mode: 'production',

	// メインとなるJavaScriptファイル（エントリーポイント）
	entry: entries,

	// ファイルの出力設定
	output: {
		path: path.resolve(__dirname, 'build/js/admin'),
		filename: '[name].js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				// query: {
				//     presets: ['react', 'es2015']
				// },
				use: [
					{
						// Babel を利用する
						loader: 'babel-loader',
						// Babel のオプションを指定する
						options: {
							presets: [
								[
									'@babel/preset-env',
									{
										modules: false,
										useBuiltIns: 'usage', //core-js@3から必要なpolyfillだけを読み込む
										corejs: 3,
										// targets: {
										//     esmodules: true,
										// },
									},
								],
							],
						},
					},
				],
			},
		],
	},
	resolve: {
		alias: {
			'@swell-js': path.resolve(__dirname, 'src/js/'),
		},
	},

	// plugins: [ new HardSourceWebpackPlugin() ],
	performance: { hints: false },
};
