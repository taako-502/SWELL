/**
 * Barba
 */
import barba from '@barba/core';
import barbaPrefetch from '@barba/prefetch';

/**
 * init()
 */
export default function barbaInit() {
	// tell Barba to use the prefetch module
	barba.use(barbaPrefetch);

	let newHead = null;
	let newBody = null;

	// pjaxさせないページを配列で保持しておく(PHPからの受け取り)
	let pjaxPreventPagesList = window.swellVars.pjaxPreventPages;
	if (pjaxPreventPagesList) {
		pjaxPreventPagesList = pjaxPreventPagesList.split(',');
		// 各要素に trim()
		pjaxPreventPagesList = pjaxPreventPagesList.map(function (
			value,
			index,
			array
		) {
			return value.trim();
		});
	}

	// barba init
	barba.init({
		debug: true,
		timeout: 3000,

		// requestError: (trigger, action, url, response) => {
		//     console.log(response);
		// },

		prevent: ({ el, event, href }) => {
			/* ここで true を返すと普通に遷移する */

			// 管理画面へのリンク or 同じページへのリンク or LPページはpjax処理させない
			if (
				-1 !== href.indexOf('/wp-admin/') ||
				-1 !== href.indexOf('/wp-login.php') ||
				-1 !== href.indexOf('/lp/') ||
				href === location.href
			)
				return true;

			// 無視リストにある文字列が現在のURLに含まれていればpjaxさせない
			if (pjaxPreventPagesList) {
				for (let i = 0; i < pjaxPreventPagesList.length; i++) {
					const ignoreKey = pjaxPreventPagesList[i];
					if (ignoreKey) {
						if (
							-1 !== ignoreKey.indexOf('://') &&
							ignoreKey === href
						)
							return true; //「://」が含まれていれば完全一致かどうか
						if (-1 !== href.indexOf(ignoreKey)) return true; // それ以外は部分一致
					}
				}
			}
		},
		transitions: [
			{
				// sync: true,

				// once系は初めに一度だけ処理される。
				// beforeOnce() {},
				// once() {},
				// afterOnce() {},
				// before({ next }) {},
				// beforeLeave({ current, next, trigger }) {},
				// leave({ current, next, trigger }) {},

				afterLeave({ current, next, trigger }) {
					// next.htmlが取得できるのが afterLeave() からっぽい

					// 新ページのHTML
					const newHTML = next.html;

					// headを取得
					const newHeadInner = newHTML.match(
						/<head[^>]*>([\s\S]*)<\/head>/i
					)[1]; //参考記事は[0]だった
					newHead = document.createElement('head');
					newHead.innerHTML = newHeadInner;

					// body部分を取得
					const newBodyInner = newHTML.match(
						/<body[^>]*>([\s\S]*)<\/body>/i
					)[1]; //参考記事は[0]だった
					newBody = document.createElement('body');
					newBody.innerHTML = newBodyInner;

					// [data-barba="wrapper"]が存在しなければエラーになるので、destroy してリダイレクトさせる。
					if (!newBody.querySelector('[data-barba="wrapper"]')) {
						barba.destroy();
						// console.log(window.history);
						// window.history.pushState(null, null, location.href);
						// window.history.go(0);
						// let redirectUrl = next.url.href;
						location.href = next.url.href; // ** 問題点 : 「戻る」が効かなくなる。
					}
				},

				beforeEnter({ current, next }) {
					/* ここでのdocument は遷移前からずっと残っているもの。*/

					// スクロール位置をトップへ。
					if (!next.url.hash) window.scrollTo(0, 0);

					// 追加処理
					const swellHooks = window.SWELLHOOK.barbaBeforeEnter || {};
					Array.from(swellHooks).forEach((cb) => {
						// console.log(cb);
						if (cb && 'function' === typeof cb)
							cb({ newHead, newBody }); //cbが関数なら実行
					});
				},

				enter() {}, //enterがないと他の Transition関数も動かない

				// afterEnter() {},
				after({ current, next, trigger }) {
					/* documentが更新されているタイミングは、この after() から */

					const swellHooks = window.SWELLHOOK.barbaAfter || {};
					Array.from(swellHooks).forEach((cb) => {
						// console.log(cb);
						if (cb && 'function' === typeof cb)
							cb({ newHead, newBody, next }); //cbが関数なら実行
					});
				},
			},
		],
	});
}
