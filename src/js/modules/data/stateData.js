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
	// isMobile = window.isMobile,
	isFixHeadSP = window.swellVars.isFixHeadSP;

export const ua = navigator.userAgent.toLowerCase();

export default {
	mediaSize: () => {
		isPC = 959 < window.innerWidth ? true : false;
		isMobile = 600 > window.innerWidth ? true : false;
		isSP = !isPC;
		isTab = !isMobile;
	},
	headH: (header) => {
		if (null !== header) {
			headH = header.offsetHeight;
			document.documentElement.style.setProperty('--swl-headerH', headH + 'px');
		}
	},
	fixBarH: (fixBar) => {
		if (null !== fixBar) {
			fixBarH = fixBar.offsetHeight;
		}
	},
	smoothOffset: (wpadminbar) => {
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
	},
	scrollbarW: () => {
		const scrollbarW = window.innerWidth - document.body.clientWidth;
		document.documentElement.style.setProperty('--swl-scrollbar_width', scrollbarW + 'px');
	},
};
