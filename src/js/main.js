/**
 * ポリフィル
 */
import '@swell-js/modules/polyfill';

/**
 * DOMデータ
 */
import DOM from '@swell-js/modules/data/domData';
import setDomData from '@swell-js/modules/setDomData';

/**
 * 状態を管理する変数データ
 */
import {
	smoothOffset,
	ua,
	setMediaSize,
	setHeightData,
	setScrollbarW,
} from '@swell-js/modules/data/stateData';

/**
 * モジュール読み込み
 */
import setScrollEvent from '@swell-js/modules/setScrollEvent';
import setIndexList from '@swell-js/modules/setIndexList';
import setFixWidget from '@swell-js/modules/setFixWidget';
import setGnavClass from '@swell-js/modules/setGnavClass';
import changeDeviceSize from '@swell-js/modules/changeDeviceSize';
import addClickEvents from '@swell-js/modules/addClickEvents';
import { smoothScroll, addSmoothScrollEvent } from '@swell-js/modules/smoothScroll';
import { pvCount } from '@swell-js/modules/pvCount';
import { lazyLoadContents } from '@swell-js/modules/lazyLoadContents';
import { removeNoreferrer } from '@swell-js/modules/setPostContent';

/**
 * FB内ブラウザのバグに対処
 */
const isFB = -1 !== ua.indexOf('fb');
if (isFB) {
	if (300 > window.innerHeight) {
		location.reload();
	}
}

/**
 * デバイス判定変数のセット
 */
setMediaSize();

/**
 * URLのハッシュ取得
 */
const urlHash = location.hash;

/**
 * Lazyloadへのフック
 * PCとSPで画像切り替える場合の処理
 */
// method.LazyHook(isPC);

window.onpageshow = function (event) {
	if (event.persisted) {
		// なんらかの処理
	}
};

/**
 * DOMContentLoaded
 */
document.addEventListener('DOMContentLoaded', function () {
	// DOMデータを取得
	setDomData(DOM);

	// 高さ取得
	setHeightData();

	// スクロールバーの幅
	setScrollbarW();

	/**
	 * objectFitImages
	 */
	if (window.objectFitImages) window.objectFitImages();

	/**
	 * スマホ・タブレット縦 と PC・タブレット横による分岐処理
	 */
	changeDeviceSize();

	/**
	 * グロナビに -current つける
	 */
	setGnavClass();

	/**
	 * 目次生成
	 */
	setIndexList();

	/**
	 * クリックイベントをまとめて登録
	 */
	addClickEvents(document);

	/**
	 * スクロールイベント（ 単純なbodyWrapへのクラス切り替え処理 ）
	 */
	setScrollEvent();
});

window.addEventListener('load', function () {
	// html のdata-loadedをセット
	document.documentElement.setAttribute('data-loaded', 'true');

	/* ヘッダーの高さ取得 */
	setHeightData();

	/**
	 * 追従サイドバーウィジェットの位置・サイズのセット
	 */
	if (null !== DOM.fixSidebar) setFixWidget(DOM.fixSidebar);

	/**
	 * スムースリンクの処理を登録
	 *  !!! 目次リスト生成よりあとに !!!
	 */
	addSmoothScrollEvent(document);

	// #つきリンクでページ遷移してきたときに明示的にスクロールさせる
	if (urlHash) {
		const targetID = urlHash.replace('#', '');
		const hashTarget = document.getElementById(targetID); // querySelectorは###などでエラーになる
		if (null !== hashTarget) smoothScroll(hashTarget, smoothOffset);
	}

	// PVカウント
	pvCount();

	// コンテンツの後読み込み
	lazyLoadContents();

	/**
	 * SWELLへのリンクを検知して noreferrer 削除する
	 * ! lazyLoadContents よりあとで !
	 */
	removeNoreferrer();
});

/**
 * 画面回転時にも発火させる
 */
window.addEventListener('orientationchange', function () {
	// console.log('Do orientationchange.');

	// 縦・横サイズを正確に取得するために少しタイミングを遅らせる
	setTimeout(() => {
		// デバイス判定変数のセット
		setMediaSize();

		// 高さ取得
		setHeightData();

		// スマホ・タブレット縦 と PC・タブレット横による分岐処理
		changeDeviceSize();
	}, 10);
});

/**
 * 画面リサイズ時の処理
 */
window.addEventListener('resize', function () {
	// console.log('resize');
	setTimeout(() => {
		// デバイス判定変数のセット
		setMediaSize();

		// 高さ取得
		setHeightData();

		// スクロールバーの幅
		setScrollbarW();

		// スマホ・タブレット縦 と PC・タブレット横による分岐処理
		changeDeviceSize();
	}, 5);
});
