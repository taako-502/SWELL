const path = require('path');
const srcDir = './src/gutenberg/blocks';

// ビルドするファイル群
const entries = {};
entries.index = './src/gutenberg/index.js';
entries.post_editor = './src/gutenberg/post_editor.js';
entries.fa = './src/gutenberg/fa.js';

const blocks = [
	'ab-test',
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

// 全ビルドする場合は 0 に。
if (1) {
	module.exports = entries;
} else {
	module.exports = null;
}
