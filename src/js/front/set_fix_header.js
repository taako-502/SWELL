/**
 * 固定ヘッダーのセットアップ
 */
function setupFixHeader() {
	const header = document.getElementById('header');
	if (null === header) return;

	const fixHeader = document.getElementById('fix_header');
	if (null === fixHeader) return;

	// ヘッダーウィジェットのコピー
	if (header.classList.contains('-series')) {
		// ヘッダーウィジェットを取得
		const headWidget = header.querySelector('.w-header');

		// ヘッダーウィジェットの移動先を取得
		const fixHeadWrap = fixHeader.querySelector('.l-fixHeader__inner');

		if (headWidget && fixHeadWrap) {
			// クローン
			const cloneWidget = headWidget.cloneNode(true);

			//fixバーにコピー
			fixHeadWrap.appendChild(cloneWidget);
		}
	}

	setTimeout(() => {
		fixHeader.setAttribute('data-ready', '1');
	}, 250);
}

document.addEventListener('DOMContentLoaded', function () {
	setupFixHeader();
});
