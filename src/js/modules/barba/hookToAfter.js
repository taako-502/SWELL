/* eslint no-undef: 0 */
/* eslint no-eval: 0 */
/* eslint no-console: 0 */

/**
 * モジュール読み込み
 */
import DOM from '@swell-js/modules/data/domData';
import {
	smoothOffset,
	setMediaSize,
	setHeightData,
	setScrollbarW,
} from '@swell-js/modules/data/stateData';
import setDomData from '@swell-js/modules/setDomData';
import setIndexList from '@swell-js/modules/setIndexList';
import setFixWidget from '@swell-js/modules/setFixWidget';
import setGnavClass from '@swell-js/modules/setGnavClass';
import addClickEvents from '@swell-js/modules/addClickEvents';
import { smoothScroll, addSmoothScrollEvent } from '@swell-js/modules/smoothScroll';
import { pvCount } from '@swell-js/modules/pvCount';
import { lazyLoadContents } from '@swell-js/modules/lazyLoadContents';
import { setBlockStyle, checkTheadFix, removeNoreferrer } from '@swell-js/modules/setPostContent';

/**
 * afterでの処理
 */
export function resetCustomScript({ newBody }) {
	if (newBody) {
		const newSwellScript = newBody.querySelector('#swell_custom_js');
		const newSwellScriptText = newSwellScript ? newSwellScript.textContent : '';

		// カスタムJSの実行
		eval(newSwellScriptText);

		// カスタムJSの中で SWELLHOOK.barbaAfterOnce がセットされればそれも実行
		if (!!window.SWELLHOOK.barbaAfterOnce) window.SWELLHOOK.barbaAfterOnce();
		window.SWELLHOOK.barbaAfterOnce = null; //発火が終わればリセット
	}
}

/**
 * Googleアナリティクス
 */
export function sendGA() {
	if (window.gtag) {
		// console.log('gtag !');

		gtag('event', 'page_view', {
			page_location: window.location.href,
			page_path: window.location.pathname,
			// page_title: 'About',
			// send_to: '<GA_MEASUREMENT_ID>',
		});
	} else if (window.ga) {
		// console.log('ga');
		ga('set', 'page', window.location.pathname);
		ga('send', 'pageview');
	}
}

/**
 * Googleアドセンスの (adsbygoogle = window.adsbygoogle || []).push({}); を全部消す。
 */
export function removeAdScript() {
	const adsScripts = document.querySelectorAll('.adsbygoogle + script');
	if (0 === adsScripts.length) return;
	adsScripts.forEach(function (adsScript) {
		adsScript.parentNode.removeChild(adsScript);
	});
}

/**
 * SNSの埋め込みなど
 */
export function resetSnsScript() {
	const content = document.querySelector('#content');
	if (!content) return;

	// Twitter
	if (window.twttr) {
		window.twttr.widgets.load();
	} else {
		const twScripts = content.querySelectorAll('script[src*="platform.twitter.com"]');
		if (0 < twScripts.length) {
			const twitterjs = document.createElement('script');
			twitterjs.async = true;
			twitterjs.src = '//platform.twitter.com/widgets.js';
			document.getElementsByTagName('body')[0].appendChild(twitterjs);
		}
	}

	//インスタ
	if (window.instgrm) {
		window.instgrm.Embeds.process();
	} else {
		const instaScripts = content.querySelectorAll('script[src*="www.instagram.com"]');
		if (0 < instaScripts.length) {
			const instajs = document.createElement('script');
			instajs.async = true;
			instajs.src = '//www.instagram.com/embed.js';
			document.getElementsByTagName('body')[0].appendChild(instajs);
		}
	}

	// Facebook
	if (window.FB) {
		window.FB.XFBML.parse(); //scriptが既に読み込まれている場合、再読み込みメソッド発火
	} else {
		// 本文への埋め込みがあるかどうか
		const fbScripts = content.querySelectorAll('script[src*="connect.facebook.net"]');
		if (0 < fbScripts.length) {
			const fbjs = document.createElement('script');
			fbjs.id = 'facebook-jssdk';
			fbjs.src = fbScripts[0].src;
			document.getElementsByTagName('body')[0].appendChild(fbjs);
		}

		// いいねボタンがあるかどうか
		const fbLikeScripts = content.querySelector('script.fb_like_script');
		if (fbLikeScripts) eval(fbLikeScripts.textContent);
	}
}

