/**
 * beforeEnter での処理
 */
export function resetMeta({ newHead }) {
	if (!newHead) return;
	const head = document.head;
	if (!head) return;

	const removeHeadTags = [
		"meta[name='keywords']",
		"meta[name='description']",
		"meta[property^='fb']",
		"meta[property^='og']",
		"meta[name^='twitter']",
		"meta[name='robots']",
		'meta[itemprop]',
		'link[itemprop]',
		"link[rel='prev']",
		"link[rel='next']",
		"link[rel='canonical']",
	].join(',');
	const headTags = [...head.querySelectorAll(removeHeadTags)];

	headTags.forEach((item) => {
		head.removeChild(item);
	});
	const newHeadTags = [...newHead.querySelectorAll(removeHeadTags)];
	newHeadTags.forEach((item) => {
		head.appendChild(item);
	});
}

// head内SWELLスタイルをリセット
export function resetSwellStyle({ newHead }) {
	if (!newHead) return;
	const newSwellStyle = newHead.querySelector('#swell_custom-inline-css');
	const oldSwellStyle = document.querySelector('#swell_custom-inline-css');
	const newSwellStyleCSS = newSwellStyle ? newSwellStyle.textContent : '';
	if (oldSwellStyle) oldSwellStyle.textContent = newSwellStyleCSS;

	const newCustomStyle = newHead.querySelector('#swell_custom_css');
	const oldCustomStyle = document.querySelector('#swell_custom_css');
	const newCustomStyleCSS = newCustomStyle ? newCustomStyle.textContent : '';
	if (oldCustomStyle) oldCustomStyle.textContent = newCustomStyleCSS;
}

// アニメーションCSSを上書き
export function resetAnimationCSS() {
	let animationStyleTag = document.querySelector('.swell-style-animation');
	if (animationStyleTag) return; //すでにあれば return

	// なければ生成
	animationStyleTag = document.createElement('style');

	const cssRule = document.createTextNode(
		'#content,#main_visual,.p-mvInfo,.l-topTitleArea,.l-topTitleArea__body{animation-delay: 0s;}'
	);
	animationStyleTag.media = 'screen';
	animationStyleTag.type = 'text/css';
	animationStyleTag.classList.add('swell-style-animation');
	if (animationStyleTag.styleSheet) {
		animationStyleTag.styleSheet.cssText = cssRule.nodeValue;
	} else {
		animationStyleTag.appendChild(cssRule);
	}
	document.getElementsByTagName('head')[0].appendChild(animationStyleTag);
}

export function resetBodyClass({ newBody }) {
	if (!newBody) return;
	// bodyのクラスを差し替える
	const newBodyWrap = newBody.querySelector('#body_wrap');
	const oldBodyWrap = document.querySelector('#body_wrap');

	if (!newBodyWrap || !oldBodyWrap) return;
	oldBodyWrap.setAttribute('class', newBodyWrap.getAttribute('class'));
}

export function resetHeader({ newBody }) {
	if (!newBody) return;
	// ヘッダーのクラスを差し替える
	const newHeader = newBody.querySelector('#header');
	const oldHeader = document.querySelector('#header');

	if (!newHeader || !oldHeader) return;
	oldHeader.setAttribute('class', newHeader.getAttribute('class'));

	// ロゴ画像部分を切り替える
	const newLogo = newHeader.querySelector('.c-headLogo__link');
	const oldLogo = oldHeader.querySelector('.c-headLogo__link');

	if (!newLogo || !oldLogo) return;
	oldLogo.innerHTML = newLogo.innerHTML;

	// スマホメニューのリンクを踏んだ時のため、遷移時は強制的にメニューを閉じる
	document.documentElement.setAttribute('data-spmenu', 'closed');
}

// アドミンバーリセット
export function resetAdminBar({ newBody }) {
	if (!newBody) return;
	// adminbarを差し替える
	const newAdminBar = newBody.querySelector('#wpadminbar');
	const oldAdminBar = document.querySelector('#wpadminbar');

	if (!newAdminBar || !oldAdminBar) return;
	oldAdminBar.innerHTML = newAdminBar.innerHTML;

	// ホバーイベントを再度登録
	const adminBarLi = document.querySelectorAll('#wp-admin-bar-root-default > li');
	if (0 < adminBarLi.length) {
		adminBarLi.forEach(function (li) {
			li.addEventListener(
				'mouseover',
				function () {
					li.classList.add('hover');
				},
				false
			);
			li.addEventListener(
				'mouseout',
				function () {
					li.classList.remove('hover');
				},
				false
			);
		});
	}
}
