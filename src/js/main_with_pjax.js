/**
 * main.js
 */
import '@swell-js/main.js';

/**
 * Braba
 */
import barbaInit from '@swell-js/modules/barba/init';
import {
	resetMeta,
	resetSwellStyle,
	resetBodyClass,
	resetHeader,
	resetAnimationCSS,
	resetAdminBar,
} from '@swell-js/modules/barba/hookToBeforeEnter';
import {
	resetCustomScript,
	resetSwellScript,
	sendGA,
	removeAdScript,
	resetAdsense,
	resetSnsScript,
	resetWidgetScripts,
} from '@swell-js/modules/barba/hookToAfter';

// Promise をサポートしているかどうか
const isPromiseSupported = 'undefined' !== typeof Promise;

// IntersectionObserverをブラウザがサポートしているかどうか
const isObserveSupported =
	window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;

/**
 * Barbaフックに追加する処理をまとめる Setオブジェクト
 */
window.SWELLHOOK = {};
window.SWELLHOOK.barbaBeforeEnter = new Set();
window.SWELLHOOK.barbaAfter = new Set();

/**
 * init()
 */
const barbaWrapper = document.querySelector('[data-barba="wrapper"]');
if (barbaWrapper && isPromiseSupported && isObserveSupported) {
	console.log('[SWELL] Barba on'); // eslint-disable-line no-console

	barbaInit();

	/**
	 * フック処理追加
	 */
	// beforeEnter() へのフック
	window.SWELLHOOK.barbaBeforeEnter.add(resetMeta);
	window.SWELLHOOK.barbaBeforeEnter.add(resetSwellStyle);
	window.SWELLHOOK.barbaBeforeEnter.add(resetAnimationCSS);
	window.SWELLHOOK.barbaBeforeEnter.add(resetBodyClass);
	window.SWELLHOOK.barbaBeforeEnter.add(resetHeader);
	window.SWELLHOOK.barbaBeforeEnter.add(resetAdminBar);

	// after() へのフック
	window.SWELLHOOK.barbaAfter.add(removeAdScript);
	window.SWELLHOOK.barbaAfter.add(resetCustomScript);
	window.SWELLHOOK.barbaAfter.add(resetSwellScript);
	window.SWELLHOOK.barbaAfter.add(sendGA);
	window.SWELLHOOK.barbaAfter.add(resetAdsense);
	window.SWELLHOOK.barbaAfter.add(resetSnsScript);
	window.SWELLHOOK.barbaAfter.add(resetWidgetScripts);

	/**
	 * 削除するなら
	 */
	// window.barbaBeforeEnter.delete(swellBarbaBeforeEnter);
}
