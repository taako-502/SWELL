/* eslint no-undef: 0 */
// console.log('SWELL: set_ps.js');

const isPC = 959 < window.innerWidth ? true : false;

/**
 * メインスライダー
 */
setPostSlider();

// 画面回転時にも発火させる
window.addEventListener('orientationchange', () => {
	setTimeout(() => {
		if (window.swellPsSwiper) {
			window.swellPsSwiper.destroy();
			setPostSlider();
		}
	}, 10);
});

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(setPostSlider);
}

/**
 * 記事スライダーをセットする関数
 * ! postSliderがあるときにだけ呼び出されるように !
 */
function setPostSlider() {
	const pSlider = document.getElementById('post_slider');
	if (null === pSlider) return;

	const swellVars = window.swellVars;
	if (swellVars === undefined) return;

	const swiperContainer = pSlider.querySelector('.swiper-container');
	if (null === swiperContainer) {
		// もしスライダークラスが見つからなければ
		pSlider.classList.add('show_');
		return;
	}

	const swipeOption = {
		loop: true,
		effect: 'slider',
		preloadImages: false,
		lazy: {
			loadPrevNext: true,
		},
		autoplay: {
			delay: parseInt(swellVars.psDelay) || 10000,
			disableOnInteraction: false,
		},
		speed: parseInt(swellVars.psSpeed) || 1200,
		pagination: {
			el: '.p-postSlider .swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '.p-postSlider .swiper-button-next',
			prevEl: '.p-postSlider .swiper-button-prev',
		},
		runCallbacksOnInit: true,
		on: {
			init() {
				setTimeout(() => {
					const thumb = pSlider.querySelector('.p-postList__thumb');
					if (thumb) {
						const thumbHelfH = thumb.offsetHeight / 2;
						const prevNav = pSlider.querySelector('.swiper-button-prev');
						const nextNav = pSlider.querySelector('.swiper-button-next');
						if (prevNav && nextNav) {
							prevNav.style.top = thumbHelfH + 'px';
							nextNav.style.top = thumbHelfH + 'px';
						}
					}
					pSlider.classList.add('show_');
				}, 10);
			},
		},
	};

	const sliderNum = isPC ? parseFloat(swellVars.psNum) : parseFloat(swellVars.psNumSp);
	swipeOption.slidesPerView = sliderNum;
	swipeOption.spaceBetween = 0;
	swipeOption.centeredSlides = true;
	// if (1 === sliderNum % 2) {
	//     // スライドの枚数が奇数なら
	//     let prevButton = pSlider.querySelector('.swiper-button-prev');
	//     let nextButton = pSlider.querySelector('.swiper-button-next');
	//     if (prevButton) {
	//         prevButton.style.left = '8px';
	//     }
	//     if (nextButton) {
	//         nextButton.style.right = '8px';
	//     }
	// }
	const psSwiper = new Swiper(swiperContainer, swipeOption);
	window.swellPsSwiper = psSwiper;
}
