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

	// スマホヘッダーナビのswiper処理

	//すでにset関数が実行されたかどうかで分岐
	// if (!spHeadNav.classList.contains('show_')) {
	//     setSpHeadNav(spHeadNav);
	// }

	const isMenuLoop = '1' === spHeadNav.getAttribute('data-loop');

	const swipeOption = {
		loop: isMenuLoop ? true : false,
		centeredSlides: isMenuLoop ? true : false,
		autoplay: false,
		speed: 600,
		runCallbacksOnInit: true,
		slidesPerView: 'auto',
		autoResize: false,
		spaceBetween: 0,
		// on: {
		//     init: function() {
		//         setTimeout(() => {
		//             spHeadNav.classList.add('show_');
		//         }, 10);
		//     },
		// },
	};
	let swiperIndex = 0;
	const spHeadNavList = spHeadNav.querySelectorAll('ul.swiper-wrapper > li');

	//現在のURLを取得 ? や # はをのぞいて
	const nowHref = window.location.origin + window.location.pathname;
	for (let i = 0; i < spHeadNavList.length; i++) {
		const element = spHeadNavList[i];
		// element.classList.add('swiper-slide');

		//-currentクラスを付与
		const elemLink = element.querySelector('a');
		const elemHref = elemLink.getAttribute('href');
		element.classList.remove('-current');
		if (nowHref === elemHref) {
			element.classList.add('-current');

			//スライダーのインデックス番号をセット（-currentは複数あるので初回だけ）
			if (0 === swiperIndex) {
				swiperIndex = i;
			}
		}
	}
	swipeOption.initialSlide = swiperIndex;
	new Swiper(spHeadNav, swipeOption);
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
