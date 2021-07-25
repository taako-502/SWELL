<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SWELL_FUNC::is_show_ttltop()が true の時のみ呼び出される。
 * タームアーカイブ / 固定ページ / 投稿ページの３種類で呼び出される可能性があることに注意。
 */
$SETTING = SWELL_FUNC::get_setting();

// タイトル・背景画像を取得
if ( \SWELL_Theme::is_term() ) {
	// タームアーカイブの場合
	$term_id = get_queried_object_id();

	// 背景画像
	$ttlbg = get_term_meta( $term_id, 'swell_term_meta_ttlbg', 1 )
			?: $SETTING['ttlbg_default_img']
			?: get_term_meta( $term_id, 'swell_term_meta_image', 1 );
	if ( $ttlbg ) {
		$ttlbg_id = attachment_url_to_postid( $ttlbg );
		$ttlbg_s  = $ttlbg_id ? wp_get_attachment_image_url( $ttlbg_id, 'medium' ) : '';
	} else {
		$ttlbg   = \SWELL_Theme::get_noimg( 'url' );
		$ttlbg_s = \SWELL_Theme::get_noimg( 'small' );
	}
} else {
	// 投稿ページ・固定ページの場合
	$the_id = get_queried_object_id();  // ※ get_the_ID() は is_home でアウト

	// 背景画像
	$ttlbg = get_post_meta( $the_id, 'swell_meta_ttlbg', true ) ?: $SETTING['ttlbg_default_img'];
	if ( $ttlbg ) {
		$ttlbg_id = attachment_url_to_postid( $ttlbg );
		$ttlbg_s  = $ttlbg_id ? wp_get_attachment_image_url( $ttlbg_id, 'medium' ) : '';
	} else {
		$ttlbg   = get_the_post_thumbnail_url( $the_id, 'full' ) ?: \SWELL_Theme::get_noimg( 'url' );
		$ttlbg_s = get_the_post_thumbnail_url( $the_id, 'medium' ) ?: \SWELL_Theme::get_noimg( 'small' );
	}
}

// 背景画像へのフィルター
$filter_name  = $SETTING['title_bg_filter'];
$filter_class = ( 'nofilter' === $filter_name ) ? '' : "c-filterLayer -$filter_name";

?>
<div id="top_title_area" class="l-topTitleArea <?=esc_attr( $filter_class )?>">
	<div class="l-topTitleArea__img c-filterLayer__img lazyload" data-bg="<?=esc_attr( $ttlbg )?>" style="background-image:url(<?=esc_attr( $ttlbg_s )?>)"></div>
	<div class="l-topTitleArea__body l-container">
	<?php
		if ( \SWELL_Theme::is_term() ) :

			\SWELL_Theme::pluggable_parts( 'term_title', [
				'term_id'   => $term_id,
				'has_inner' => false,
			] );
			SWELL_PARTS::the_term_navigation( $term_id );

		elseif ( is_single() ) :

			SWELL_FUNC::get_parts( 'parts/single/post_head', $the_id );

		elseif ( is_page() || is_home() ) :

			// タイトル
			\SWELL_Theme::pluggable_parts( 'page_title', [
				'title'     => get_the_title( $the_id ),
				'subtitle'  => get_post_meta( $the_id, 'swell_meta_subttl', true ),
				'has_inner' => false,
			] );

			// 抜粋文
			$post_data = get_post( $the_id );
			$excerpt   = $post_data->post_excerpt;
			if ( $excerpt ) echo '<div class="c-pageExcerpt">' . $excerpt . '</div>';

		endif;
		?>
	</div>
</div>
