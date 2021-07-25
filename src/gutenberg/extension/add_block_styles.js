/**
 * @WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * @Self dependencies
 */
import { swellDomain } from '@swell-guten/config';

// 見出しブロック
// registerBlockStyle('core/heading', {
// 	name: 'default',
// 	label: __('Default'),
// });

registerBlockStyle('core/heading', {
	name: 'section_ttl',
	label: _x('Section', 'Block Style', swellDomain),
});

// カラム
// registerBlockStyle('core/paragraph', {
//     name: 'border_sg',
//     label: '線枠（グレー）',
// });

// メディアと文章
const mediaTextStyles = {
	// drop_shadow: '画像に影をつける',
	// default: __('Default'),
	card: __('Card type', swellDomain),
	broken: __('Broken Grid', swellDomain),
};
for (const key in mediaTextStyles) {
	registerBlockStyle('core/media-text', {
		name: key,
		label: mediaTextStyles[key],
	});
}

// リストブロック
const coreListStyles = {
	// default: __('Default'),
	index: __('TOC', swellDomain),
	check_list: '【ul】' + __('Check', swellDomain),
	good_list: '【ul】' + __('Good', swellDomain),
	bad_list: '【ul】' + __('Bad', swellDomain),
	num_circle: '【ol】' + __('Round number', swellDomain),
	note_list: __('Notes', swellDomain),
};

// const coreListStyles = {
//     check_list: 'Check',
//     good_list: 'Good',
//     bad_list: 'Bad',
// };

for (const key in coreListStyles) {
	registerBlockStyle('core/list', {
		name: key,
		label: coreListStyles[key],
	});
}

// 画像ブロック / 動画ブロック
const coreImageStyles = {
	//default: "標準",
	border: _x('Border', 'Block Style', swellDomain),
	shadow: _x('Shadow', 'Block Style', swellDomain),
	photo_frame: _x('Photo frame', 'Block Style', swellDomain),
	browser_mac: 'ブラウザ風',
	desktop: 'デスクトップ風',
	// small:'小さく表示',
};

// 動画ブロックは先に「デフォルト」も追加
// registerBlockStyle('core/video', {
// 	name: 'default',
// 	label: __('Default'),
// });

for (const key in coreImageStyles) {
	registerBlockStyle('core/image', {
		name: key,
		label: coreImageStyles[key],
	});
	registerBlockStyle('core/video', {
		name: key,
		label: coreImageStyles[key],
	});
}

// テーブルブロック
// registerBlockStyle('core/table', {
// 	name: 'default',
// 	label: __('Default'),
// });
registerBlockStyle('core/table', {
	name: 'simple',
	label: __('Simple', swellDomain),
});
registerBlockStyle('core/table', {
	name: 'double',
	label: __('Double line', swellDomain),
});

//引用のデフォルトスタイル削除
wp.domReady(() => {
	unregisterBlockStyle('core/quote', 'large');
	// unregisterBlockStyle('core/pullquote', 'solid-color');
	// unregisterBlockStyle('core/table', 'stripes');
});
