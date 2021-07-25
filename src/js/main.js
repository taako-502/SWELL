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
import setState, { isPC, smoothOffset, ua } from '@swell-js/modules/data/stateData';

/**
 * モジュール読み込み
 */
import setScrollEvent from '@swell-js/modules/setScrollEvent';
import setIndexList from '@swell-js/modules/setIndexList';
import setPostSlider from '@swell-js/modules/setPostSlider';
import setFixWidget from '@swell-js/modules/setFixWidget';
import setLuminous from '@swell-js/modules/setLuminous';
import setGnavClass from '@swell-js/modules/setGnavClass';
import changeDeviceSize from '@swell-js/modules/changeDeviceSize';
import { setupFixHeader, setFixHeaderPosition } from '@swell-js/modules/setupFixHeader';
// import fixHead from '@swell-js/modules/fixHead';
import addClickEvents from '@swell-js/modules/addClickEvents';
import adClick from '@swell-js/modules/adClick';
import { mvSet } from '@swell-js/modules/setMainVisual';
import { smoothScroll, addSmoothScrollEvent } from '@swell-js/modules/smoothScroll';
import { ajaxToLoadContents } from '@swell-js/modules/wpAjax';
import {
	setOlStartNum,
	setTableScroll,
	removeNoreferrer,
	setParallax,
	setUrlCopy,
} from '@swell-js/modules/setPostContent';

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
 * 状態変数のセット
 */
setState.mediaSize();

/**
 * URLのハッシュ取得
 */
const urlHash = location.hash;

/**
 * Lazyloadへのフック
 * PCとSPで画像切り替える場合の処理
 */
// method.LazyHook(isPC);

/**
 * DOMContentLoaded
 */
document.addEventListener('DOMContentLoaded', function () {
	/* DOMデータを取得 */
	setDomData(DOM);

	/* ヘッダーの高さ取得 */
	setState.headH(DOM.header);

	/* PC用固定ヘッダーの高さ取得 */
	setState.fixBarH(DOM.fixBar);

	/* smoothOffsetをセット */
	setState.smoothOffset(DOM.wpadminbar);

	// スクロールバーの幅
	setState.scrollbarW();

	/**
	 * objectFitImages
	 */
	if (window.objectFitImages) window.objectFitImages();

	/**
	 * スマホ・タブレット縦 と PC・タブレット横による分岐処理
	 */
	changeDeviceSize();

	// fixヘッダー
	setupFixHeader(DOM.fixBar);

	/**
	 * メインスライダー
	 */
	if (null !== DOM.mainVisual) {
		mvSet(DOM.mainVisual);
	}

	/**
	 * 記事スライダー処理
	 */
	if (null !== DOM.postSlider) {
		setPostSlider(DOM.postSlider);
	}

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

	// olのstart属性に対応する
	setOlStartNum();

	// URLコピー

	// パララックス
	setParallax();

	// URLコピー
	setUrlCopy();

	// // Barba
	// setBarba();
});

window.addEventListener('load', function () {
	// html のdata-loadedをセット
	document.documentElement.setAttribute('data-loaded', 'true');

	/* ヘッダーの高さ取得 */
	setState.headH(DOM.header);

	/* PC用固定ヘッダーの高さ取得 */
	setState.fixBarH(DOM.fixBar);
	setFixHeaderPosition(DOM.fixBar);

	/* smoothOffsetをセット */
	setState.smoothOffset(DOM.wpadminbar);

	/**
	 * ヘッダー固定スクリプト
	 */
	// fixHead();

	/**
	 * スライダー画像の遅延読み込み
	 */
	// const mvSlider = document.querySelector('.p-mainVisual.-type-slider');
	// if (mvSlider) {
	//     mvSlideLoad(mvSlider);
	// }

	/**
	 * 追従サイドバーウィジェットの位置・サイズのセット
	 */
	if (null !== DOM.fixSidebar) setFixWidget(DOM.fixSidebar);

	/**
	 * スムースリンクの処理を登録
	 *  !!! 目次リスト生成よりあとに !!!
	 */
	addSmoothScrollEvent(document);

	/**
	 * luminousをセット
	 */
	if (window.Luminous && window.swellVars.useLuminous) setLuminous();

	/**
	 * テーブルを横にスクロール可能に
	 */
	setTableScroll();

	// #つきリンクでページ遷移してきたときに明示的にスクロールさせる
	if (urlHash) {
		const targetID = urlHash.replace('#', '');
		const hashTarget = document.getElementById(targetID); // querySelectorは###などでエラーになる
		if (null !== hashTarget) smoothScroll(hashTarget, smoothOffset);
	}

	// コンテンツの後読み込み
	ajaxToLoadContents();

	/**
	 * SWELLへのリンクを検知して noreferrer 削除する
	 * ! ajaxToLoadContents よりあとで !
	 */
	removeNoreferrer();

	// 広告タグのクリック計測(非ログイン時のみ)
	if (!window.swellVars.isLoggedIn) adClick();
});

/**
 * 画面回転時にも発火させる
 */
window.addEventListener('orientationchange', function () {
	// console.log('Do orientationchange.');

	// 縦・横サイズを正確に取得するために少しタイミングを遅らせる
	setTimeout(() => {
		/* 状態変数のセット */
		setState.mediaSize();

		/* ヘッダーの高さ取得 */
		setState.headH(DOM.header);

		/* PC用固定ヘッダーの高さ取得 */
		setState.fixBarH(DOM.fixBar);

		/* smoothOffsetをセット */
		setState.smoothOffset(DOM.wpadminbar);

		/** スマホ・タブレット縦 と PC・タブレット横による分岐処理 */
		changeDeviceSize();

		/** ヘッダー固定スクリプト */
		// fixHead();

		/** メインスライダー */
		if (null !== DOM.mainVisual) {
			if (window.swellMvSwiper) {
				window.swellMvSwiper.destroy();
				mvSet(DOM.mainVisual);
			}
		}

		/** 記事スライダー処理 */
		if (null !== DOM.postSlider) {
			if (window.swellPsSwiper) {
				window.swellPsSwiper.destroy();
				setPostSlider(DOM.postSlider);
			}
		}
	}, 10);
});

/**
 * 画面リサイズ時の処理
 */
window.addEventListener('resize', function () {
	// console.log('resize');
	setTimeout(() => {
		// setState.scrollbarW();

		/* 状態変数のセット */
		setState.mediaSize();

		/* ヘッダーの高さ取得 */
		setState.headH(DOM.header);

		/* PC用固定ヘッダーの高さ取得 */
		setState.fixBarH(DOM.fixBar);

		/* smoothOffsetをセット */
		setState.smoothOffset(DOM.wpadminbar);

		// スクロールバーの幅
		setState.scrollbarW();

		/** スマホ・タブレット縦 と PC・タブレット横による分岐処理 */
		changeDeviceSize();
	}, 5);
});
