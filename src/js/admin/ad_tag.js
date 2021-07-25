(function ($) {
	console.log('Loaded ad_tag.js.');

	$(function () {
		// $('form').attr('autocomplete', 'off');

		const $swellAdMeta = $('.swl-meta--ad');
		const $adBox = $swellAdMeta.find('.p-adBox');

		// 広告タイプをラッパーに付与
		$('input[name="ad_type"]').change(function () {
			const thisVal = $(this).val();
			$swellAdMeta.attr('data-adtype', thisVal);
			$adBox.attr('data-ad', thisVal);
		});

		// 表示名
		$('[name="ad_name"]').on('input', function () {
			let thisVal = $(this).val();
			if (!thisVal) thisVal = $('[name="post_title"]').val();
			$adBox.find('.p-adBox__name').text(thisVal);
			$adBox.find('.p-adBox__title').text(thisVal);
		});

		// 価格
		$('[name="ad_price"]').on('input', function () {
			const thisVal = $(this).val();
			$adBox.find('.p-adBox__price').text(thisVal);
		});

		//説明文
		$('[name="ad_desc"]').on('input', function () {
			const thisVal = $(this).val();
			$adBox.find('.p-adBox__desc').text(thisVal);
		});

		// ボーダーのオン/オフ
		$('[name="ad_border"]').change(function () {
			const thisVal = $(this).val();
			if ('on' === thisVal) {
				$adBox.removeClass('-border-off');
				$adBox.addClass('-border-on');
			} else {
				$adBox.addClass('-border-off');
				$adBox.removeClass('-border-on');
			}
		});

		// ランキング
		$('[name="ad_rank"]').change(function () {
			const thisVal = $(this).val();
			$('.p-adBox__title').attr('class', 'p-adBox__title -' + thisVal);
		});

		// 評価スター ad_star
		// icon-star-full / -empty / -half
		$('[name="ad_star"]').change(function () {
			const thisVal = $(this).val();
			let starPt = parseFloat(thisVal) || 0; //星の数 float で
			if (0 === starPt) {
				$('.p-adBox__star').html('');
				return;
			}
			let returnStar = '';
			for (let i = 0; 5 > i; i++) {
				starPt = starPt - 1;
				if (-0.5 === starPt) {
					returnStar += '<i class="icon-star-half"></i>';
				} else if (0 <= starPt) {
					returnStar += '<i class="icon-star-full"></i>';
				} else {
					returnStar += '<i class="icon-star-empty"></i>';
				}
			}
			$('.p-adBox__star').html(returnStar);
		});

		// ボタン
		$('[name="ad_btn1_text"]').on('input', function () {
			const thisVal = $(this).val() || 'テキスト入力してね';
			$adBox.find('.-btn1').text(thisVal);
		});
		$('[name="ad_btn2_text"]').on('input', function () {
			const thisVal = $(this).val() || 'テキスト入力してね';
			$adBox.find('.-btn2').text(thisVal);
		});

		$('[name="ad_btn1_url"]').on('input', function () {
			const thisVal = $(this).val();
			if (!thisVal) {
				$adBox.find('.-btn1').addClass('u-none');
			} else {
				$adBox.find('.-btn1').removeClass('u-none');
			}
		});
		$('[name="ad_btn2_url"]').on('input', function () {
			const thisVal = $(this).val();
			if (!thisVal) {
				$adBox.find('.-btn2').addClass('u-none');
			} else {
				$adBox.find('.-btn2').removeClass('u-none');
			}
		});

		// リセットボタン
		$('.swl-adDataResetBtn').on('click', function () {
			if (window.confirm('本当にリセットしてもいいですか？')) {
				const $this = $(this);
				const adID = $this.attr('data-id');

				$.ajax({
					type: 'POST',
					url: window.swellVars.ajaxUrl,
					data: {
						action: 'swell_reset_ad_data',
						nonce: window.swellVars.ajaxNonce,
						id: adID,
					},
				})
					.done(function (returnData) {
						alert(returnData);
						location.reload();
					})
					.fail(function () {
						alert('リセットに失敗しました。');
					});
			}
		});
	});
})(window.jQuery);
