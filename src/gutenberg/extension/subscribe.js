/**
 * @WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
import { subscribe } from '@wordpress/data';

/**
 * wp.data.subscribe : 設定などが変更されたときに呼び出される関数を登録できる。
 */
domReady(() => {
	// ブロック幅を保持する変数
	window.swellBlockWidth = 0;

	// subscribe() : 設定やらなんやらが切り替わる度に発火する
	subscribe(() => {
		// getPreferences() で各種状態を一括で取得できる
		// const allPreferences = wp.data.select('core/edit-post').getPreferences();
		// console.log(allPreferences);

		// デバイスプレビューをの状態を取得するならこの div のサイズ取得する
		// .edit-post-visual-editor.editor-styles-wrapper

		// ブロック幅を取得する。 (必ず存在しているタイトルブロックで計測)
		setTimeout(() => {
			// タイトルブロックの幅を取得
			const titleBlock = document.querySelector('.editor-post-title');
			const blockWidth = titleBlock ? titleBlock.offsetWidth : 0;

			// ブロック幅が変わっていれば更新
			if (blockWidth !== window.swellBlockWidth) {
				window.swellBlockWidth = blockWidth;
				document.documentElement.style.setProperty('--block_width', blockWidth + 'px');
			}
		}, 50);
	});
});
