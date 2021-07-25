(function ($) {
	function countTitle(titleSelector, titleArea) {
		const ttlVal = titleArea.val();
		titleSelector.attr('data-txtct', ttlVal.length);
	}

	function countTitleMain() {
		let titleSelector = $('.editor-post-title__block');
		let titleArea;
		if (0 < titleSelector.length) {
			// ブロックエディターの場合
			titleArea = titleSelector.find('textarea');
		} else {
			// クラシックエディターの場合
			titleSelector = $('#titlewrap');
			titleArea = titleSelector.find('input');
		}

		// console.log(titleSelector, titleArea);

		// それでも titleなかった場合 return
		if (0 === titleSelector.length) {
			return;
		}

		//ページ表示時に数える
		countTitle(titleSelector, titleArea);

		//入力フォーム変更時に数える
		titleArea.bind('keydown keyup keypress change', function () {
			countTitle(titleSelector, titleArea);
		});
	}

	window.addEventListener('load', function () {
		// タイトルカウント実行 : wp56からDOM取得がすぐにはできないので、少し遅らせる
		setTimeout(() => {
			countTitleMain();
		}, 50);
	});
})(window.jQuery);
