/* eslint no-undef: 0 */
// console.log('SWELL: set_spheadnav');

/**
 * スマホヘッダーナビをセット
 */
function setSpHeadNav() {
	// isPC
	if (959 < window.innerWidth) return;

	const spHeadNav = document.querySelector('.l-header__spNav');
	if (!spHeadNav) return;

	const spHeadNavList = spHeadNav.querySelectorAll('.p-spHeadMenu > li');

	//現在のURLを取得 ? や # はをのぞいて
	const nowHref = window.location.origin + window.location.pathname;
	for (let i = 0; i < spHeadNavList.length; i++) {
		const element = spHeadNavList[i];

		//-currentクラスを付与
		const elemLink = element.querySelector('a');
		const elemHref = elemLink.getAttribute('href');
		element.classList.remove('-current');
		if (nowHref === elemHref) {
			element.classList.add('-current');
		}
	}
}

// 実行
setSpHeadNav();

/**
 * 画面回転時にも発火させる
 */
window.addEventListener('orientationchange', function () {
	// console.log('Do orientationchange.');

	// 縦・横サイズを正確に取得するために少しタイミングを遅らせる
	setTimeout(() => {
		setSpHeadNav();
	}, 10);
});

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(setSpHeadNav);
}
