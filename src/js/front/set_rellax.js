import Rellax from 'rellax';
/* eslint no-undef: 0 */
// console.log('SWELL: setParallax');

/**
 * パララックスへの処理
 */
function setParallax() {
	const hasParallaxBlocks = document.querySelectorAll('.-parallax');
	if (0 === hasParallaxBlocks.length) return;
	for (let i = 0; i < hasParallaxBlocks.length; i++) {
		const block = hasParallaxBlocks[i];
		const bgImg = block.getAttribute('data-bg');

		if (!bgImg) return;

		block.removeAttribute('data-bg');
		block.style.backgroundImage = '';

		const parallaxLayer = document.createElement('div');
		parallaxLayer.classList.add('__parallaxLayer');
		parallaxLayer.setAttribute('role', 'presentation');
		parallaxLayer.style.backgroundImage = `url(${bgImg})`;

		block.appendChild(parallaxLayer);
	}

	// ほんのちょっと送らせて発火
	setTimeout(() => {
		new Rellax('.__parallaxLayer', {
			speed: -3,
			center: true,
			// round: true,
			// vertical: true,
			// horizontal: false,
		});
	}, 100);
}

// 実行
setParallax();

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(setParallax);
}
