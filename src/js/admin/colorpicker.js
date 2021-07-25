/**
 * カラーピッカーに関するスクリプト。
 * https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
 */
console.log('Loaded colorpicker.js.');

(function ($) {
	//通常（ウィジェット以外）
	$(function () {
		$('.colorpicker').wpColorPicker({
			change(event, ui) {
				//チェンジイベントを発火させる setTimeoutでちょっと遅らせないと選択した色が反映されない
				const $this = $(this);
				setTimeout(function () {
					$this.trigger('change');
				}, 10);
			},
			clear() {
				// クリアクリック時にも changeイベントを発火させる。
				const $this = $(this);
				const $colorPicker = $this.prev().find('input');
				setTimeout(function () {
					$colorPicker.trigger('change');
				}, 10);
			},
		});
	});

	//ウィジェット
	function initColorPicker(widget) {
		widget.find('.widget_colorpicker').wpColorPicker({
			change() {
				const $this = $(this);
				setTimeout(function () {
					$this.trigger('change');
				}, 10);
			},
		});
	}

	// widget-added → ウィジェットが追加されたときのイベント
	// widget-updated → ウィジェットが更新されたときのイベント
	$(document).on('widget-added widget-updated', function (event, widget) {
		initColorPicker(widget);
	});

	$(document).ready(function () {
		$('#widgets-right .widget:has(.widget_colorpicker)').each(function () {
			initColorPicker($(this));
		});
	});
})(jQuery);
