/* eslint camelcase : 0*/
(function ($) {
	// Wait until the customizer has finished loading.

	wp.customize.bind('ready', function () {
		// $('.customize-control-title.-big').click(function(e){
		// 	$(this).toggleClass('is-closed');
		// });

		// ページ背景画像
		function toggle__bodyBgSettings(val) {
			if (val) {
				$('.customize-control.-bodybg-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-bodybg-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[body_bg]', function (value) {
			toggle__bodyBgSettings(value.get());
			value.bind(function (to) {
				toggle__bodyBgSettings(to);
			});
		});

		// トップヘッダー
		function toggle__topHeader(val) {
			if ('no' !== val) {
				$('.customize-control.-top-header-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-top-header-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[header_transparent]', function (value) {
			toggle__topHeader(value.get());
			value.bind(function (to) {
				toggle__topHeader(to);
			});
		});

		// 追従ヘッダー
		function toggle__fixHeadOpacity(val) {
			if (val) {
				$('.customize-control.-fixhead-pc-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-fixhead-pc-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[fix_header]', function (value) {
			toggle__fixHeadOpacity(value.get());
			value.bind(function (to) {
				toggle__fixHeadOpacity(to);
			});
		});

		//グロナビ背景色
		// function toggle__gnavBgType(val) {
		// 	if ('parallel_bottom' === val || 'parallel_top' === val) {
		// 		$('.customize-control.-gnav-bg-type').removeClass('-hide_');
		// 		$('.customize-control.-gnav-bg-setting').removeClass('-hide');
		// 	} else {
		// 		$('.customize-control.-gnav-bg-type').addClass('-hide_');
		// 		$('.customize-control.-gnav-bg-setting').addClass('-hide');
		// 	}
		// }
		// wp.customize('loos_customizer[header_layout]', function (value) {
		// 	toggle__gnavBgType(value.get());
		// 	value.bind(function (to) {
		// 		toggle__gnavBgType(to);
		// 	});
		// });
		function toggle__gnavBgColor(val) {
			if ('overwrite' === val) {
				$('.customize-control.-gnav-bg-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-gnav-bg-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[gnav_bg_type]', function (value) {
			toggle__gnavBgColor(value.get());
			value.bind(function (to) {
				toggle__gnavBgColor(to);
			});
		});

		//ホバー時のライン
		function toggle__gnavLine(val) {
			if ('line_center' === val || 'line_left' === val || 'block' === val) {
				$('.customize-control.-gnav-line-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-gnav-line-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[headmenu_effect]', function (value) {
			toggle__gnavLine(value.get());
			value.bind(function (to) {
				toggle__gnavLine(to);
			});
		});

		//お知らせバー
		function toggle__infoBar(val) {
			if ('none' !== val) {
				$('.customize-control.-swell-info-bar').removeClass('-hide');
			} else {
				$('.customize-control.-swell-info-bar').addClass('-hide');
			}
		}
		wp.customize('loos_customizer[pos_info_bar]', function (value) {
			toggle__infoBar(value.get());
			value.bind(function (to) {
				toggle__infoBar(to);
			});
		});

		//お知らせバーボタン
		function toggle__infoBtn(val) {
			if ('btn' === val) {
				$('.customize-control.-info-btn').removeClass('-hide_');
			} else {
				$('.customize-control.-info-btn').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[info_flowing]', function (value) {
			toggle__infoBtn(value.get());
			value.bind(function (to) {
				toggle__infoBtn(to);
			});
		});

		//お知らせバー グラデーション
		function toggle__infoColor2(val) {
			if ('gradation' === val) {
				$('.customize-control.-info-col2').removeClass('-hide_');
			} else {
				$('.customize-control.-info-col2').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[info_bar_effect]', function (value) {
			toggle__infoColor2(value.get());
			value.bind(function (to) {
				toggle__infoColor2(to);
			});
		});

		// show_post_thumb
		function toggle__showNoImgThumb(val) {
			if (val) {
				$('.customize-control.-show-noimg-thumb').removeClass('-hide_');
			} else {
				$('.customize-control.-show-noimg-thumb').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[show_post_thumb]', function (value) {
			toggle__showNoImgThumb(value.get());
			value.bind(function (to) {
				toggle__showNoImgThumb(to);
			});
		});

		// コンテンツエリアの独立
		function toggle__frameSettings(val) {
			if ('frame_off' !== val) {
				$('.customize-control.-frame-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-frame-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[content_frame]', function (value) {
			toggle__frameSettings(value.get());
			value.bind(function (to) {
				toggle__frameSettings(to);
			});
		});

		// SNS CTA
		function toggle__twitterCTA(val) {
			if (val) {
				$('.customize-control.-twitter-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-twitter-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[show_tw_follow_btn]', function (value) {
			toggle__twitterCTA(value.get());
			value.bind(function (to) {
				toggle__twitterCTA(to);
			});
		});

		function toggle__instaCTA(val) {
			if (val) {
				$('.customize-control.-insta-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-insta-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[show_insta_follow_btn]', function (value) {
			toggle__instaCTA(value.get());
			value.bind(function (to) {
				toggle__instaCTA(to);
			});
		});

		function toggle__fbCTA(val) {
			if (val) {
				$('.customize-control.-fb-setting').removeClass('-hide_');
			} else {
				$('.customize-control.-fb-setting').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[show_fb_like_box]', function (value) {
			toggle__fbCTA(value.get());
			value.bind(function (to) {
				toggle__fbCTA(to);
			});
		});

		// メインビジュアルの種類による項目の表示・非表示
		function toggle__mvTipe(val) {
			if ('slider' === val) {
				$('.customize-control.swell-mv-common').removeClass('-hide');
				$('.customize-control.swell-mv-slider').removeClass('-hide');
				$('.customize-control.swell-mv-movie').addClass('-hide');
			} else if ('movie' === val) {
				$('.customize-control.swell-mv-common').removeClass('-hide');
				$('.customize-control.swell-mv-movie').removeClass('-hide');
				$('.customize-control.swell-mv-slider').addClass('-hide');
			} else {
				$('.customize-control.swell-mv-common').addClass('-hide');
				$('.customize-control.swell-mv-slider').addClass('-hide');
				$('.customize-control.swell-mv-movie').addClass('-hide');
			}
		}
		wp.customize('loos_customizer[main_visual_type]', function (value) {
			toggle__mvTipe(value.get());
			value.bind(function (to) {
				toggle__mvTipe(to);
			});
		});

		// スライドの高さ
		function toggle__slideSize(val) {
			if ('set' === val) {
				$('.customize-control.swell-mv-height').removeClass('-hide_');
			} else {
				$('.customize-control.swell-mv-height').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[mv_slide_size]', function (value) {
			toggle__slideSize(value.get());
			value.bind(function (to) {
				toggle__slideSize(to);
			});
		});

		// 次のスライドを表示
		function toggle__nextSlide(val, num) {
			if (val) {
				$('.customize-control.-slide-num-' + num).removeClass('-hide_');
			} else {
				$('.customize-control.-slide-num-' + num).addClass('-hide_');
			}
		}
		// そのスライドがセットされているかどうか
		function set__hasMvImage(val, num) {
			if (val) {
				$('.customize-control.-slide-num-' + num).addClass('has-image');
			} else {
				$('.customize-control.-slide-num-' + num).removeClass('has-image');
			}
		}
		function toggle__mvBtnSettingw(val, num) {
			if (val) {
				$('#customize-control-loos_customizer-slider' + num + '_btncol').removeClass(
					'-hide_'
				);
				$('#customize-control-loos_customizer-slider' + num + '_btntype').removeClass(
					'-hide_'
				);
			} else {
				$('#customize-control-loos_customizer-slider' + num + '_btncol').addClass('-hide_');
				$('#customize-control-loos_customizer-slider' + num + '_btntype').addClass(
					'-hide_'
				);
			}
		}

		for (let i = 1; 5 > i; i++) {
			wp.customize('loos_customizer[slider' + i + '_img]', function (value) {
				const nextNum = i + 1;
				set__hasMvImage(value.get(), i);
				toggle__nextSlide(value.get(), nextNum);
				value.bind(function (to) {
					set__hasMvImage(to, i);
					toggle__nextSlide(to, nextNum);
				});
			});

			wp.customize('loos_customizer[slider' + i + '_btn_text]', function (value) {
				toggle__mvBtnSettingw(value.get(), i);
				value.bind(function (to) {
					toggle__mvBtnSettingw(to, i);
				});
			});
		}

		// 画像２枚目があるかどうか
		function toggle__mvSliderSettings(val) {
			if ('' !== val) {
				$('.customize-control.-mv-slider-setting').removeClass('-hide_');
				$('.customize-control.-slider-area-bigttl').addClass('-desc-hidden');
			} else {
				$('.customize-control.-mv-slider-setting').addClass('-hide_');
				$('.customize-control.-slider-area-bigttl').removeClass('-desc-hidden');
			}
		}
		wp.customize('loos_customizer[slider2_img]', function (value) {
			toggle__mvSliderSettings(value.get());
			value.bind(function (to) {
				toggle__mvSliderSettings(to);
			});
		});

		// 記事スライダー ピックアップ対象
		function toggle__psPickup(val) {
			if ('tag' === val) {
				$('.customize-control.-pickup-tag').removeClass('-hide_');
				$('.customize-control.-pickup-cat').addClass('-hide_');
			} else {
				$('.customize-control.-pickup-tag').addClass('-hide_');
				$('.customize-control.-pickup-cat').removeClass('-hide_');
			}
		}
		wp.customize('loos_customizer[ps_pickup_type]', function (value) {
			toggle__psPickup(value.get());
			value.bind(function (to) {
				toggle__psPickup(to);
			});
		});

		// 記事一覧リスト : カテゴリー
		function toggle__postListCategory(val) {
			if ('on_thumb' === val) {
				$('.customize-control.-cat-on-thumb').removeClass('-hide_');
			} else {
				$('.customize-control.-cat-on-thumb').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[category_pos]', function (value) {
			toggle__postListCategory(value.get());
			value.bind(function (to) {
				toggle__postListCategory(to);
			});
		});

		// ボタンのグラデーション設定
		function toggle__btnColor02(val) {
			if (val) {
				$('.customize-control.-btn-gradation-color').removeClass('-hide_');
			} else {
				$('.customize-control.-btn-gradation-color').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[is_btn_gradation]', function (value) {
			toggle__btnColor02(value.get());
			value.bind(function (to) {
				toggle__btnColor02(to);
			});
		});

		// 目次
		function toggle__tocBeforeColor(val) {
			if ('custom' === val) {
				$('.customize-control.-toc-custom-color').removeClass('-hide_');
			} else {
				$('.customize-control.-toc-custom-color').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[toc_before_color]', function (value) {
			toggle__tocBeforeColor(value.get());
			value.bind(function (to) {
				toggle__tocBeforeColor(to);
			});
		});

		// pjax
		function toggle__pjaxKeys(val) {
			if ('pjax' === val) {
				$('.customize-control.-pjax_prevent_pages').removeClass('-hide_');
			} else {
				$('.customize-control.-pjax_prevent_pages').addClass('-hide_');
			}

			if ('prefetch' === val) {
				$('.customize-control.-prefetch_prevent_keys').removeClass('-hide_');
			} else {
				$('.customize-control.-prefetch_prevent_keys').addClass('-hide_');
			}
		}
		wp.customize('loos_customizer[use_pjax]', function (value) {
			toggle__pjaxKeys(value.get());
			value.bind(function (to) {
				toggle__pjaxKeys(to);
			});
		});
	});
})(jQuery);
