/**
 * モジュール読み込み
 */
import DOM from '@swell-js/modules/data/domData';
import setState, { isPC, isSP, smoothOffset } from '@swell-js/modules/data/stateData';
import setDomData from '@swell-js/modules/setDomData';
import setIndexList from '@swell-js/modules/setIndexList';
import setPostSlider from '@swell-js/modules/setPostSlider';
import setFixWidget from '@swell-js/modules/setFixWidget';
import setLuminous from '@swell-js/modules/setLuminous';
import setGnavClass from '@swell-js/modules/setGnavClass';
import { ajaxToLoadContents } from '@swell-js/modules/wpAjax';
import addClickEvents from '@swell-js/modules/addClickEvents';
import { smoothScroll, addSmoothScrollEvent } from '@swell-js/modules/smoothScroll';
import { mvSet } from '@swell-js/modules/setMainVisual';
import setSpHeader from '@swell-js/modules/setSpHeader';
import adClick from '@swell-js/modules/adClick';
import {
	setBlockStyle,
	setOlStartNum,
	setTableScroll,
	removeNoreferrer,
	setParallax,
	setUrlCopy,
} from '@swell-js/modules/setPostContent';

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
	setState.mediaSize();

	/**
	 * mainでは ready でやってる処理
	 */
	// objectFitImages
	if (window.objectFitImages) objectFitImages();

	// DOMセットしなおす
	setDomData(DOM);

	// ヘッダーの高さ取得
	setState.headH(DOM.header);

	// PC用固定ヘッダーの高さ取得
	setState.fixBarH(DOM.fixBar);

	//スムーススクロールのオフセット値
	setState.smoothOffset(DOM.wpadminbar);

	// スクロールバーの幅
	setState.scrollbarW();

	// スマホヘッダー
	if (isSP) setSpHeader();

	// メインスライダー
	if (null !== DOM.mainVisual) mvSet(DOM.mainVisual);

	// 記事スライダー処理
	if (null !== DOM.postSlider) setPostSlider(DOM.postSlider);

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

	// olのstart属性に対応する
	setOlStartNum();

	// パララックスセット
	setParallax();

	// URLコピーセット
	setUrlCopy();

	/**
	 * mainでは onload でやってる処理
	 */
	// 追従サイドバー
	if (null !== DOM.fixSidebar) setFixWidget(DOM.fixSidebar);

	// スムースリンクの処理を登録 !!! 目次リスト生成よりあとに !!!
	addSmoothScrollEvent(document);

	// テーブルを横にスクロール可能に
	setTableScroll();

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
	if (window.Luminous && window.swellVars.useLuminous) setLuminous();

	if (!!window.Prism) Prism.highlightAll();

	// コンテンツの後読み込み
	ajaxToLoadContents();

	// SWELLへのリンクを検知して noreferrer 削除する. !! ajaxToLoadContents よりあとで !!
	removeNoreferrer();

	// 広告タグのクリック計測(非ログイン時のみ)
	if (!window.swellVars.isLoggedIn) adClick();
}
