<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 投稿一覧リストの出力テンプレート
 */
$list_type      = $variable['list_type'] ?? SWELL_Theme::$list_type;
$thumb_sizes    = $variable['thumb_sizes'] ?? '';
$cat_pos        = $variable['cat_pos'] ?? 'none';
$show_title     = $variable['show_title'] ?? true;
$show_date      = $variable['show_date'] ?? true;
$show_modified  = $variable['show_modified'] ?? false;
$show_pv        = $variable['show_pv'] ?? false;
$show_author    = $variable['show_author'] ?? false;
$excerpt_length = $variable['excerpt_length'] ?? 0;

// 投稿情報
$post_data = get_post();
$the_id    = $post_data->ID;
$date      = new DateTime( $post_data->post_date );
$modified  = new DateTime( $post_data->post_modified );


// 更新日が公開日より遅い場合だけ表示（ただし、更新日だけ表示の時は更新日をそのまま表示する）
if ( $show_modified && $show_date ) {
	$show_modified = ( $date < $modified ) ? $show_modified : false;
}

// 抜粋文
$excerpt = SWELL_Theme::get_excerpt( $post_data );

?>
<li class="p-postList__item">
	<a href="<?php the_permalink( $the_id ); ?>" class="p-postList__link">
		<div class="c-postTitle">
			<h2 class="c-postTitle__ttl"><?php the_title(); ?></h2>
			<?php if ( $show_date ) : ?>
				<time class="c-postTitle__date u-thin" datetime="<?=esc_attr( $date->format( 'Y-m-d' ) )?>">
					<span class="__y"><?=esc_html( $date->format( 'Y' ) )?></span>
					<span class="__md"><?=esc_html( $date->format( 'n/d' ) )?></span>
				</time>
			<?php endif; ?>
		</div>
		<?php
			SWELL_Theme::get_parts( 'parts/post_list/item/thumb', [
				'post_id'  => $the_id,
				'cat_pos'  => $cat_pos,
				'size'     => 'full',
				'sizes'    => $thumb_sizes,
			] );
		?>
		<div class="p-postList__body">
			<div class="p-postList__meta">
				<?php if ( $show_modified ) : ?>
					<time class="c-postTimes__modified icon-modified" datetime="<?=esc_attr( $modified->format( 'Y-m-d' ) )?>"><?=esc_html( $modified->format( 'Y.m.d' ) )?></time>
				<?php endif; ?>
				<?php
					if ( 'beside_date' === $cat_pos ) :
						SWELL_Theme::pluggable_parts( 'post_list_category', [
							'post_id' => $the_id,
						] );
					endif;

					if ( $show_pv ) :
						SWELL_Theme::pluggable_parts( 'post_list_pv', [
							'post_id' => $the_id,
						] );
					endif;

					if ( $show_author ) :
						SWELL_Theme::pluggable_parts( 'post_list_author', [
							'author_id' => $post_data->post_author,
						] );
					endif;
				?>
			</div>
			<?php if ( ! empty( $excerpt ) ) : ?>
				<div class="p-postList__excerpt"><?=wp_kses_post( $excerpt )?></div>
			<?php endif; ?>
		</div>
	</a>
</li>