/**
 * Googleアドセンス
 */
export function resetAdsense() {
	const ads = document.querySelectorAll('.adsbygoogle');
	if (0 === ads.length) return;
	// let ct = 0;
	ads.forEach(function (ad) {
		// ad.classList.add('-ct' + ct++);
		if (ad.firstChild) {
			// すでに広告が表示されている場合、なにもしない。(removeすると記事には書いてあったが、動かなかった。)
			// ad.removeChild(ad.firstChild); //これするとダメだった
		} else {
			// 広告を再セット
			try {
				window.adsbygoogle = window.adsbygoogle || [];
				window.adsbygoogle.push({});
			} catch (e) {
				// logMyErrors(e); // 例外オブジェクトをエラー処理部分に渡す
				console.log(e);
			}
		}
	});
}

/**
 * アーカイブウィジェット & カテゴリーウィジェットのドロップダウンを使えるようにする
 */
export function resetWidgetScripts({ newBody }) {
	const content = newBody.querySelector('#content');
	if (0 === content.length) return;

	const widgetScripts = content.querySelectorAll(
		'.widget_archive script, .widget_categories script'
	);
	if (0 === widgetScripts.length) return;

	widgetScripts.forEach((script) => {
		eval(script.textContent);
	});
}

/**
 * SWELLの機能
 */
export function resetSwellScript({ newBody, next }) {
	// isPC / isSPなどをセット
	setMediaSize();

	/**
	 * mainでは ready でやってる処理
	 */
	// objectFitImages
	if (window.objectFitImages) objectFitImages();

	// DOMセットしなおす
	setDomData(DOM);

	// 高さ取得
	setHeightData();

	// スクロールバーの幅
	setScrollbarW();

	//グロナビに -current つける
	setGnavClass();

	// 目次一旦消す
	const tocList = document.querySelectorAll('.p-toc__list');
	if (0 < tocList.length) {
		tocList.forEach((elem) => {
			elem.parentNode.removeChild(elem);
		});
	}

	// 目次生成
	setIndexList();

	// クリックイベントをまとめて登録
	addClickEvents(document.querySelector('[data-barba="container"]')); //更新されたエリアにのみ

	// デバイスサイズによるブロックスタイルのセット
	setBlockStyle();

	/**
	 * mainでは onload でやってる処理
	 */
	// 追従サイドバー
	if (null !== DOM.fixSidebar) setFixWidget(DOM.fixSidebar);

	// スムースリンクの処理を登録 !!! 目次リスト生成よりあとに !!!
	addSmoothScrollEvent(document);

	// テーブルを横にスクロール可能に
	checkTheadFix();

	// #つきリンクでページ遷移してきたときに明示的にスクロールさせる
	const urlHash = next.url.hash;
	if (urlHash) {
		const hashTarget = document.getElementById(urlHash);
		setTimeout(() => {
			if (null !== hashTarget) smoothScroll(hashTarget, smoothOffset);
			document.documentElement.setAttribute('data-scrolled', 'true');
		}, 50);
	}

	// luminousをセット
	// if (window.Luminous && window.swellVars.useLuminous) setLuminous();

	if (!!window.Prism) Prism.highlightAll();

	// PVカウント
	pvCount();

	// コンテンツの後読み込み
	lazyLoadContents();

	// SWELLへのリンクを検知して noreferrer 削除する. !! lazyLoadContents よりあとで !!
	removeNoreferrer();
}
