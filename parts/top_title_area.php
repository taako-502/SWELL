<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SWELL_Theme::is_show_ttltop()が true の時のみ呼び出される。
 * タームアーカイブ / 固定ページ / 投稿ページの３種類で呼び出される可能性があることに注意。
 */
$SETTING = SWELL_Theme::get_setting();

// タイトル・背景画像を取得
if ( SWELL_Theme::is_term() ) {
	// タームアーカイブの場合
	$term_id = get_queried_object_id();
	$ttlbg   = get_term_meta( $term_id, 'swell_term_meta_ttlbg', 1 )
			?: $SETTING['ttlbg_default_img']
			?: get_term_meta( $term_id, 'swell_term_meta_image', 1 )
			?: SWELL_Theme::get_noimg( 'url' );
} else {
	// 投稿ページ・固定ページの場合
	$the_id = get_queried_object_id();  // ※ get_the_ID() は is_home でアウト
	$ttlbg  = get_post_meta( $the_id, 'swell_meta_ttlbg', true )
	?: $SETTING['ttlbg_default_img']
	?: get_the_post_thumbnail_url( $the_id, 'full' )
	?: SWELL_Theme::get_noimg( 'url' );
}

// 背景画像へのフィルター
$filter_name  = $SETTING['title_bg_filter'];
$filter_class = ( 'nofilter' === $filter_name ) ? '' : "c-filterLayer -$filter_name";

$ttlbg = '<img class="l-topTitleArea__img c-filterLayer__img u-obf-cover" src="' . esc_attr( $ttlbg ) . '" alt="" aria-hidden="true">';
$ttlbg = SWELL_Theme::set_lazyload( $ttlbg, apply_filters( 'swell_top_area_lazy_type', 'none' ) );

?>
<div id="top_title_area" class="l-topTitleArea <?=esc_attr( $filter_class )?>">
	<?php echo $ttlbg; // phpcs:ignore ?>
	<div class="l-topTitleArea__body l-container">
	<?php
		if ( SWELL_Theme::is_term() ) :

		SWELL_Theme::pluggable_parts( 'term_title', [
			'term_id'   => $term_id,
			'has_inner' => false,
		] );
	SWELL_PARTS::the_term_navigation( $term_id );

		elseif ( is_single() ) :

			SWELL_Theme::get_parts( 'parts/single/post_head' );

		elseif ( is_page() || is_home() ) :

			// タイトル
			SWELL_Theme::pluggable_parts( 'page_title', [
				'title'     => get_the_title( $the_id ),
				'subtitle'  => get_post_meta( $the_id, 'swell_meta_subttl', true ),
				'has_inner' => false,
			] );

			// 抜粋文
			$post_data = get_post( $the_id );
			$excerpt   = $post_data->post_excerpt;
			if ( $excerpt ) :
				echo '<div class="c-pageExcerpt">' . wp_kses( $excerpt, SWELL_Theme::$allowed_text_html ) . '</div>';
			endif;

		endif;
		?>
	</div>
</div>
