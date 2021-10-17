import { postRestApi } from '@swell-js/helper/callRestApi';
/* eslint no-alert: 0 */
/* eslint no-console: 0 */

/**
 * データリセットボタンの処理
 */
addEventListener('DOMContentLoaded', function () {
	// nonceを正常に取得できるか
	const nonce = window?.wpApiSettings?.nonce;
	if (nonce === undefined) return;

	const params = {
		_wpnonce: nonce,
	};

	// 古いデータを更新する
	const updateActionBtn = document.getElementById('swell_settings_btn___do_update_action');
	if (null !== updateActionBtn) {
		updateActionBtn.addEventListener('click', (e) => {
			e.preventDefault();

			const doneFunc = (response) => {
				alert(response);
				location.reload();
			};

			const failFunc = () => {
				alert('更新に失敗しました。');
			};

			postRestApi('swell-do-update-action', params, doneFunc, failFunc);
		});
	}

	const clickedFunc = (actionName) => {
		const doneFunc = (response) => {
			alert(response);
		};

		const failFunc = () => {
			alert('リセットに失敗しました。');
		};

		if (window.confirm('本当にリセットしてもいいですか？')) {
			params.action = actionName;
			postRestApi('swell-reset-settings', params, doneFunc, failFunc);
		}
	};

	// カスタマイザーのクリア（設定画面）
	const resetBtn = document.getElementById('swell_settings_btn___reset_settings');
	if (null !== resetBtn) {
		resetBtn.addEventListener('click', (e) => {
			e.preventDefault();
			clickedFunc('customizer');
		});
	}

	// PVリセット
	const pvResetBtn = document.getElementById('swell_settings_btn___reset_pv');
	if (null !== pvResetBtn) {
		pvResetBtn.addEventListener('click', (e) => {
			e.preventDefault();
			clickedFunc('pv');
		});
	}
});

/**
 * カラー変換
 * $brightness : -1 ~ 1
 */
function getRGBA(colorCode, alpha, brightness) {
	alpha = alpha || 1;
	brightness = brightness || 0;

	let red = parseInt(colorCode.substring(1, 3), 16);
	let green = parseInt(colorCode.substring(3, 5), 16);
	let blue = parseInt(colorCode.substring(5, 7), 16);

	if (0 !== brightness) {
		red = Math.round(red + red * brightness);
		red = Math.min(red, 255);
		green = Math.round(green + green * brightness);
		green = Math.min(green, 255);
		blue = Math.round(blue + blue * brightness);
		blue = Math.min(blue, 255);
	}
	return `rgba(${red},${green},${blue},${alpha})`;
}

/**
 * 設定値による動的な変化
 */
