const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const path = require('path');

let entries = {}; // ビルドするファイル群
let localEntries = null; // ビルドするファイルを限定したい時に使う
try {
	localEntries = require('./webpack.config.local');
} catch (err) {}

// entries
if (localEntries) {
	entries = localEntries;
} else {
	entries.index = './src/gutenberg/index.js';
	entries.post_editor = './src/gutenberg/post_editor.js';
	entries.fa = './src/gutenberg/fa.js';

	const srcDir = './src/gutenberg/blocks';
	const blocks = [
		'ab-test',
		'ab-test-a',
		'ab-test-b',
		'accordion',
		'accordion-item',
		'ad-tag',
		'balloon',
		'banner-link',
		'blog-parts',
		'button',
		'cap-block',
		'dl',
		'dl-dt',
		'dl-dd',
		'faq',
		'faq-item',
		'full-wide',
		'post-link',
		'post-list',
		'restricted-area',
		'rss',
		'step',
		'step-item',
		'tab',
		'tab-body',
		// 旧ブロック
		// 'old-blocks',
	];

	blocks.forEach((key) => {
		entries[key + '/index'] = path.resolve(srcDir, key + '/index.js');
	});
}

/**
 * CleanWebpackPlugin （ビルド先のほかのファイルを勝手に削除するやつ） はオフに。
 */
defaultConfig.plugins.shift();

// ↓ CleanWebpackPlugin が 先頭じゃなくなったとき用
// for (let i = 0; i < defaultConfig.plugins.length; i++) {
// 	const pluginInstance = defaultConfig.plugins[i];
// 	if ('CleanWebpackPlugin' === pluginInstance.constructor.name) {
// 		defaultConfig.plugins.splice(i, i);
// 	}
// }

/**
 * exports
 */
module.exports = {
	...defaultConfig, //@wordpress/scriptを引き継ぐ

	// mode: 'production', // npm start でも圧縮させる

	//エントリーポイント
	entry: entries,

	//アウトプット先
	output: {
		path: path.resolve(__dirname, 'build/blocks'),
		filename: '[name].js',
	},

	resolve: {
		alias: {
			'@swell-guten': path.resolve(__dirname, 'src/gutenberg'),
		},
	},
	// plugins: [ ...defaultConfig.plugins, new HardSourceWebpackPlugin() ],
	performance: { hints: false },
};
