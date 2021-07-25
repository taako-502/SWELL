/* 記事コンテンツに対する処理をまとめたファイル */

// import
import { isPC, isSP, isTab, isMobile } from '@swell-js/modules/data/stateData';
// import DOM from '@swell-js/modules/data/domData';
// import wrap from '@swell-js/modules/wrap';

/**
 * デバイスサイズで変化するもの
 */
export function setBlockStyle() {
	let dataName = '';

	// tab / mobile で出し分けてるパターン
	if (isTab) {
		dataName = 'data-tab-style';
	} else if (isMobile) {
		dataName = 'data-mobile-style';
	}

	const blocksTabMobile = document.querySelectorAll(`[${dataName}]`);
	for (let i = 0; i < blocksTabMobile.length; i++) {
		const block = blocksTabMobile[i];
		const dataval = block.getAttribute(dataName);
		block.setAttribute('style', dataval);
	}

	// pc / sp で出し分けてるパターン
	if (isPC) {
		dataName = 'data-pc-style';
	} else {
		dataName = 'data-sp-style';
	}

	const blocksPCSP = document.querySelectorAll(`[${dataName}]`);
	for (let i = 0; i < blocksPCSP.length; i++) {
		const block = blocksPCSP[i];
		const dataval = block.getAttribute(dataName);
		block.setAttribute('style', dataval);
	}
}

/**
 * olのstart属性に対応する
 */
export function setOlStartNum() {
	const olWithStart = document.querySelectorAll('ol[start]');
	for (let i = 0; i < olWithStart.length; i++) {
		const ol = olWithStart[i];
		const startVal = ol.getAttribute('start');
		const isReversed = ol.getAttribute('reversed');
		if (null === isReversed) {
			ol.style.counterReset = `li ${parseInt(startVal) - 1}`;
		} else {
			ol.style.counterReset = `li ${parseInt(startVal) + 1}`;
		}
	}
}

/**
 * SP表示でスクロールさせる表への処理。（wp5.3以前用）
 */
export function setTableScroll() {
	// ヘッダー固定テーブルがある場合の処理
	const fixHeadTables = document.querySelectorAll('.wp-block-table[data-theadfix]');
	if (0 < fixHeadTables.length) {
		document.documentElement.setAttribute('data-has-fixtable', '1');
	}
}

/**
 * SWELLへのリンクを検知して noreferrer 削除する
 */
export function removeNoreferrer() {
	const swellLinks = document.querySelectorAll('a[href*="swell-theme.com"]');
	for (let i = 0; i < swellLinks.length; i++) {
		const link = swellLinks[i];
		let rel = link.rel;
		if (rel) {
			rel = rel.replace('noreferrer', '');
			rel = rel.trim();
			link.rel = rel;
		}
	}
}

/**
 * パララックスへの処理
 */
export function setParallax() {
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
			speed: -2,
			center: true,
			// round: true,
			// vertical: true,
			// horizontal: false,
		});
	}, 10);
}

/**
 * URLコピー
 */
export function setUrlCopy() {
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
