import DOM from './data/domData';

export default function setIndexList() {
	// swellVars 正常に取得できるかどうか
	if (window.swellVars === undefined) return;

	const bodyWrap = DOM.bodyWrap;
	if (null === bodyWrap) return;

	const mainContent = DOM.mainContent;
	if (null === mainContent) return;

	// ショートコードで挿入された目次があるかどうか
	const tocFromSc = mainContent.querySelector('.p-toc.-called-from-sc');
	if (null !== tocFromSc) {
		bodyWrap.classList.remove('-index-off');
	}

	// -index-off が確定していれば処理しない
	if (bodyWrap.classList.contains('-index-off')) return;

	const indexWraps = document.querySelectorAll('.p-toc');
	if (0 < indexWraps.length) {
		//記事本文が書かれているラッパー
		// const postContent = mainContent.querySelector(
		// 	'.post_content:not(.p-toc):not(.p-blogParts)'
		// );

		// 本文コンテンツだけを対象にする
		const postContent =
			mainContent.querySelector('.l-mainContent__inner > .post_content') ||
			mainContent.querySelector('.p-termContent > .post_content');

		const tocListTag = window.swellVars.tocListTag || 'ul'; //リスト ol か ulか
		const tocTarget = window.swellVars.tocTarget || 'h3';
		const tocMinnum = parseInt(window.swellVars.tocMinnum) || 1; //見出しが何個以上で生成するか。

		// 見出し取得
		let hTags = [];
		if (null !== postContent) {
			if ('h2' === tocTarget) {
				hTags = postContent.querySelectorAll('h2:not(.p-postList__title)'); //記事内の見出しタグを全て取得
			} else {
				hTags = postContent.querySelectorAll('h2:not(.p-postList__title), h3'); //記事内の見出しタグを全て取得
			}
		}

		// hTags = [...hTags, ..._htags];
		// console.log('hTags', hTags);

		if (0 < hTags.length && tocMinnum <= hTags.length) {
			/* 見出しタグがあれば */
			let indexList;
			if ('ol' === tocListTag) {
				indexList = document.createElement('ol');
			} else {
				indexList = document.createElement('ul');
			}

			indexList.classList.add('p-toc__list');
			indexList.classList.add('is-style-index');
			//indexList.classList.add('is-style-num_circle');

			let listSrc = ''; //目次ラッパーの中身となるhtmlソース
			let h3List = '';

			for (let i = 0; i < hTags.length; i++) {
				const theHeading = hTags[i];
				let theHeadingID = '';
				if (theHeading.hasAttribute('id')) {
					//すでにIDを持っている場合
					theHeadingID = theHeading.getAttribute('id'); //theHeading.idでもOK ?
				} else {
					theHeadingID = 'index_id' + i;
					theHeading.setAttribute('id', theHeadingID); //リンクで飛べるようにIDをつける
				}
				let theHeadingTextContent = theHeading.textContent; // テキストだけを取得
				let miniText = theHeading.querySelector('.mininote'); // "注釈" を取得

				// "注釈" があれば、それだけはHTMLを維持しておく。
				if (null !== miniText) {
					miniText = miniText.textContent;
					theHeadingTextContent = theHeadingTextContent.replace(
						miniText,
						`<span class="mininote">${miniText}</span>`
					);
				}
				const listAnchor =
					'<a href="#' +
					theHeadingID +
					'" class="p-toc__link">' +
					theHeadingTextContent +
					'</a>';

				if ('H2' === theHeading.tagName) {
					if ('' !== h3List) {
						//h3リストが生成されていれば
						if ('ol' === tocListTag) {
							listSrc += '<ol>' + h3List + '</ol>';
						} else {
							listSrc += '<ul>' + h3List + '</ul>';
						}

						h3List = '';
					}

					if (0 === i) {
						listSrc += '<li>' + listAnchor;
					} else {
						listSrc += '</li><li>' + listAnchor;
					}
				} else if ('H3' === theHeading.tagName) {
					h3List += '<li>' + listAnchor + '</li>';
				}
			} //end for

			if ('' !== h3List) {
				//最後のリストがh3だった場合
				if ('ol' === tocListTag) {
					listSrc += '<ol>' + h3List + '</ol></li>';
				} else {
					listSrc += '<ul>' + h3List + '</ul></li>';
				}
			} else {
				listSrc += '</li>';
			}

			indexList.innerHTML = listSrc;

			// 各p-tocに目次リストをセット
			for (let i = 0; i < indexWraps.length; i++) {
				const indexWrap = indexWraps[i];
				indexWrap.appendChild(indexList.cloneNode(true));

				//fullWideの中身であれば、外に出す。
				const tocParent = indexWrap.parentNode;
				if (tocParent.classList.contains('swell-block-fullWide__inner')) {
					const fullWide = tocParent.parentNode; //親: .swell-block-fullWide
					fullWide.parentNode.insertBefore(indexWrap, fullWide);

					// また、広告もある場合はそれも外に出す。
					const tocAd = fullWide.querySelector('.w-beforeToc');
					if (null !== tocAd) {
						indexWrap.parentNode.insertBefore(tocAd, indexWrap);
					}
				}
			}
		} else {
			//見出しがなければ
			DOM.bodyWrap.classList.add('-index-off');

			// fullWideの中身に残ることがあるので削除
			const tocInFullwide = mainContent.querySelector('.swell-block-fullWide .p-toc');
			if (tocInFullwide) {
				tocInFullwide.parentNode.removeChild(tocInFullwide);
			}
			const wInFullwide = mainContent.querySelector('.swell-block-fullWide .w-beforeToc');
			if (wInFullwide) {
				wInFullwide.parentNode.removeChild(wInFullwide);
			}

			// const tocAd = postContent.querySelector('.w-beforeToc');
			// if (null !== tocAd) tocAd.remove();
		}
	}
}
