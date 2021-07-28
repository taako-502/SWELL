<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php SWELL_Theme::root_attrs(); ?>>
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, viewport-fit=cover">
<?php
	wp_head();
	$SETTING = SWELL_FUNC::get_setting(); // SETTING取得
?>
</head>
<body>
<?php if ( function_exists( 'wp_body_open' ) ) wp_body_open(); ?>
<div id="body_wrap" <?php body_class(); ?> <?php SWELL_Theme::body_attrs(); ?>>
<?php
	// SPメニュー
	$cache_key = $SETTING['cache_spmenu'] ? 'spmenu' : '';
	SWELL_FUNC::get_parts( 'parts/header/sp_menu', null, $cache_key );

	// ヘッダー
	$cache_key = '';
	if ( $SETTING['cache_header'] ) {
		$cache_key = ( SWELL_Theme::is_top() && ! is_paged() ) ? 'header_top' : 'header_notop';
	}
	SWELL_FUNC::get_parts( 'parts/header/header_contents', null, $cache_key );

	// Barba用 wrapper
	if ( \SWELL_Theme::is_use( 'pjax' ) ) echo '<div data-barba="container" data-barba-namespace="home">';

	if ( SWELL_Theme::is_top() && ! is_paged() ) {
		// メインビジュアル
		if ( 'none' !== $SETTING['main_visual_type'] ) {
			$cache_key = $SETTING['cache_top'] ? 'mv' : '';
			SWELL_FUNC::get_parts( 'parts/top/main_visual', null, $cache_key );
		}

		// MV下通知バー
		// if ( 1 ) {
		//	$cache_key = $SETTING['cache_top'] ? 'mv_info' : '';
		// 	SWELL_FUNC::get_parts( 'parts/top/mv_info', null, $cache_key );
		// }

		// 記事スライダー
		if ( 'on' === $SETTING['show_post_slide'] ) {
			$cache_key = $SETTING['cache_top'] ? 'post_slider' : '';
			SWELL_FUNC::get_parts( 'parts/top/post_slider', null, $cache_key );
		}
	}

	// タイトル(コンテンツ上)
	if ( SWELL_Theme::is_show_ttltop() ) SWELL_FUNC::get_parts( 'parts/top_title_area' );

	// ぱんくず
	if ( 'top' === $SETTING['pos_breadcrumb'] ) SWELL_FUNC::get_parts( 'parts/breadcrumb' );

?>
<div id="content" class="l-content l-container" <?php SWELL_Theme::content_attrs(); ?>>
<?php
	// ピックアップバナー
	if ( SWELL_Theme::is_show_pickup_banner() ) {
		$cache_key = $SETTING['cache_top'] ? 'pickup_banner' : '';
		SWELL_FUNC::get_parts( 'parts/top/pickup_banner', null, $cache_key );
	}
