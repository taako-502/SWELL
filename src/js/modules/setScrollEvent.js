/**
 * スクロールイベントを登録
 *
 * @param {*} bodyWrap スクロールしたことを伝えるhtml要素
 */
export default function setScrollEvent() {
	// IntersectionObserverをブラウザがサポートしているかどうか
	const isObserveSupported =
		window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;
	if (!isObserveSupported) return;

	const observerOptions = {
		root: null,
		rootMargin: '0px',
		threshold: 0,
	};
	const scrollObserver = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			toggleScrollClass(!entry.isIntersecting);
			// console.log('intersectionRatio: ', entry.intersectionRatio);
		});
	}, observerOptions);

	const theObserver = document.querySelector('.l-scrollObserver');
	scrollObserver.observe(theObserver);
}

function toggleScrollClass(isScrolled) {
	if (isScrolled) {
		document.documentElement.setAttribute('data-scrolled', 'true');
	} else {
		document.documentElement.setAttribute('data-scrolled', 'false');
	}
}
