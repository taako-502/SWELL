<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$the_id    = get_the_ID();
$post_data = get_post( $the_id );

$SETTING = SWELL_Theme::get_setting();

if ( $SETTING['show_page_links'] ) : // 前の記事・次の記事
	SWELL_Theme::get_parts( 'parts/single/prev_next_link' );
endif;

// CTAウィジェット
$hide_meta = get_post_meta( $the_id, 'swell_meta_hide_widget_cta', true );
if ( '1' !== $hide_meta ) :
	$cta_id     = 0;
	$categories = get_the_category( $the_id ) ?: [];
	foreach ( $categories as $the_cat ) :
		$cta_id = get_term_meta( $the_cat->term_id, 'swell_term_meta_cta_parts', 1 );
		if ( $cta_id ) break; // CTAが取得できればループ終了。(先に取得できるカテゴリーを優先)
	endforeach;
	if ( $cta_id ) :
		echo '<div class="w-cta">' . do_shortcode( '[blog_parts id=' . $cta_id . ']' ) . '</div>';
	elseif ( is_active_sidebar( 'single_cta' ) ) :
		echo '<div class="w-cta">';
			dynamic_sidebar( 'single_cta' );
		echo '</div>';
	endif;
endif;

// 著者情報
$show_meta = get_post_meta( $the_id, 'swell_meta_show_author', true );
if ( 'hide' !== $show_meta && ( 'show' === $show_meta || $SETTING['show_author'] ) ) :
	SWELL_Theme::get_parts( 'parts/single/post_author', $post_data->post_author );
endif;

// 関連記事前ウィジェット
if ( is_active_sidebar( 'before_related' ) ) :
	echo '<div class="l-articleBottom__section w-beforeRelated">';
		dynamic_sidebar( 'before_related' );
	echo '</div>';
endif;

// 関連記事
$show_meta = get_post_meta( $the_id, 'swell_meta_show_related', true );
if ( 'hide' !== $show_meta && ( 'show' === $show_meta || $SETTING['show_related_posts'] ) ) :
	SWELL_Theme::get_parts( 'parts/single/related_post_list', $the_id );
endif;

// 関連記事前ウィジェット
if ( is_active_sidebar( 'after_related' ) ) :
	echo '<div class="l-articleBottom__section w-afterRelated">';
		dynamic_sidebar( 'after_related' );
	echo '</div>';
endif;
