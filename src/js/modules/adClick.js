window.isSwlAdCtConnecting = false;

const observerOptions = {
	root: null,
	rootMargin: '-20% 0px',
	threshold: 0,
};

/**
 * 広告タグ、ボタンの計測
 */
export default function () {
	// IntersectionObserverをブラウザがサポートしているかどうか
	const isObserveSupported =
		window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;
	if (!isObserveSupported) return;

	// fetch使えるか
	if (!window.fetch) return;

	// restUrlを正常に取得できるか
	if (window.swellVars === undefined) return;
	const restUrl = window.swellVars.restUrl;
	if (restUrl === undefined) return;

	// 広告タグ機能の計測
	adBoxCount();

	// ボタンの計測
	buttonCount();
}

/**
 * REST APIの呼び出し
 */
const callRestApi = async (route, params) => {
	const restUrl = window.swellVars.restUrl;

	fetch(restUrl + route, {
		method: 'POST',
		body: params,
	}).then((response) => {
		if (response.ok) {
			// console.log などで一回 response.json() 確認で使うと、responseJSONでbodyがlockされるので注意
			return response.json();
		}
		throw new TypeError('Failed ajax!');
	});
};

/**
 * ボタンの計測
 */
const buttonCount = () => {
	const swlButtons = document.querySelectorAll('.swell-block-button[data-id]');
	if (0 === swlButtons.length) return;

	const buttonIDs = [];

	/**
	 * 表示回数の計測用 IntersectionObserver
	 */
	const buttonObserver = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				const button = entry.target;
				const buttonID = button.getAttribute('data-id');
				window.isSwlAdCtConnecting = true;
				setTimeout(() => {
					window.isSwlAdCtConnecting = false;
				}, 100);

				// 少しだけ遅らせてpvカウントや他の計測とのバッティングを回避
				const delayTime = window.isSwlAdCtConnecting ? 120 : 10;
				setTimeout(() => {
					ctButtonData(buttonID, 'imp');
				}, delayTime);

				//表示計測は一度だけでいいので、一回処理されれば削除
				buttonObserver.unobserve(button);
			}
		});
	}, observerOptions);

	// ボタン計測
	swlButtons.forEach((button) => {
		// ID / タイプを取得
		const buttonID = button.getAttribute('data-id');

		buttonIDs.push(buttonID);

		// ボタンが表示された回数の計測
		buttonObserver.observe(button);

		const buttonLink = button.querySelector('a');
		if (buttonLink) {
			buttonLink.onclick = function (e) {
				e.preventDefault();
				ctButtonData(buttonID, 'click');
				buttonLink.onclick = () => true;
				buttonLink.click();
			};
		}
	});

	// そのページ自体の表示回数の計測
	if (buttonIDs.length > 0) {
		ctButtonData(buttonIDs, 'pv');
	}
};

// ボタンイベントの処理
const ctButtonData = (buttonID, ctName) => {
	const postID = window.swellVars.postID || 0;
	if (!postID) return;

	// 受け渡すデータ
	const route = 'swell-ct-btn-data';
	const params = new URLSearchParams();
	params.append('btnid', buttonID);
	params.append('postid', postID);
	params.append('ct_name', ctName);

	// REST API呼び出し
	callRestApi(route, params);
};

// 広告イベントの処理
const ctAdData = (adData) => {
	const postID = window.swellVars.postID || 0;
	if (!postID) return;

	// 受け渡すデータ
	const route = 'swell-ct-ad-data';
	const params = new URLSearchParams();
	params.append('adid', adData.adID); //広告ID
	params.append('ct_name', adData.ctName);

	if (adData.target) {
		params.append('target', adData.target);
	}

	// REST API呼び出し
	callRestApi(route, params);
};

/**
 * 広告タグの計測
 */
const adBoxCount = () => {
	const adBoxs = document.querySelectorAll('.p-adBox');
	if (0 === adBoxs.length) return;

	const adIDs = [];

	// 表示回数の計測用 IntersectionObserver
	const adBoxObserver = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				const adBpx = entry.target;
				const adID = adBpx.getAttribute('data-id');

				window.isSwlAdCtConnecting = true;
				setTimeout(() => {
					window.isSwlAdCtConnecting = false;
				}, 100);

				// 少しだけ遅らせてpvカウントや他の計測とのバッティングを回避
				const delayTime = window.isSwlAdCtConnecting ? 120 : 10;
				setTimeout(() => {
					ctAdData({ adID, ctName: 'imp' });
				}, delayTime);

				//表示計測は一度だけでいいので、一回処理されれば削除
				adBoxObserver.unobserve(adBpx);
			}
		});
	}, observerOptions);

	// 広告タグ
	adBoxs.forEach((adBox) => {
		// ID / タイプを取得
		const adID = adBox.getAttribute('data-id');
		const adType = adBox.getAttribute('data-ad');

		adIDs.push(adID);

		// 表示回数の計測
		adBoxObserver.observe(adBox);

		if ('text' === adType) {
			const adLink = adBox.querySelector('a');
			if (adLink) {
				adLink.onclick = function (e) {
					e.preventDefault();
					ctAdData({ adID, ctName: 'click', target: 'tag' });
					adLink.onclick = () => true;
					adLink.click();
				};
			}
		} else {
			// クリック計測
			const adImg = adBox.querySelector('.p-adBox__img a');
			if (adImg) {
				adImg.onclick = function (e) {
					e.preventDefault();
					ctAdData({ adID, ctName: 'click', target: 'tag' });
					adImg.onclick = () => true;
					adImg.click();
				};
			}
			const adBtn1 = adBox.querySelector('.-btn1');
			if (adBtn1) {
				adBtn1.onclick = function (e) {
					e.preventDefault();
					ctAdData({ adID, ctName: 'click', target: 'btn1' });
					adBtn1.onclick = () => true;
					adBtn1.click();
				};
			}
			const adBtn2 = adBox.querySelector('.-btn2');
			if (adBtn2) {
				adBtn2.onclick = function (e) {
					e.preventDefault();
					ctAdData({ adID, ctName: 'click', target: 'btn2' });
					adBtn2.onclick = () => true;
					adBtn2.click();
				};
			}
		}
	});

	// そのページ自体の表示回数の計測
	if (adIDs.length > 0) {
		ctAdData({ adID: adIDs.join(','), ctName: 'pv' });
	}
};
