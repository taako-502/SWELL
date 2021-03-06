/* eslint no-undef: 0 */
// import { Luminous, LuminousGallery } from 'luminous-lightbox';

// console.log('set_luminous.js');

const setDataLuminous = (img) => {
	// data-srcがあれば読み取る
	let src = img.getAttribute('data-src');
	if (!src) {
		// data-srcがなければ普通に src を取得
		src = img.getAttribute('src');
	}

	// 画像ソースなければ continue
	if (!src) return false;

	// フルサイズの画像パスを取得 luminousをセットする処理を開始
	const fullSizeSrc = src.replace(/-[0-9]*x[0-9]*\./, '.');

	img.setAttribute('data-luminous', fullSizeSrc);

	return true;
};

const setLuminousGallery = () => {
	const galleys = document.querySelectorAll('.post_content .wp-block-gallery:not(.u-lb-off)');

	// なければreturn
	if (1 > galleys.length) {
		return;
	}

	galleys.forEach((galley) => {
		const galleyImgs = galley.querySelectorAll('img');
		galleyImgs.forEach((img) => {
			if (setDataLuminous(img)) {
				img.classList.add('luminous');
			}
		});
		if (0 < galleyImgs.length) {
			new LuminousGallery(
				galleyImgs,
				{ arrowNavigation: true },
				{ sourceAttribute: 'data-luminous' }
			);
		}
	});
};

/**
 * Luminousをセット
 */
function setLuminous() {
	if (!window.LuminousGallery || !window.LuminousGallery) return;

	// ギャラリーブロックの画像は先にグループ化して処理
	setLuminousGallery();
	const isSetPostImg = '1' === window.swlLuminousVars?.postImg;

	let targetSelector = '.u-lb-on img, img.u-lb-on ';
	if (isSetPostImg) {
		targetSelector += ', .post_content .wp-block-image:not(.u-lb-off) img';
	}
	// 残った普通の画像
	const lbOnImgs = document.querySelectorAll(targetSelector);

	// 画像が一枚もなければreturn
	if (1 > lbOnImgs.length) return;

	// あとで設定できるようにする？
	// const isGroup = false;
	// const imglist = [];

	lbOnImgs.forEach((img) => {
		const imgParent = img.parentNode;

		// 親がaタグの場合はスキップ
		if ('A' === imgParent.tagName) return;

		const imgClassName = img.className;

		// 画像に すでに luminous クラスがついていればスキップ
		if (-1 !== imgClassName.indexOf('luminous')) return;

		// 画像に -no-lb がついていればスキップ
		if (-1 !== imgClassName.indexOf('-no-lb')) return;

		// 画像src取得できなければスキップ
		if (!setDataLuminous(img)) return;

		img.classList.add('luminous');

		// グループ化がオンの時、リストに保持
		// if (isGroup) {
		// 	imglist.push(img);
		// 	return;
		// }

		// Luminou発動
		new Luminous(img, {
			sourceAttribute: 'data-luminous',
		});
	});

	// if (isGroup && 0 < imglist.length) {
	// 	new LuminousGallery(
	// 		imglist,
	// 		{ arrowNavigation: true },
	// 		{ sourceAttribute: 'data-luminous' }
	// 	);
	// }
}

// 実行
setLuminous();

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(setLuminous);
}
