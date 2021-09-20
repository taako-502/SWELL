const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const path = require('path');

const entries = {}; // ビルドするファイル群
const srcDir = './src/js/admin';
const menus = ['balloon'];

menus.forEach((key) => {
	entries[key + '/index'] = path.resolve(srcDir, key + '/index.js');
});

/**
 * CleanWebpackPlugin （ビルド先のほかのファイルを勝手に削除するやつ） はオフに。
 */
defaultConfig.plugins.shift();

/**
 * exports
 */
module.exports = {
	...defaultConfig, //@wordpress/scriptを引き継ぐ

	//エントリーポイント
	entry: entries,

	//アウトプット先
	output: {
		path: path.resolve(__dirname, 'build/js/admin'),
		filename: '[name].js',
	},
	performance: { hints: false },
};
