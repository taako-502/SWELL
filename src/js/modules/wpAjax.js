import DOM from './data/domData';
import addClickEvents from './addClickEvents';
import { addSmoothScrollEvent } from './smoothScroll';

// export const addAjaxEvents = () => {};

/**
 * コンテンツ遅延読み込みのajax処理を実行
 */
const doFetch = async (params, doneFunc, failFunc) => {
	// ajaxUrl を正常に取得できるか
	if (window.swellVars === undefined) return;
	const ajaxUrl = window.swellVars.ajaxUrl;
	if (ajaxUrl === undefined) return;

	// nonce を正常に取得できるか
	const ajaxNonce = window.swellVars.ajaxNonce;
	if (ajaxNonce === undefined) return;

	params.append('nonce', ajaxNonce);

	await fetch(ajaxUrl, {
		method: 'POST',
		cache: 'no-cache',
		body: params,
	})
		.then((response) => {
			// console.log などで一回 response.json() 確認で使うと、responseJSONでbodyがlockされるので注意
			if (response.ok) {
				// console.log(response);
				return response.json();
			}
			throw new TypeError('Failed ajax!');
		})
		.then((json) => {
			doneFunc(json);
		})
		.catch((error) => {
			failFunc(error);
		});
};

/**
 * カテゴリードロップダウン関係
 */
let catDropdownNum = 0;
let archiveDropdownNum = 0;

const removeDropdownScript = (dom) => {
	// カテゴリードロップダウンメニューがあれば
	const catDropdowns = dom.querySelectorAll('select[name="cat"]');
	if (0 < catDropdowns.length) {
		catDropdowns.forEach((dropdown) => {
			// script削除
			(function () {
				const parentForm = dropdown.parentNode;
				if (null === parentForm) return;
				const scriptTag = parentForm.nextElementSibling;
				if (null === scriptTag) return;
				parentForm.parentNode.removeChild(scriptTag);
			})();
		});
	}

	//アーカイブ
	const archiveDropdowns = dom.querySelectorAll('select[name="archive-dropdown"]');
	if (0 < archiveDropdowns.length) {
		archiveDropdowns.forEach((dropdown) => {
			// script削除
			(function () {
				const parentForm = dropdown.parentNode;
				if (null === parentForm) return;
				const scriptTag = parentForm.nextElementSibling;
				if (null === scriptTag) return;
				parentForm.parentNode.removeChild(scriptTag);
			})();
		});
	}
};

const addDropdownEvent = (dom) => {
	// カテゴリードロップダウンメニューがあれば
	const catDropdowns = dom.querySelectorAll('select[name="cat"]');
	if (0 < catDropdowns.length) {
		catDropdowns.forEach((dropdown) => {
			const dropdownId = 'ajaxed-cat-dropdown-' + catDropdownNum++;

			dropdown.setAttribute('id', dropdownId); // idを再定義;

			// カテゴリーのドロップダウンメニューscriptを再定義
			dropdown.addEventListener('change', (e) => {
				if (0 < dropdown.options[dropdown.selectedIndex].value) {
					// dropdown.parentNode = <form>
					dropdown.parentNode.submit();
				}
			});
		});
	}

	// アーカイブ
	const archiveDropdowns = dom.querySelectorAll('select[name="archive-dropdown"]');
	if (0 < archiveDropdowns.length) {
		archiveDropdowns.forEach((dropdown) => {
			const dropdownId = 'ajaxed-archive-dropdown-' + archiveDropdownNum++;

			dropdown.setAttribute('id', dropdownId); // idを再定義;

			// カテゴリーのドロップダウンメニューscriptを再定義
			dropdown.addEventListener('change', (e) => {
				if (dropdown.options[dropdown.selectedIndex].value !== '') {
					document.location.href = dropdown.options[dropdown.selectedIndex].value;
				}
			});
		});
	}
};

/**
 * ajax処理の中身を定義する関数
 */
const setFetchFunctions = (params, actionName, selectorDOM) => {
	const doneFunc = (response) => {
		const fragment = document.createDocumentFragment();

		// 文字列をDOM化
		const responseDOM = document.createElement('div');
		responseDOM.innerHTML = response;
		// responseDOM.insertAdjacentHTML('afterbegin', response);

		while (responseDOM.firstChild) {
			fragment.appendChild(responseDOM.firstChild);
		}

		// カテゴリードロップダウンメニューのscriptを削除しておく
		removeDropdownScript(fragment);

		// <script>を実行できるようにする。 : https://stackoverflow.com/questions/41582850/javascript-dom-parser-load-ajax-requests-scripts-no-run
		[].map.call(fragment.querySelectorAll('script'), function (script) {
			const scriptParent = script.parentElement || fragment;
			const newScript = document.createElement('script');
			if (script.src) {
				newScript.src = script.src;
			} else {
				newScript.textContent = script.textContent;
			}
			scriptParent.replaceChild(newScript, script);
		});

		// 一旦空にしてから追加
		selectorDOM.innerHTML = '';
		selectorDOM.appendChild(fragment);

		// カテゴリーウィジェットのスクリプトを再登録
		addDropdownEvent(selectorDOM);

		//クリックイベントの登録
		addClickEvents(selectorDOM);

		// スムーススクロールイベントの登録
		addSmoothScrollEvent(selectorDOM);
	};

	const failFunc = (err) => {
		console.error(`Ajax failed : ${actionName}`);
	};

	doFetch(params, doneFunc, failFunc);
};

/**
 * コンテンツ遅延読み込み処理
 */
const lazyLoadContent = (actionName, selector, addParam) => {
	const selectorDOM = document.getElementById(selector);
	if (!selectorDOM) return;

	const params = new URLSearchParams();
	params.append('action', actionName);

	// paramsにパラメータを追加
	if ('post_id' === addParam) {
		if (null === DOM.content) {
			console.error('Not found "#content".');
			return;
		}
		const postID = DOM.content.getAttribute('data-postid');
		if (!postID) {
			console.error('Not found "data-postid".');
			return;
		}
		params.append('post_id', postID);
	}

	// ajax処理
	setFetchFunctions(params, actionName, selectorDOM);
};

/**
 * PVカウント処理
 */
const pvCount = () => {
	const postID = window.swellVars.postID || 0;
	if (!postID) return;

	const params = new URLSearchParams();
	params.append('action', 'swell_pv_count');
	params.append('post_id', postID);

	const doneFunc = () => {};
	const failFunc = (json) => {
		console.error(json);
	};
	doFetch(params, doneFunc, failFunc);
};

/**
 * コンテンツの遅延読み込み
 */
export const ajaxToLoadContents = () => {
	if (window.swellVars.isAjaxAfterPost) {
		lazyLoadContent('swell_load_after_article', 'after_article', 'post_id');
	}
	if (window.swellVars.isAjaxFooter) {
		lazyLoadContent('swell_load_foot_before', 'before_footer_widget', '');
		lazyLoadContent('swell_load_footer', 'footer', '');
	}

	// PVカウント
	if (window.swellVars.isCountPV) {
		pvCount();
	}
};
