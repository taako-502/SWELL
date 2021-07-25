console.log('[SWELL] on Prefetch');

function setPrefetch() {
	const toPrefetch = new Set();
	const alreadyPrefetched = new Set();

	// Check browser support for native 'prefetch'
	const prefetcher = document.createElement('link');
	const isPrefetchSupported =
		prefetcher.relList &&
		prefetcher.relList.supports &&
		prefetcher.relList.supports('prefetch');

	// IntersectionObserverをブラウザがサポートしているかどうか
	const isObserveSupported =
		window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;

	// Promise をサポートしているかどうか
	const isPromiseSupported = 'undefined' !== typeof Promise; // !!window.Promise

	// prefetch, IntersectionObserver, Promise のいずれかをサポートしていないブラウザでは return
	if (!isPrefetchSupported || !isObserveSupported || !isPromiseSupported) return;

	// Checks if user is on slow connection or has enabled data saver
	const isSlowConnection =
		navigator.connection &&
		(navigator.connection.saveData ||
			(navigator.connection.effectiveType || '').includes('2g')); //slow-2g or 2g なら slow 判定

	// Don't start preloading if user is on a slow connection.
	if (isSlowConnection) return;

	// Prefetch the given url using native 'prefetch'. Fallback to 'xhr' if not supported
	// <link rel="prefetch" ...> をhead内に挿入するPromise処理
	const prefetch = (url) =>
		new Promise((resolve, reject) => {
			const link = document.createElement(`link`);
			link.rel = `prefetch`;
			link.href = url;
			link.onload = resolve;
			link.onerror = reject;
			document.head.appendChild(link);
		});

	// Prefetch pages with a timeout.
	// 5000msでタイムアウトさせるようにして prefetch 投げる。
	const prefetchWithTimeout = (url) => {
		const timer = setTimeout(() => stopPreloading(), 5000);
		prefetch(url)
			.catch(() => stopPreloading())
			.finally(() => clearTimeout(timer));
	};

	// URLをキューに追加
	const addUrlToQueue = (url, processImmediately = false) => {
		if (alreadyPrefetched.has(url) || toPrefetch.has(url)) return;

		// Prevent preloading 3rd party domains.
		// 外部サイトはプリロードしない。
		const origin = window.location.origin;
		if (url.substring(0, origin.length) !== origin) return;

		// Prevent current page from preloading
		// 現在のページはプリロードしない。
		if (window.location.href === url) return;

		// Ignore keywords in the array, if matched to the url
		// 無視リストと照らし合わせる。
		for (let i = 0; i < window.SwellFPConfig.ignoreKeywords.length; i++) {
			if (url.includes(window.SwellFPConfig.ignoreKeywords[i])) {
				// console.log('ignore:', url);
				return;
			}
		}

		// If max RPS is 0 or is on mouse hover, process immediately (without queue)
		if (processImmediately) {
			prefetchWithTimeout(url);
			alreadyPrefetched.add(url);
		} else {
			toPrefetch.add(url);
		}
	};

	// Observe the links in viewport, add url to queue if found intersecting
	// ビューポート内のリンクを addUrlToQueue させる。
	const linksObserver = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				const url = entry.target.href;
				addUrlToQueue(url, !window.SwellFPConfig.maxRPS);
			}
		});
	});

	// Queue that process requests based on max RPS (requests per second)
	// RPSの数値に合わせせて、toPrefetch を順番に処理していく。
	const startQueue = () =>
		setInterval(() => {
			Array.from(toPrefetch)
				.slice(0, window.SwellFPConfig.maxRPS)
				.forEach((url) => {
					prefetchWithTimeout(url);
					alreadyPrefetched.add(url); //処理が終わったら alreadyPrefetched へ追加。
					toPrefetch.delete(url); //さらに、 toPrefetch からは削除
				});
		}, 1000);

	let hoverTimer = null;

	// Add URL to queue on mouse hover, after timeout
	// マウスホバー時に addUrlToQueue。この時、第2引数の processImmediately は true で。
	const mouseOverListener = (event) => {
		const elm = event.target.closest('a');
		if (elm && elm.href && !alreadyPrefetched.has(elm.href)) {
			hoverTimer = setTimeout(() => {
				addUrlToQueue(elm.href, true);
			}, window.SwellFPConfig.hoverDelay);
		}
	};

	// Preload on touchstart on mobile
	// タッチスタートイベントでも。
	const touchStartListener = (event) => {
		const elm = event.target.closest('a');
		if (elm && elm.href && !alreadyPrefetched.has(elm.href)) addUrlToQueue(elm.href, true);
	};

	// Clear timeout on mouse out if not already preloaded
	const mouseOutListener = (event) => {
		const elm = event.target.closest('a');
		if (elm && elm.href && !alreadyPrefetched.has(elm.href)) {
			clearTimeout(hoverTimer);
		}
	};

	// requestIdleCallback のコールバック関数を定義。
	const requestIdleCallback =
		window.requestIdleCallback ||
		function (cb) {
			const start = Date.now();
			return setTimeout(function () {
				cb({
					didTimeout: false,
					timeRemaining() {
						return Math.max(0, 50 - (Date.now() - start));
					},
				});
			}, 1);
		};

	// Stop preloading in case server is responding slow/errors
	// サーバーからレスポンスが遅い時にプリロード中断させる。（prefetchWithTimeoutで投げられる処理）
	const stopPreloading = () => {
		// Find all links are remove it from observer (viewport)
		document.querySelectorAll('a').forEach((e) => linksObserver.unobserve(e));

		// Clear pending links in queue
		toPrefetch.clear();

		// Remove event listeners for mouse hover and mobile touch
		document.removeEventListener('mouseover', mouseOverListener, true);
		document.removeEventListener('mouseout', mouseOutListener, true);
		document.removeEventListener('touchstart', touchStartListener, true);
	};

	// Default options incase options is not set
	// SwellFPConfigがセットされなかった時のデフォルト値
	const defaultOptions = {
		delay: 0,
		ignoreKeywords: [],
		maxRPS: 3,
		hoverDelay: 50,
	};

	// Combine default options with received options to create the new config and set the config in window for easy access
	window.SwellFPConfig = Object.assign(defaultOptions, window.SwellFPConfig);

	// Start Queue
	// キューの登録を開始する
	startQueue();

	// Start preloading links in viewport on idle callback, with a delay
	// requestIdleCallback : ブラウザーがアイドル状態の時に実行される関数をキューに登録できる。
	requestIdleCallback(() =>
		setTimeout(
			() => document.querySelectorAll('a').forEach((e) => linksObserver.observe(e)),
			window.SwellFPConfig.delay * 1000
		)
	);

	// Add event listeners to detect mouse hover and mobile touch
	const listenerOptions = { capture: true, passive: true };
	document.addEventListener('mouseover', mouseOverListener, listenerOptions);
	document.addEventListener('mouseout', mouseOutListener, listenerOptions);
	document.addEventListener('touchstart', touchStartListener, listenerOptions);
}

// ignorePrefetchKeys を定義(PHPからの受け取り)
let ignorePrefetchKeys = window.swellVars.ignorePrefetchKeys || [];

if (ignorePrefetchKeys.length > 0) {
	// 各要素に trim()
	ignorePrefetchKeys = ignorePrefetchKeys.split(',').map(function (value, index, array) {
		return value.trim();
	});
}

ignorePrefetchKeys.push('/wp-admin/');
ignorePrefetchKeys.push('/wp-login.php');
window.SwellFPConfig = {
	ignoreKeywords: ignorePrefetchKeys,
};

setPrefetch();
