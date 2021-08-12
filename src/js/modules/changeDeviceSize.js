import DOM from '@swell-js/modules/data/domData';
import { isPC } from '@swell-js/modules/data/stateData';
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
	// 固定フッターメニューがあれば footerの下に余白つける
	if (null !== DOM.fixBottomMenu) {
		setFooterPaddingBottom(DOM.fixBottomMenu);
	}

	// デバイスごとのスタイル処理
	setBlockStyle();
}
