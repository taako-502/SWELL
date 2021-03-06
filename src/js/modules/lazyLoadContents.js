import DOM from './data/domData';
import addClickEvents from './addClickEvents';
import { addSmoothScrollEvent } from './smoothScroll';
import { postRestApi } from '@swell-js/helper/callRestApi';

/* eslint no-console: 0 */
/* eslint no-unused-vars: 0 */

// console.log('SWELL: lazyLoadContents.js');

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
				const scriptTag = dropdown.nextElementSibling;
				if (null === scriptTag) return;
				dropdown.parentNode.removeChild(scriptTag);
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
 * コンテンツ遅延読み込み処理
 */
const loadContent = (placement, addParam) => {
	const selectorDOM = document.getElementById(placement);
	if (!selectorDOM) return;

	const params = {
		placement,
	};

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
		params.post_id = postID;
	}

	/**
	 * fetch成功時の処理
	 */
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
	postRestApi('swell-lazyload-contents', params, doneFunc);
};

/**
 * コンテンツの遅延読み込み
 */
export const lazyLoadContents = () => {
	if (window.swellVars.isAjaxAfterPost) {
		loadContent('after_article', 'post_id');
	}
	if (window.swellVars.isAjaxFooter) {
		loadContent('before_footer_widget', '');
		loadContent('footer', '');
	}
};
