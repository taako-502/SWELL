import DOM from './data/domData';
import { isPC, headH, isFixHeadSP } from './data/stateData';

export default function () {
	/**
	 * ヘッダー固定スクリプト
	 */
	const bodyWrap = DOM.bodyWrap;
	const header = DOM.header;

	if (null === header) return;

	if (isPC) {
		header.style.position = 'relative';
		// bodyWrap.style.paddingTop = '0px';
	} else if (isFixHeadSP) {
		//ヘッダー固定
		header.style.position = 'sticky';
		// bodyWrap.style.paddingTop = headH + 'px';
	}
}