(function ($) {
	$(function () {
		/**
		 * ふきだしカラー
		 */
		(function () {
			const $balloonMenu = $('.swell-menu-balloon');
			const $bgField = $balloonMenu.find('.-bg');
			const $borderField = $balloonMenu.find('.-border');

			$bgField.change(function () {
				const $this = $(this);
				const newColor = $this.val();
				const $preview = $this.parents('.__settings').next();
				$preview.find('.c-balloon__text').css('background', newColor);
				$preview.find('.c-balloon__before').css('border-right-color', newColor);
			});

			$borderField.change(function () {
				const $this = $(this);
				const newColor = $this.val();
				const $preview = $this.parents('.__settings').next();
				$preview.find('.c-balloon__text').css('border-color', newColor);
			});
		})();

		/**
		 * キャプションボックス
		 */
		(function () {
			const $capboxMenu = $('.swell-menu-capbox');
			const $darkField = $capboxMenu.find('.__dark');
			const $lightField = $capboxMenu.find('.__light');

			$darkField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const $parent = $this.parents('.swell-menu-capbox');
				$parent.find('.cap_box_ttl').css('background', thisVal);
				$parent.find('.cap_box_content').css('border-color', thisVal);
			});
			$lightField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const $parent = $this.parents('.swell-menu-capbox');
				$parent.find('.cap_box_content').css('background', thisVal);
			});
		})();

		/**
		 * ボーダー設定
		 */
		function setNewBorderStyle($menu) {
			const style = $menu.find('.__style').val();
			const width = $menu.find('.__width').val();
			let color = $menu.find('.__color').val();
			const customColor = $menu.find('.__customColor').val();

			if ('custom' === color) color = customColor;

			const newVal = style + ' ' + width + 'px ' + color;

			const $previewBox = $menu.find('.__previwBox');

			$previewBox.css('border', newVal);
			// $previewBox.html(newVal);

			const $hiddenField = $menu.find('.__hidden');
			$hiddenField.val(newVal);
		}
		(function () {
			const $borderMenu = $('.swell-menu-border');
			const $styleField = $borderMenu.find('.__style');
			const $widthField = $borderMenu.find('.__width');
			const $colorField = $borderMenu.find('.__color');
			const $customColorField = $borderMenu.find('.__customColor');

			$styleField.change(function () {
				const $this = $(this);
				const $parent = $this.parents('.swell-menu-border');
				setNewBorderStyle($parent);
			});

			$widthField.change(function () {
				const $this = $(this);
				const $parent = $this.parents('.swell-menu-border');
				setNewBorderStyle($parent);
			});

			$colorField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const $parent = $this.parents('.swell-menu-border');

				const $customColorItem = $parent.find('.-customColor');
				// カスタムカラーを出し分ける
				if ('custom' === thisVal) {
					$customColorItem.removeClass('u-none');
				} else {
					$customColorItem.addClass('u-none');
				}

				setNewBorderStyle($parent);
			});

			$customColorField.change(function () {
				const $this = $(this);
				const $parent = $this.parents('.swell-menu-border');
				setNewBorderStyle($parent);
			});
		})();

		/**
		 * アイコンボックス - スタイル
		 */
		(function () {
			const $swellSettingsBody = $('.swell_settings__body');
			const $iconboxMenu = $('.swell-menu-iconbox');
			const $smallIconField = $iconboxMenu.find('.__icon_small_type');
			const $bigIconField = $iconboxMenu.find('.__icon_big_type');

			$smallIconField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				$swellSettingsBody.attr('data-type-small', thisVal);
			});
			$bigIconField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				$swellSettingsBody.attr('data-type-big', thisVal);
			});
		})();

		/**
		 * アイコンボックス - カラー
		 */
		(function () {
			const $iconboxMenu = $('.swell-menu-iconbox');
			const $iconColorField = $iconboxMenu.find('.__icon_color');

			$iconColorField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const thisKey = $this.attr('data-key');
				document.documentElement.style.setProperty('--' + thisKey, thisVal);
			});
		})();

		/**
		 * リストカラー
		 */
		(function () {
			const $listMenu = $('.swell-menu-list');
			const $listColorField = $listMenu.find('.__list_color');

			$listColorField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const thisKey = $this.attr('data-key');
				document.documentElement.style.setProperty('--' + thisKey, thisVal);
				if (!thisVal) {
					document.documentElement.style.removeProperty('--' + thisKey, thisVal);
				}
			});
		})();

		/**
		 * FAQカラー
		 */
		(function () {
			const $faqMenu = $('.swell-menu-faq');
			const $faqColorField = $faqMenu.find('.__faq_color');

			$faqColorField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const thisKey = $this.attr('data-key');
				document.documentElement.style.setProperty('--' + thisKey, thisVal);
			});
		})();

		/**
		 * ブログカード
		 */
		(function () {
			const $cardMenu = $('.swell-menu-blogcard');
			const $cardField = $cardMenu.find('.__blogcard');
			$cardField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const $parent = $this.parents('.swell-menu-blogcard');
				$parent.find('.p-blogCard').attr('data-type', thisVal);
			});
		})();

		/**
		 * 引用
		 */
		(function () {
			const $blockquoteMenu = $('.swell-menu-blockquote');
			const $blockquoteField = $blockquoteMenu.find('.__blockquote');

			$blockquoteField.change(function () {
				const $this = $(this);
				const thisVal = $this.val();
				const $parent = $this.parents('.swell-menu-blockquote');
				$parent.find('.__blockquote').attr('data-type', thisVal);
			});
		})();

		/**
		 * マーカー
		 */
		(function () {
			const $markerMenu = $('.swell-menu-marker');

			const $typeField = $markerMenu.find('.__type');
			$typeField.change(function () {
				const thisVal = $(this).val();
				$markerMenu.attr('data-type', thisVal);
			});

			const $orangeField = $markerMenu.find('.__orange');
			$orangeField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_mark_orange', thisVal);
			});

			const $yellowField = $markerMenu.find('.__yellow');
			$yellowField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_mark_yellow', thisVal);
			});

			const $greenField = $markerMenu.find('.__green');
			$greenField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_mark_green', thisVal);
			});

			const $blueField = $markerMenu.find('.__blue');
			$blueField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_mark_blue', thisVal);
			});
		})();

		/**
		 * ボタン
		 */
		(function () {
			const $btnMenu = $('.swell-menu-btn');

			const $gradField = $btnMenu.find('.__gradation');
			$gradField.change(function () {
				const isChecked = $(this).prop('checked');
				$btnMenu.attr('data-is-grad', Number(isChecked));
			});

			// 赤
			const $redField = $btnMenu.find('.__red');
			$redField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_red', thisVal);

				// 立体部分の影の部分
				const shadowColor = getRGBA(thisVal, 1, -0.25);
				document.documentElement.style.setProperty('--color_btn_red_dark', shadowColor);
			});

			const $red2Field = $btnMenu.find('.__red2');
			$red2Field.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_red2', thisVal);
			});

			// 青
			const $blueField = $btnMenu.find('.__blue');
			$blueField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_blue', thisVal);

				// 立体部分の影の部分
				const shadowColor = getRGBA(thisVal, 1, -0.25);
				document.documentElement.style.setProperty('--color_btn_blue_dark', shadowColor);
			});

			const $blue2Field = $btnMenu.find('.__blue2');
			$blue2Field.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_blue2', thisVal);
			});

			// 緑
			const $greenField = $btnMenu.find('.__green');
			$greenField.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_green', thisVal);

				// 立体部分の影の部分
				const shadowColor = getRGBA(thisVal, 1, -0.25);
				document.documentElement.style.setProperty('--color_btn_green_dark', shadowColor);
			});

			const $green2Field = $btnMenu.find('.__green2');
			$green2Field.change(function () {
				const thisVal = $(this).val();
				document.documentElement.style.setProperty('--color_btn_green2', thisVal);
			});

			// 丸み
			const $normalRadiusField = $btnMenu.find('[name="swell_editors[btn_radius_normal]"]');
			$normalRadiusField.change(function () {
				const thisVal = $(this).val();
				$('.is-style-btn_normal a').css('border-radius', thisVal);
			});
			const $solidRadiusField = $btnMenu.find('[name="swell_editors[btn_radius_solid]"]');
			$solidRadiusField.change(function () {
				const thisVal = $(this).val();
				$('.is-style-btn_solid a').css('border-radius', thisVal);
			});
			const $shinyRadiusField = $btnMenu.find('[name="swell_editors[btn_radius_shiny]"]');
			$shinyRadiusField.change(function () {
				const thisVal = $(this).val();
				$('.is-style-btn_shiny a').css('border-radius', thisVal);
			});
			const $lineRadiusField = $btnMenu.find('[name="swell_editors[btn_radius_line]"]');
			$lineRadiusField.change(function () {
				const thisVal = $(this).val();
				$('.is-style-btn_line a').css('border-radius', thisVal);
			});
		})();

		/**
		 * カスタム書式設置
		 */
		(function () {
			const $setMenu = $('.swell-menu-set');

			const $enableField = $setMenu.find('.__enable');
			$enableField.change(function () {
				const $this = $(this);
				const isChecked = $this.prop('checked');
				const $parent = $this.parents('.__field');
				$parent.attr('data-is-enable', Number(isChecked));
			});
		})();
	});

	// ページ上部へ
	// window.scrollTo(0, 0);
})(jQuery);
