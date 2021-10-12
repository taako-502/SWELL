/**
 * 「ふきだし」編集ページのスクリプト
 */
(function ($) {
	console.log('SWELL: Loaded balloon.js.');

	$(function () {
		// $('form').attr('autocomplete', 'off');

		const $swellBalloonMeta = $('.swl-meta--balloon');
		const $balloonBox = $swellBalloonMeta.find('.c-balloon');
		let $balloonBoxIcon = $balloonBox.find('.c-balloon__icon');
		const $balloonBoxBody = $balloonBox.find('.c-balloon__body');

		// 画像
		$('[name="balloon_icon"]').change(function () {
			const thisVal = $(this).val() || '';

			if (0 === $balloonBoxIcon.length) {
				const iconHtml =
					'<div class="c-balloon__icon -circle"><img src="" class="c-balloon__iconImg" width="80px" height="80px"><span class="c-balloon__iconName"></span></div>';
				$balloonBoxBody.before(iconHtml);

				// 取得し直す
				$balloonBoxIcon = $balloonBox.find('.c-balloon__icon');
			}

			const $iconImg = $balloonBox.find('.c-balloon__iconImg');

			if (thisVal) {
				$balloonBoxIcon.removeClass('u-none');
			} else {
				$balloonBoxIcon.addClass('u-none');
			}
			$iconImg.attr('src', thisVal);
		});

		// なまえ
		$('[name="balloon_icon_name"]').on('input', function () {
			const thisVal = $(this).val() || $('[name="post_title"]').val();
			$balloonBox.find('.c-balloon__iconName').text(thisVal);
		});

		// 枠
		$('[name="balloon_icon_shape"]').change(function () {
			const thisVal = $(this).val();
			$balloonBoxIcon.removeClass('-circle');
			$balloonBoxIcon.removeClass('-square');
			$balloonBoxIcon.addClass('-' + thisVal);
		});

		// 形
		$('[name="balloon_type"]').change(function () {
			const thisVal = $(this).val();
			$balloonBoxBody.removeClass('-speaking');
			$balloonBoxBody.removeClass('-thinking');
			$balloonBoxBody.addClass('-' + thisVal);
		});

		// 左右
		$('[name="balloon_align"]').change(function () {
			const thisVal = $(this).val();
			$balloonBox.removeClass('-bln-right');
			$balloonBox.removeClass('-bln-left');
			$balloonBox.addClass('-bln-' + thisVal);
		});

		// ボーダー
		$('[name="balloon_border"]').change(function () {
			const thisVal = $(this).val();
			$balloonBoxBody.removeClass('-border-none');
			$balloonBoxBody.removeClass('-border-on');
			$balloonBoxBody.addClass('-border-' + thisVal);
		});

		// 色
		$('[name="balloon_col"]').change(function () {
			const thisVal = $(this).val();
			$balloonBox.attr('data-col', thisVal);
		});
	});
})(window.jQuery);
