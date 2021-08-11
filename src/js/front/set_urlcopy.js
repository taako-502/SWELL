/* eslint no-undef: 0 */
function setUrlCopy() {
	if (!window.ClipboardJS) return;
	const clipboard = new ClipboardJS('.c-urlcopy');
	clipboard.on('success', function (e) {
		const btn = e.trigger;
		btn.classList.add('-done');
		setTimeout(() => {
			btn.classList.remove('-done');
		}, 3000);
	});
}

// 実行
setUrlCopy();

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(setUrlCopy);
}
