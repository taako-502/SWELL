import { postRestApi } from '@swell-js/helper/callRestApi';
/* eslint camelcase: 0 */
/* eslint no-console: 0 */

// console.log('count_CTR.js');

window.isSwlAdCtConnecting = false;

const observerOptions = {
	root: null,
	rootMargin: '-20% 0px',
	threshold: 0,
};

/**
 * 広告タグ、ボタンの計測
 */
function count_CTR() {
	// IntersectionObserverをブラウザがサポートしているかどうか
	const isObserveSupported =
		window.IntersectionObserver && 'isIntersecting' in IntersectionObserverEntry.prototype;
	if (!isObserveSupported) {
		console.error('IntersectionObserver is not supported.');
		return;
	}

	// fetch使えるか
	if (!window.fetch) {
		console.error('fetch() is not supported.');
		return;
	}

	// restUrlを正常に取得できるか
	const restUrl = window?.swellVars?.restUrl;
	if (restUrl === undefined) {
		console.error('restUrl not found.');
		return;
	}

	// postIDセット
	const content = document.querySelector('#content');
	if (null === content) {
		console.error('#content not found.');
		return;
	}
	const postID = content.getAttribute('data-postid');
	if (!postID) {
		console.log('data-postid not found.');
		return;
	}

	window.swellVars.postID = postID;

	// 広告タグ機能の計測
	adBoxCount();

	// ボタンの計測
	buttonCount();
}

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
			buttonLink.addEventListener('click', (e) => clickedButtonEvent(e, buttonID));
			buttonLink.addEventListener('mousedown', (e) => clickedButtonEvent(e, buttonID));
		}
	});

	// そのページ自体の表示回数の計測
	if (buttonIDs.length > 0) {
		ctButtonData(buttonIDs, 'pv');
	}
};

// ボタンイベントの処理
const ctButtonData = (buttonID, ctName) => {
	const postID = window?.swellVars?.postID;
	if (!postID) return;

	// 受け渡すデータ
	const params = { postid: postID, btnid: buttonID, ct_name: ctName };

	// fetch
	postRestApi('swell-ct-btn-data', params);
};

// 広告イベントの処理
const ctAdData = (adData) => {
	// 受け渡すデータ
	const params = { adid: adData.adID, ct_name: adData.ctName };
	if (adData.target) {
		params.target = adData.target;
	}

	// fetch
	postRestApi('swell-ct-ad-data', params);
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
				const adData = { adID, ctName: 'click', target: 'tag' };
				adLink.addEventListener('click', (e) => clickedAdEvent(e, adData));
				adLink.addEventListener('mousedown', (e) => clickedAdEvent(e, adData));
			}
		} else {
			// クリック計測
			const adImg = adBox.querySelector('.p-adBox__img a');
			if (adImg) {
				const adData = { adID, ctName: 'click', target: 'tag' };
				adImg.addEventListener('click', (e) => clickedAdEvent(e, adData));
				adImg.addEventListener('mousedown', (e) => clickedAdEvent(e, adData));
			}
			const adBtn1 = adBox.querySelector('.-btn1');
			if (adBtn1) {
				const adData = { adID, ctName: 'click', target: 'btn1' };
				adBtn1.addEventListener('click', (e) => clickedAdEvent(e, adData));
				adBtn1.addEventListener('mousedown', (e) => clickedAdEvent(e, adData));
			}
			const adBtn2 = adBox.querySelector('.-btn2');
			if (adBtn2) {
				const adData = { adID, ctName: 'click', target: 'adBtn2' };
				adBtn2.addEventListener('click', (e) => clickedAdEvent(e, adData));
				adBtn2.addEventListener('mousedown', (e) => clickedAdEvent(e, adData));
			}
		}
	});

	// そのページ自体の表示回数の計測
	if (adIDs.length > 0) {
		ctAdData({ adID: adIDs.join(','), ctName: 'pv' });
	}
};

const clickedAdEvent = (e, adData) => {
	// ホイールボタン以外での mousedown は無効化
	if ('mousedown' === e.type && 1 !== e.button) {
		return;
	}

	// クリックされたボックス
	const adBox = document.querySelector(`.p-adBox[data-id="${adData.adID}"]`);
	if (null === adBox) return;

	// 二重計測防止
	const clicked = adBox.getAttribute('data-clicked');
	if (clicked) return;

	adBox.setAttribute('data-clicked', '1');
	ctAdData(adData);
};

const clickedButtonEvent = (e, buttonID) => {
	// ホイールボタン以外での mousedown は無効化
	if ('mousedown' === e.type && 1 !== e.button) {
		return;
	}

	const button = e.currentTarget;

	// 二重計測防止
	const clicked = button.getAttribute('data-clicked');
	if (clicked) return;

	button.setAttribute('data-clicked', '1');

	ctButtonData(buttonID, 'click');
};

window.addEventListener('load', function () {
	// 広告タグのクリック計測(非ログイン時のみ)
	if (!window.swellVars.isLoggedIn) count_CTR();
});

// Pjax用
if (window.SWELLHOOK) {
	window.SWELLHOOK.barbaAfter.add(count_CTR);
}
