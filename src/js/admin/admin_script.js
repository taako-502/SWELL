import { postRestApi } from '@swell-js/helper/callRestApi';
/* eslint no-console: 0 */
/* eslint no-alert: 0 */
/* eslint no-unused-vars: 0 */

/**
 * 管理画面用スクリプト
 *   ※ 管理画面の全ページ & ログイン時はフロント側でも 読み込む。
 */
document.addEventListener('DOMContentLoaded', function () {
	//switchCheckbox の動作
	(function () {
		const switchBtns = document.querySelectorAll('.swl-switchCheckbox .__switchBtn');
		for (let i = 0; i < switchBtns.length; i++) {
			const theBtn = switchBtns[i];
			const targetId = theBtn.getAttribute('for');
			const tagetCheckbox = document.getElementById(targetId);
			const targetInput = document.querySelector('[name="' + targetId + '"]');

			if (null === tagetCheckbox) continue;
			if (null === targetInput) continue;
			theBtn.addEventListener('click', function (e) {
				setTimeout(() => {
					const val = tagetCheckbox.checked;
					targetInput.setAttribute('value', Number(val));
				}, 10);
			});
		}
	})();

	// パーツ用セレクトボックス
	(function () {
		const partsSelect = document.querySelectorAll('.swell_parts_select');
		for (let i = 0; i < partsSelect.length; i++) {
			const theSelect = partsSelect[i];
			const targetId = theSelect.getAttribute('data-for');
			const targetInput = document.getElementById(targetId);
			if (null === targetInput) continue;

			theSelect.addEventListener('change', function (e) {
				console.log(e.target.value);
				targetInput.setAttribute('value', e.target.value);
			});
		}
	})();
});

// キャッシュクリア処理
const callClearCache = function (action) {
	// nonceを正常に取得できるか
	const nonce = window?.wpApiSettings?.nonce;
	if (nonce === undefined) return;

	const params = {
		action,
		_wpnonce: nonce,
	};

	const doneFunc = (response) => {
		alert(response);
		location.reload();
	};
	const failFunc = () => {
		alert('キャッシュクリアに失敗しました。');
	};

	// ajax処理
	postRestApi('swell-reset-cache', params, doneFunc, failFunc);
};

/**
 * jQuery非依存処理
 */
document.addEventListener('DOMContentLoaded', function () {
	// キャッシュクリア（ツールバー）
	const clearCacheBtn = document.getElementById('wp-admin-bar-swell_settings__clear_cache');
	if (null !== clearCacheBtn) {
		clearCacheBtn.addEventListener('click', function (e) {
			e.preventDefault();
			callClearCache('cache');
		});
	}

	// キャッシュクリア（設定画面）
	const clearCacheBtn2 = document.getElementById('swell_settings_btn___clear_cache');
	if (null !== clearCacheBtn2) {
		clearCacheBtn2.addEventListener('click', function (e) {
			e.preventDefault();
			callClearCache('cache');
		});
	}

	// ブログカードのキャッシュクリア（ツールバー）
	const clearCardBtn = document.getElementById('wp-admin-bar-swell_settings__clear_card_cache');
	if (null !== clearCardBtn) {
		clearCardBtn.addEventListener('click', function (e) {
			e.preventDefault();
			callClearCache('card_cache');
		});
	}

	// ブログカードのキャッシュクリア（設定画面）
	const clearCardBtn2 = document.getElementById('swell_settings_btn___clear_card_cache');
	if (null !== clearCardBtn2) {
		clearCardBtn2.addEventListener('click', function (e) {
			e.preventDefault();
			callClearCache('card_cache');
		});
	}
});
