/* eslint no-undef: 0 */

// console.log('SWELL: set_mv.js');

const isPC = 959 < window.innerWidth ? true : false;
const isMobile = 600 > window.innerWidth ? true : false;

/**
 * メインスライダー
 */
mvSet();

// 画面回転時にも発火させる
window.addEventListener('orientationchange', () => {
	setTimeout(() => {
		if (window.swellMvSwiper) {
			window.swellMvSwiper.destroy();
			mvSet();
		}
	}, 10);
});

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(mvSet);
}

/**
 * メインビジュアルをセットする関数
 */
function mvSet() {
	const mainVisual = document.getElementById('main_visual');
	if (null === mainVisual) return;

	const mvInner = mainVisual.querySelector('.p-mainVisual__inner');

	// フルスクリーンの時の高さ調整
	if (mainVisual.classList.contains('-height-full')) {
		setFullScreenHeight(mainVisual);
	}

	if (mainVisual.classList.contains('-type-slider')) {
		// 画像スライダーのときの処理
		setMvSlider(mainVisual, mvInner);
	} else if (mainVisual.classList.contains('-type-movie')) {
		// 動画埋め込み時の処理...
		setMvVideo(mainVisual);
	}
}

/**
 * フルスクリーン時の高さセット : スライダー & 動画であり得る
 */
function setFullScreenHeight(mainVisual) {
	const header = document.getElementById('header');

	//offsetHを計算：フルワイド幅からどれだけ引くか
	if (header.classList.contains('-transparent')) return;

	let offsetH = 0;

	//お知らせバーの高さを取得
	const infoBar = document.querySelector('.c-infoBar');
	if (infoBar) {
		offsetH += infoBar.offsetHeight;
	}

	mainVisual.style.setProperty('--swl-mv-offsetH', `${offsetH}px`);
}

/**
 * スライダーセット
 */
function setMvSlider(mainVisual, mvInner) {
	const swellVars = window.swellVars;
	if (swellVars === undefined) return;

	const swiperOpt = {
		loop: true,
		preloadImages: false,
		lazy: {
			loadPrevNext: true,
		},
		autoplay: {
			delay: parseInt(swellVars.mvSlideDelay) || 10000,
			disableOnInteraction: false,
		},
		speed: parseInt(swellVars.mvSlideSpeed) || 1200,
		pagination: {
			el: '.p-mainVisual .swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '.p-mainVisual .swiper-button-next',
			prevEl: '.p-mainVisual .swiper-button-prev',
		},
		runCallbacksOnInit: true,
		on: {
			init() {
				setTimeout(() => {
					mvInner.classList.add('show_');
				}, 10);
			},
		},
	};

	// swiperオプション上書き
	let slidesPV = isMobile ? swellVars.mvSlideNumSp : swellVars.mvSlideNum;
	slidesPV = parseFloat(slidesPV);

	//スライド表示枚数による分岐
	if (1 < slidesPV) {
		swiperOpt.slidesPerView = slidesPV;
		swiperOpt.effect = 'slider';
		swiperOpt.spaceBetween = 8;
		swiperOpt.centeredSlides = true;
		swiperOpt.watchSlidesVisibility = true;
		if (!mainVisual.classList.contains('-margin-on')) {
			mvInner.style.paddingTop = '8px';
			mvInner.style.paddingBottom = '8px';
		}
	} else {
		const slideEffect = 'slide' === swellVars.mvSlideEffect ? 'slide' : 'fade';
		swiperOpt.effect = slideEffect;
	}

	const mvSwiper = new Swiper(mvInner, swiperOpt);
	window.swellMvSwiper = mvSwiper;
}

/**
 * 動画セット
 * videoタグは<source>のmediaが効かない。
 */
function setMvVideo(mainVisual) {
	const mvVideo = mainVisual.querySelector('.p-mainVisual__video');
	if (null === mvVideo) return;

	const media = isPC ? 'pc' : 'sp';
	const videoPoster = mvVideo.getAttribute(`data-poster-${media}`);
	if (videoPoster) mvVideo.setAttribute('poster', videoPoster);

	const videoSource = mvVideo.querySelector('source');
	const videoSrc = videoSource.getAttribute(`data-src-${media}`);
	videoSource.setAttribute('src', videoSrc);
	mvVideo.load();
	mvVideo.play();

	setTimeout(() => {
		if (mvVideo.paused) {
			mvVideo.play();
		}
	}, 1000);
}
