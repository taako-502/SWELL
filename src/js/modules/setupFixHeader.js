import DOM from './data/domData';
import { fixBarH } from './data/stateData';
/**
 * グロナビの移動
 */
export function setupFixHeader(fixBar) {
	if (!fixBar) return;

	const header = DOM.header;

	// ヘッダーウィジェットのコピー
	if (header.classList.contains('-series')) {
		// ヘッダーウィジェットを取得
		const headWidget = header.querySelector('.w-header');

		// ヘッダーウィジェットの移動先を取得
		const fixHeadWrap = fixBar.querySelector('.l-fixHeader__inner');

		if (headWidget && fixHeadWrap) {
			// クローン
			const cloneWidget = headWidget.cloneNode(true);

			//fixバーにコピー
			fixHeadWrap.appendChild(cloneWidget);
		}
	}

	setFixHeaderPosition(fixBar);

	setTimeout(() => {
		fixBar.setAttribute('data-ready', '1');
	}, 250);
}

/**
 * グロナビの移動
 */
export function setFixHeaderPosition(fixBar) {
	if (!fixBar) return;
	fixBar.style.top = 0 - 16 - fixBarH + 'px';
}
