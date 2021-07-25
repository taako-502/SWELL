// import wrap from './wrap';

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

/**
 * Luminousをセット
 */
export default function setLuminous() {
	// ギャラリーブロックの画像は先にグループ化して処理
	const galleys = document.querySelectorAll(
		'.post_content .wp-block-gallery'
	);
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

	// 残った普通の画像
	const contentImgs = document.querySelectorAll(
		'.post_content img:not(.-no-lb)'
	);

	// 画像が一枚もなければreturn
	if (1 > contentImgs.length) {
		return;
	}
	const isGroup = false;
	const imglist = [];

	for (let i = 0; i < contentImgs.length; i++) {
		// 画像データ
		const img = contentImgs[i];
		const imgClassName = img.className;
		const imgParent = img.parentNode;

		// 親がaタグの場合や、親に特定のクラスが付いていれば continue
		// const ignoreRegex = /post_thumb_img/;
		// ignoreRegex.test(imgClassName)
		if ('A' === imgParent.tagName) {
			continue;
		}

		// 画像に -no-lb がついていれば continue
		// if (-1 !== imgClassName.indexOf('-no-lb')) {
		//     continue;
		// }

		// 画像に すでに luminous がついていれば continue
		if (-1 !== imgClassName.indexOf('luminous')) {
			continue;
		}

		if (setDataLuminous(img)) {
			img.classList.add('luminous');

			// Luminou発動
			if (!isGroup) {
				new Luminous(img, {
					sourceAttribute: 'data-luminous',
				});
			} else {
				// グループ化がオンの時、リストに保持
				imglist.push(img);
			}
		}
	}

	if (isGroup && 0 < imglist.length) {
		new LuminousGallery(
			imglist,
			{ arrowNavigation: true },
			{ sourceAttribute: 'data-luminous' }
		);
	}
}
