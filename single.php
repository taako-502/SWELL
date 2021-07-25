<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
while ( have_posts() ) :
	the_post();

	$SETTING = SWELL_FUNC::get_setting();
	$the_id  = get_the_ID();

	// シェアボタンを隠すかどうか
	$show_share_btns    = get_post_meta( $the_id, 'swell_meta_hide_sharebtn', true ) !== '1';
	$show_widget_top    = get_post_meta( $the_id, 'swell_meta_show_widget_top', true );
	$show_widget_bottom = get_post_meta( $the_id, 'swell_meta_show_widget_bottom', true );
?>
<main id="main_content" class="l-mainContent l-article">
	<article class="l-mainContent__inner">
		<?php
			// タイトル周り
			if ( ! SWELL_FUNC::is_show_ttltop() ) :
				SWELL_FUNC::get_parts( 'parts/single/post_head', $the_id );
			endif;

			// アイキャッチ画像
			if ( SWELL_FUNC::is_show_thumb( $the_id ) ) :
				do_action( 'swell_before_post_thumb', $the_id );
				// @codingStandardsIgnoreStart
				echo SWELL_PARTS::post_thumbnail( $the_id );
				// @codingStandardsIgnoreEnd
			endif;

			// 記事上シェアボタン
			if ( $show_share_btns && $SETTING['show_share_btn_top'] ) :
				SWELL_FUNC::get_parts( 'parts/single/share_btns', [
					'post_id'  => $the_id,
					'position' => '-top',
				] );
			endif;

			// 記事上ウィジェット
			if ( is_active_sidebar( 'single_top' ) && '1' !== $show_widget_top ) :
				echo '<div class="w-singleTop">';
				dynamic_sidebar( 'single_top' );
				echo '</div>';
			endif;
		?>

		<div class="<?=esc_attr( apply_filters( 'swell_post_content_class', 'post_content' ) )?>">
			<?php the_content();  // 本文 ?>
		</div>
		<?php
			// 改ページナビゲーション
			$defaults = [
				'before'           => '<div class="c-pagination">',
				'after'            => '</div>',
				'next_or_number'   => 'number',
				// 'pagelink'         => '<span>%</span>',
			];
			wp_link_pages( $defaults );

			// 下部ウィジェット
			if ( is_active_sidebar( 'single_bottom' ) && '1' !== $show_widget_bottom ) :
				echo '<div class="w-singleBottom">';
					dynamic_sidebar( 'single_bottom' );
				echo '</div>';
			endif;

			// post_foot
			SWELL_FUNC::get_parts( 'parts/single/post_foot', $the_id );

			// FBいいね & Twitterフォロー ボックス
			$tw_id    = $SETTING['show_tw_follow_btn'] ? $SETTING['tw_follow_id'] : '';
			$fb_url   = $SETTING['show_fb_like_box'] ? $SETTING['fb_like_url'] : '';
			$insta_id = $SETTING['show_insta_follow_btn'] ? $SETTING['insta_follow_id'] : '';
			if ( $tw_id || $fb_url || $insta_id ) :
				SWELL_FUNC::get_parts( 'parts/single/sns_cta', [
					'tw_id'    => $tw_id,
					'fb_url'   => $fb_url,
					'insta_id' => $insta_id,
				] );
			endif;

			// 下部シェアボタン
			if ( $show_share_btns && $SETTING['show_share_btn_bottom'] ) :
				SWELL_FUNC::get_parts( 'parts/single/share_btns', [
					'post_id'  => $the_id,
					'position' => '-bottom',
				] );
			endif;

			// 固定シェアボタン
			if ( $show_share_btns && $SETTING['show_share_btn_fix'] ) :
				SWELL_FUNC::get_parts( 'parts/single/share_btns', [
					'post_id'  => $the_id,
					'position' => '-fix',
				] );
			endif;
		?>
		<div id="after_article" class="l-articleBottom">
			<?php
				if ( ! \SWELL_Theme::is_use( 'ajax_after_post' ) ) :
					SWELL_FUNC::get_parts( 'parts/single/after_article', ['post_id' => $the_id ] );
				endif;
			?>
		</div>
		<?php
			if ( SWELL_FUNC::is_show_comments( $the_id ) ) comments_template();
		?>
	</article>
</main>
<?php endwhile; ?>
<?php get_footer(); ?>
