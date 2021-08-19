<?php if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$the_id = get_queried_object_id();
?>
<main id="main_content" class="l-mainContent l-article">
	<div class="l-mainContent__inner">
		<?php if ( is_front_page() && is_active_sidebar( 'front_top' ) ) : ?>
			<div class="w-frontTop">
				<?php dynamic_sidebar( 'front_top' ); ?>
			</div>
		<?php endif; ?>
		<?php
			// 「投稿ページ」の時、タイトルとコンテンツ出力
			if ( ! is_front_page() && $the_id ) :
				$queried_object = get_queried_object();
				$the_content    = $queried_object->post_content ?? '';

				SWELL_Theme::get_parts( 'parts/page_head' );

				if ( ! empty( $the_content ) ) :
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="post_content">' . apply_filters( 'the_content', $the_content ) . '</div>';
				endif;
			endif;

			// 投稿リスト
			$cache_key = '';
			$the_paged = (int) get_query_var( 'paged' );
			if ( 0 === $the_paged && SWELL_Theme::get_setting( 'cache_top' ) ) :
				$cache_key = ( IS_MOBILE ) ? 'home_posts_sp' : 'home_posts';
			endif;

			echo '<div class="p-homeContent l-parent u-mt-40">';
			SWELL_Theme::get_parts( 'parts/home_content', '', $cache_key, 24 * HOUR_IN_SECONDS );
			echo '</div>';
		?>

		<?php if ( is_front_page() && is_active_sidebar( 'front_bottom' ) ) : ?>
			<div class="w-frontBottom">
				<?php dynamic_sidebar( 'front_bottom' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! is_front_page() ) : ?>
			<?php
				// ウィジェット（「投稿ページ」の時）
				$meta = get_post_meta( $the_id, 'meta_show_widget_bottom', true );
				if ( is_active_sidebar( 'page_bottom' ) && '1' !== $meta ) :
					echo '<div class="w-pageBottom">';
					dynamic_sidebar( 'page_bottom' );
					echo '</div>';
				endif;
			?>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
