<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING = SWELL_FUNC::get_setting();
$the_id  = $variable ?: get_queried_object_id();

// 記事タイトル
if ( ! SWELL_Theme::is_show_ttltop() ) {
	\SWELL_Theme::pluggable_parts( 'page_title', [
		'title'     => get_the_title(),
		'subtitle'  => get_post_meta( $the_id, 'swell_meta_subttl', true ),
		'has_inner' => true,
	] );
}

// アイキャッチ画像
if ( SWELL_Theme::is_show_thumb( $the_id ) ) {
	echo SWELL_PARTS::post_thumbnail( $the_id );
}

// コンテンツ上ウィジェット
$meta = get_post_meta( $the_id, 'swell_meta_show_widget_top', true );
if ( is_active_sidebar( 'page_top' ) && $meta !== '1' ) {
	echo '<div class="w-pageTop">';
		dynamic_sidebar( 'page_top' );
	echo '</div>';
}
