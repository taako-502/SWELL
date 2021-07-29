<?php
namespace SWELL_Theme\Custom_Menu;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * カスタムメニューのロケーション登録
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\hook_after_setup_theme', 9 );
function hook_after_setup_theme() {
	register_nav_menus( [
		'header_menu'     => 'グローバルナビ',
		'sp_head_menu'    => 'スマホ用ヘッダー',
		'nav_sp_menu'     => 'スマホ開閉メニュー内',
		'footer_menu'     => 'フッター',
		'fix_bottom_menu' => '固定フッター（SP）',
		'pickup_banner'   => 'ピックアップバナー',
	] );
}


/**
 * liタグのIDを削除する
 */
add_filter( 'nav_menu_item_id', __NAMESPACE__ . '\hook_nav_menu_item_id', 10, 3 );
function hook_nav_menu_item_id( $menu_id, $item, $args ) {
	$noid_locations = [
		'header_menu',
		'sp_head_menu',
		'nav_sp_menu',
		'footer_menu',
		'fix_bottom_menu',
		'pickup_banner',
	];
	if ( in_array( $args->theme_location, $noid_locations, true ) ) {
		return '';
	}
	return $menu_id;
}


/**
 * liに付与されるクラスをカスタマイズ
 */
add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\hook_nav_menu_css_class', 10, 3 );
function hook_nav_menu_css_class( $classes, $item, $args ) {
	$is_swell_menu = false;
	$location      = $args->theme_location;

	if ( in_array( $location, [
		'header_menu',
		'nav_sp_menu',
		'footer_menu',
		'fix_bottom_menu',
	], true ) ) {

		$is_swell_menu = true;

		// menu-item と menu-item-has-children だけ残す
		$has_child = in_array( 'menu-item-has-children', $classes, true );
		$classes   = [ 'menu-item' ];
		if ( $has_child ) {
			$classes[] = 'menu-item-has-children';
		}
	} elseif ( 'sp_head_menu' === $location ) {

		$is_swell_menu = true;
		$classes       = [ 'menu-item', 'swiper-slide' ];

		// current_の付与はJSで（キャッシュとの兼ね合い）
		// if( $item->current == true ) {$classes[] = 'current_';}

	} elseif ( 'pickup_banner' === $location ) {

		$is_swell_menu = true;
		$classes       = [ 'p-pickupBanners__item' ];

	}

	// カスタムメニューの設定でクラスの指定があればそれも追加する。
	$item_classes = $item->classes;
	if ( $is_swell_menu && '' !== $item_classes[0] ) {
		$classes[] = $item_classes[0];
	}
	return $classes;
}

/**
 * リストのHTMLを組み替える
 * 例：「説明」を追加（ナビゲーションの英語テキストに使用）
 */
add_filter( 'walker_nav_menu_start_el', __NAMESPACE__ . '\hook_walker_nav_menu_start_el', 10, 4 );
function hook_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {

	// 特定のメニューに対して処理
	$menu_location = $args->theme_location;
	if ( $menu_location === 'header_menu' || $menu_location === 'nav_sp_menu' ) {
		if ( ! empty( $item->description ) ) {
			// desc はしばらく残す
			$item_output = str_replace( '</a>', '<span class="c-smallNavTitle desc">' . $item->description . '</span></a>', $item_output );
		}

		if ( SWELL::is_use( 'acc_submenu' ) && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$item_output = str_replace(
				'</a>',
				'<span class="c-submenuToggleBtn" data-onclick="toggleSubmenu"></span></a>',
				$item_output
			);
		}
	} elseif ( $menu_location === 'fix_bottom_menu' ) {
		// 固定フッターメニュー
		$target      = ( $item->target === '_blank' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		$item_output = '<a href="' . $item->url . '"' . $target . '>' .
			'<i class="' . $item->description . '"></i><span>' . $item->title . '</span>' .
		'</a>';

	} elseif ( $menu_location === 'pickup_banner' ) {
		// ピックアップバナー

		// 枚数
		$menu_count = $args->menu->count;

		// aタグの属性値
		$a_props                                    = 'href="' . $item->url . '"';
		if ( $item->target === '_blank' ) $a_props .= ' target="_blank" rel="noopener noreferrer"';

		$img   = '';
		$img_s = '';

		$layout_pc = \SWELL_Theme::get_setting( 'pickbnr_layout_pc' );
		$layout_sp = \SWELL_Theme::get_setting( 'pickbnr_layout_sp' );

		$size = 'full';
		if ( $menu_count !== 1 && $layout_sp === 'fix_col2' ) {
			$size = 'large';
		}

		$sizes_pc = '400px';
		if ( $layout_pc === 'flex' && $menu_count === 1 ) {
			$sizes_pc = '960px';
		} elseif ( $layout_pc === 'flex' && $menu_count === 2 ) {
			$sizes_pc = '600px';
		} elseif ( $layout_pc === 'fix_col2' ) {
			$sizes_pc = '600px';
		}
		$sizes_sp = $layout_sp === 'fix_col2' ? '50vw' : '100vw';
		$sizes    = '(min-width: 960px) ' . $sizes_pc . ', ' . $sizes_sp;

		if ( $item->type === 'post_type' ) {

			$post_id = $item->object_id;
			$thumb   = SWELL::get_thumbnail( [
				'post_id'          => $post_id,
				'size'             => $size,
				'sizes'            => $sizes,
				'class'            => 'p-articleThumb__img',
				'placeholder_size' => 'medium',
				'use_lazyload'     => true,
			] );

		} elseif ( $item->type === 'taxonomy' ) {
			$thumb = SWELL::get_thumbnail( [
				'term_id'          => $item->object_id,
				'size'             => $size,
				'sizes'            => $sizes,
				'class'            => 'p-articleThumb__img',
				'placeholder_size' => 'medium',
				'use_lazyload'     => true,
			] );
		}

		// 説明欄に直接画像URLがある場合
		if ( $img_url = $item->description ) {

			$img_id = attachment_url_to_postid( $img_url );
			if ( $img_id ) {
				$img   = $img_url;
				$img_s = wp_get_attachment_image_url( $img_id, 'medium' );
			} elseif ( strpos( $img_url, 'http' ) === 0 ) {
				$img   = $img_url;
				$img_s = SWELL::$placeholder;
			}
			$thumb = '<img src="' . $img_s . '" data-src="' . $img . '" alt="" class="c-bannerLink__img lazyload">';
		}

		// 画像なければ NO IMAGE
		if ( ! $thumb ) {
			$thumb = '<img src="' . SWELL::get_noimg( 'small' ) . '" data-src="' . SWELL::get_noimg( 'url' ) . '" alt="" class="c-bannerLink__img lazyload">';
		}

		$item_output = '<a ' . $a_props . ' class="c-bannerLink">' .
			'<figure class="c-bannerLink__figure">' . $thumb . '</figure>' .
			'<span class="c-bannerLink__label">' . $item->title . '</span>' .
		'</a>';
	} elseif ( $menu_location === '' ) {
		if ( SWELL::is_use( 'acc_submenu' ) && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$span        = '<span class="c-submenuToggleBtn" data-onclick="toggleSubmenu"></span>';
			$item_output = str_replace( '</a>', $span . '</a>', $item_output );
		}
	}

	// icon 使えるように
	$item_output = do_shortcode( $item_output );

	return $item_output;
}
