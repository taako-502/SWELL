// import DOM from './data/domData';

window.isSwlAdCtConnecting = false;

const observerOptions = {
	root: null,
	rootMargin: '-20% 0px',
	threshold: 0,
};

/**
 * 広告タグのクリックを計測
 */
export default function () {
	// IntersectionObserverをブラウザがサポートしているかどうか
	const isObserveSupported =
		window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;
	if (!isObserveSupported) return;

	// fetch使えるか
	if (!window.fetch) return;

	// ajaxUrl正常に取得できるか
	if (window.swellVars === undefined) return;
	const ajaxUrl = window.swellVars.ajaxUrl;
	if (ajaxUrl === undefined) return;

	// nonce
	const ajaxNonce = window.swellVars.ajaxNonce;
	if (ajaxNonce === undefined) return;

	// 広告タグ機能の計測
	adBoxCount();

	// ボタンの計測
	buttonCount();
}

/**
 * REST APIの呼び出し
 */
const callRestApi = async (route, params) => {
	// REST APIエンドポイントを正常に取得できるか
	if (window.swellVars === undefined) return;
	const restUrl = window.swellVars.restUrl;
	if (restUrl === undefined) return;

	fetch(restUrl + route, {
		method: 'POST',
		body: params,
	}).then((response) => {
		console.log(response.json());

		// if (response.ok) {
		// 	// console.log などで一回 response.json() 確認で使うと、responseJSONでbodyがlockされるので注意
		// 	return response.json();
		// }
		// throw new TypeError('Failed ajax!');
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
				// console.log('view!', buttonID);

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
		// console.log('buttonIDs', buttonIDs);
		ctButtonData(buttonIDs, 'pv');
	}
};

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
					countAdBoxImp(adID);
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
					countAdClicked(adID, 'tag');
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
					countAdClicked(adID, 'tag');
					adImg.onclick = () => true;
					adImg.click();
				};
			}
			const adBtn1 = adBox.querySelector('.-btn1');
			if (adBtn1) {
				adBtn1.onclick = function (e) {
					e.preventDefault();
					countAdClicked(adID, 'btn1');
					adBtn1.onclick = () => true;
					adBtn1.click();
				};
			}
			const adBtn2 = adBox.querySelector('.-btn2');
			if (adBtn2) {
				adBtn2.onclick = function (e) {
					e.preventDefault();
					countAdClicked(adID, 'btn2');
					adBtn2.onclick = () => true;
					adBtn2.click();
				};
			}
		}
	});

	// そのページ自体の表示回数の計測
	if (adIDs.length > 0) {
		// console.log(adIDs);
		countPageView(adIDs.join(','));
	}
};

const countAdClicked = async (adID, target) => {
	// 受け渡すデータ
	const route = 'swell-ct-ad-data';
	const params = new URLSearchParams();
	params.append('adid', adID); //広告ID
	params.append('ct_name', 'click');
	params.append('target', target); // 何をクリックしたか

	// REST API呼び出し
	callRestApi(route, params);
};

// PVカウント
const countPageView = async (adIDs) => {
	// 受け渡すデータ
	const route = 'swell-ct-ad-data';
	const params = new URLSearchParams();
	params.append('adid', adIDs);
	params.append('ct_name', 'pv');

	// REST API呼び出し
	callRestApi(route, params);
};

// IMPカウント
const countAdBoxImp = async (adID) => {
	// 受け渡すデータ
	const route = 'swell-ct-ad-data';
	const params = new URLSearchParams();
	params.append('adid', adID);
	params.append('ct_name', 'imp');

	// REST API呼び出し
	callRestApi(route, params);
};
