// import DOM from './data/domData';
import { fixBarH } from './data/stateData';
import DOM from './data/domData';

/**
 * 追従サイドバーウィジェットの位置・サイズのセット
 */
export default function setFixWidget(fixSidebar) {
	let offset = 0;

	offset += fixBarH;

	if (DOM.wpadminbar) {
		offset += DOM.wpadminbar.offsetHeight;
	}

	if (offset) {
		fixSidebar.style.top = offset + 8 + 'px';
		fixSidebar.style.maxHeight = 'calc( 100vh - ' + (offset + 16) + 'px )';
	}

	// 追従サイドバーに複数のウィジェットがあればクラスを付与
	const fixWidgetItems = fixSidebar.querySelectorAll('.c-widget');
	if (1 < fixWidgetItems.length) {
		fixSidebar.classList.add('-multiple');
	}
}
