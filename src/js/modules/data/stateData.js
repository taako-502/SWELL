import DOM from '@swell-js/modules/data/domData';

/**
 * 状態を管理する変数のオブジェクト
 */
export let headH = 0,
	fixBarH = 0,
	isPC = false, //PCサイズ以上
	isSP = false, // Tab縦「以下」( = !isPC)
	isMobile = false, // mobile以下
	isTab = false, //Tab縦「以上」か ( = !isMobile)
	smoothOffset = 0,
	isFixHeadSP = window.swellVars.isFixHeadSP;

export const ua = navigator.userAgent.toLowerCase();

/**
 * デバイス判定変数セット
 */
export const setMediaSize = () => {
	isPC = 959 < window.innerWidth ? true : false;
	isMobile = 600 > window.innerWidth ? true : false;
	isSP = !isPC;
	isTab = !isMobile;
};

/**
 * デバイス判定変数セット
 */
export const setScrollbarW = () => {
	const scrollbarW = window.innerWidth - document.body.clientWidth;
	document.documentElement.style.setProperty('--swl-scrollbar_width', scrollbarW + 'px');
};

/**
 * ヘッダーの高さをセット
 */
const setHeaderH = (header) => {
	if (null === header) return;

	headH = header.offsetHeight;
	document.documentElement.style.setProperty('--swl-headerH', headH + 'px');
};

/**
 * FIXヘッダーの高さをセット
 */
const setFixHeaderH = (fixHeader) => {
	if (null === fixHeader) return;

	fixBarH = fixHeader.offsetHeight;
	document.documentElement.style.setProperty('--swl-fix_headerH', fixBarH + 'px');
};

/**
 * スムーススクロール時のオフセット値をセット
 */
const setSmoothOffset = (wpadminbar) => {
	smoothOffset = 0;
	if (isPC) {
		// PC表示
		smoothOffset += fixBarH;
	} else if (!isPC && isFixHeadSP) {
		// SP表示・かつヘッダー固定時
		smoothOffset += headH;
	}

	// 管理バーがある時
	if (null !== wpadminbar) {
		smoothOffset += wpadminbar.offsetHeight;
	}

	document.documentElement.style.setProperty('--swl-offset_y', smoothOffset + 'px');

	smoothOffset += 8; //スクロール位置を少しだけ下へ。
};

/**
 * 高さ系のデータをセット
 */
export const setHeightData = () => {
	setHeaderH(DOM.header);
	setFixHeaderH(DOM.fixHeader);
	setSmoothOffset(DOM.wpadminbar);
};
