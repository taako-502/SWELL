<?php
use \SWELL_THEME\Parts\Post_List;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 記事スライダーの投稿リスト出力テンプレート
 */
$query_args  = $variable['query_args'] ?? [];
$thumb_sizes = $variable['thumb_sizes'] ?? '';

$SETTING = SWELL_FUNC::get_setting();

// 表示設定
$show_date     = $SETTING['ps_show_date'];
$show_modified = $SETTING['ps_show_modified'];
$show_author   = $SETTING['ps_show_author'];
$cat_pos       = $SETTING['pickup_cat_pos'];

// クエリの取得
$the_query = new WP_Query( apply_filters( 'swell_pickup_post_args', $query_args ) );

?>
<ul class="p-postSlider__postList p-postList swiper-wrapper">
<?php
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		$post_data = get_post();
		$the_id    = $post_data->ID;
		$the_title = get_the_title();

		if ( mb_strwidth( $the_title, 'UTF-8' ) > 120 ) :
			$the_title = mb_strimwidth( $the_title, 0, 120, '...', 'UTF-8' );
		endif;
?>
	<li class="p-postList__item swiper-slide">
		<a href="<?php the_permalink( $the_id ); ?>" class="p-postList__link">
			<?php
				SWELL_FUNC::get_parts(
					'parts/post_list/item/thumb',
					[
						'post_id'  => $the_id,
						'cat_pos'  => $cat_pos,
						'size'     => 'large',
						'sizes'    => $thumb_sizes,
					]
				);
			?>
			<div class="p-postList__body">
				<h2 class="p-postList__title">
					<?=esc_html( $the_title )?>
				</h2>
				<div class="p-postList__meta">
					<?php
						// 日付
						SWELL_FUNC::get_parts(
							'parts/post_list/item/date',
							[
								'show_date'     => $show_date,
								'show_modified' => $show_modified,
								'date'          => $post_data->post_date,
								'modified'      => $post_data->post_modified,
							]
						);
						if ( 'on_title' === $cat_pos ) :
							\SWELL_Theme::pluggable_parts( 'post_list_category', [
								'post_id' => $the_id,
							] );
						endif;

						if ( $show_author ) :
							\SWELL_Theme::pluggable_parts( 'post_list_author', [
								'author_id' => $post_data->post_author,
							] );
						endif;
					?>
				</div>
			</div>
		</a>
	</li>
<?php
	endwhile;
	wp_reset_postdata();
?>
</ul>
