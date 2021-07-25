<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$the_id   = $variable['post_id'] ?? get_the_ID();
$sizes    = $variable['sizes'] ?? '(min-width: 960px) 960px, 100vw';
$size     = $variable['size'] ?? '';
$cat_pos  = $variable['cat_pos'] ?? 'none';
$cat_data = $variable['cat_data'] ?? []; // 後方互換用
?>
<div class="p-postList__thumb c-postThumb<?php echo ! has_post_thumbnail( $the_id ) ? ' noimg_' : ''; ?>">
	<figure class="c-postThumb__figure">
		<?php
			\SWELL_Theme::get_thumbnail( [
				'post_id'      => $the_id,
				'size'         => $size,
				'sizes'        => $sizes,
				'class'        => 'c-postThumb__img',
				'use_lazyload' => true,
				'echo'         => true,
			] );
		?>
	</figure>
	<?php
		if ( 'on_thumb' === $cat_pos || ! empty( $cat_data ) ) :
			\SWELL_Theme::pluggable_parts( 'post_list_category', [
				'post_id' => $the_id,
				'class'   => 'c-postThumb__cat',
			] );
		endif;
	?>
</div>
