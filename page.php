<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
if ( is_front_page() ) :
	SWELL_FUNC::get_parts( 'tmp/front' );
else :
	while ( have_posts() ) :
		the_post();
		$the_id = get_the_ID();
	?>
		<main id="main_content" class="l-mainContent l-article">
			<div class="l-mainContent__inner">
				<?php SWELL_FUNC::get_parts( 'parts/page_head', $the_id ); ?>
				<div class="<?=esc_attr( apply_filters( 'swell_post_content_class', 'post_content' ) )?>">
					<?php the_content(); ?>
				</div>
				<?php
					// 改ページナビゲーション
					$defaults = [
						'before'           => '<div class="c-pagination">',
						'after'            => '</div>',
						'next_or_number'   => 'number',
						// 'pagelink'      => '<span>%</span>',
					];
					wp_link_pages( $defaults );

					// ページ下部ウィジェット
					$meta = get_post_meta( $the_id, 'swell_meta_show_widget_bottom', true );
					if ( is_active_sidebar( 'page_bottom' ) && '1' !== $meta ) :
						echo '<div class="w-pageBottom">';
							dynamic_sidebar( 'page_bottom' );
						echo '</div>';
					endif;
				?>
			</div>
			<?php if ( SWELL_FUNC::is_show_comments( $the_id ) ) comments_template(); ?>
		</main>
	<?php
	endwhile; // End loop
endif;
get_footer();
