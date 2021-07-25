// const webpack = require('webpack');
const path = require('path');
// const HardSourceWebpackPlugin = require( 'hard-source-webpack-plugin' );

module.exports = {
	mode: 'production',

	// メインとなるJavaScriptファイル（エントリーポイント）
	entry: {
		main: './src/js/main.js',
		main_with_pjax: './src/js/main_with_pjax.js',
		set_prefetch: './src/js/set_prefetch.js',
	},

	// ファイルの出力設定
	output: {
		path: path.resolve(__dirname, 'build/js'),
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
