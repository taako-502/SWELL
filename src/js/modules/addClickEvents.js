import DOM from './data/domData';
import { smoothOffset } from './data/stateData';
import { smoothScroll } from './smoothScroll';

/**
 * クリックイベント処理をまとめたオブジェクト
 */
export const clickEvents = {
	pageTop() {
		const thisFunc = clickEvents.pageTop;
		const nowY = window.pageYOffset;
		window.scrollTo(0, Math.floor(nowY * 0.8));
		if (0 < nowY) {
			window.setTimeout(thisFunc, 10);
		}
	},
	toggleMenu() {
		const dataSpmenu = document.documentElement.getAttribute('data-spmenu');
		if ('opened' === dataSpmenu) {
			document.documentElement.setAttribute('data-spmenu', 'closed');
		} else {
			document.documentElement.setAttribute('data-spmenu', 'opened');
		}
	},
	toggleSearch() {
		const searchModal = DOM.searchModal;
		if (null === searchModal) return false;

		if (!searchModal.classList.contains('is-opened')) {
			searchModal.classList.add('is-opened');
			// 入力エリアにフォーカス
			setTimeout(() => {
				searchModal.querySelector('[name="s"]').focus();
			}, 250);
		} else {
			searchModal.classList.remove('is-opened');
		}
	},
	toggleIndex() {
		const indexModal = DOM.indexModal;
		if (null !== indexModal) indexModal.classList.toggle('is-opened');
	},

	/**
	 * アコーディオン
	 * 親のariaと兄弟要素のariaを制御。
	 */
	toggleAccordion(e) {
		const acTitle = e.currentTarget;
		const acWrap = acTitle.parentNode;
		const acBody = acTitle.nextElementSibling;
		const acIcon = acTitle.lastElementChild;
		const isExpanded = acWrap.getAttribute('aria-expanded');
		if ('false' === isExpanded) {
			acWrap.setAttribute('aria-expanded', 'true');
			acBody.setAttribute('aria-hidden', 'false');
			acIcon.setAttribute('data-opened', 'true');
		} else {
			acWrap.setAttribute('aria-expanded', 'false');
			acBody.setAttribute('aria-hidden', 'true');
			acIcon.setAttribute('data-opened', 'false');
		}
	},

	/**
	 * サブメニューのアコーディオン
	 */
	toggleSubmenu(e) {
		const btn = e.currentTarget;
		const submenu = btn.parentNode.nextElementSibling;

		// if ('set' !== submenu.getAttribute('data-height')) {
		//     submenu.classList.add('-setting');
		//     submenu.style.height = submenu.offsetHeight + 'px';
		//     submenu.classList.remove('-setting');
		//     submenu.setAttribute('data-height', 'set');
		// }

		btn.classList.toggle('is-opened');
		submenu.classList.toggle('is-opened');

		e.stopPropagation();
	},

	/**
	 * タブ
	 */
	tabControl(e) {
		// クリックされたボタン要素
		const clickedButton = e.currentTarget;
		const isOpend = 'true' === clickedButton.getAttribute('aria-selected');

		// クリックイベントがキー（Enter / space）によって呼び出されたかどうか
		const iskeyClick = 0 === e.clientX;

		if (!iskeyClick) {
			// マウスクリック時はフォーカスを外す
			clickedButton.blur();
		}
		// すでにオープンされているタブの場合はなにもしない
		if (isOpend) {
			return;
		}

		// 展開させるタブボックスを取得
		const targetID = clickedButton.getAttribute('aria-controls');
		const targetBody = document.getElementById(targetID);
		if (null === targetBody) return;

		// 展開させるボックス
		// const tabNav = clickedButton.parentNode.parentNode; //ul.c-tabLis

		// 親のタブリスト
		const parentTabList = clickedButton.closest('[role="tablist"]');

		// 現在選択済みのタブボタンを取得
		const selectedButton = parentTabList.querySelector(
			'.c-tabList__item [aria-selected="true"]'
		);

		// すでに展開済みのタブボックスを取得
		const openedBody = targetBody.parentNode.querySelector(
			'.c-tabBody__item[aria-hidden="false"]'
		);

		// ariaの処理
		clickedButton.setAttribute('aria-selected', 'true');
		selectedButton.setAttribute('aria-selected', 'false');
		targetBody.setAttribute('aria-hidden', 'false');
		openedBody.setAttribute('aria-hidden', 'true');
	},

	/**
	 * メインビジュアルのスクロールボタン
	 */
	scrollToContent(e) {
		const mvNext = document.querySelector('#main_visual + *');
		if (mvNext) {
			smoothScroll(mvNext, smoothOffset - 8, 16);
		}
	},
};

/**
 * data-onclick属性を持つ要素にクリックイベントを登録
 *
 * @param {*} dom 該当要素を検索する親（ AJAXで読み込んだ要素からも探せるように引数化 ）
 */
export default function addClickEvents(dom) {
	const elemsHasClickEvent = dom.querySelectorAll('[data-onclick]');
	for (let i = 0; i < elemsHasClickEvent.length; i++) {
		const elem = elemsHasClickEvent[i];
		if (elem) {
			const funcName = elem.getAttribute('data-onclick');
			const clickFunc = clickEvents[funcName];
			elem.addEventListener('click', function (e) {
				e.preventDefault();
				clickFunc(e);
			});
		}
	}
}
