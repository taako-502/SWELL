import DOM from '@swell-js/modules/data/domData';
import { isPC, isSP, isTab, isMobile } from '@swell-js/modules/data/stateData';
import setSpHeader from '@swell-js/modules/setSpHeader';
import { setBlockStyle } from '@swell-js/modules/setPostContent';

/**
 * 固定フッターメニューがあれば footerの下に余白つける
 */
const setFooterPaddingBottom = (fixBottomMenu) => {
	const footer = document.getElementById('footer');
	if (null !== footer) {
		if (isPC) {
			footer.style.paddingBottom = '0';
		} else {
			const fixMenuH = fixBottomMenu.offsetHeight;
			footer.style.paddingBottom = fixMenuH + 'px';
		}
	}
};

/**
 * グロナビの移動
 */
export default function () {
	// const fixBar = DOM.fixBar;
	// const header = DOM.header;
	// const gnav = DOM.gnav;
	// const spMenu = DOM.spMenu;
	// const wpadminbar = DOM.wpadminbar;

	// アドミンバーの有無による処理
	// if (null !== wpadminbar && null !== fixBar) {
	// 	fixBar.style.marginTop = '32px';
	// }

	// 固定フッターメニューがあれば footerの下に余白つける
	if (null !== DOM.fixBottomMenu) {
		setFooterPaddingBottom(DOM.fixBottomMenu);
	}

	if (isSP) {
		// タブレット以下のサイズの処理
		setSpHeader();
	}

	// デバイスごとのスタイル処理
	setBlockStyle();
}
