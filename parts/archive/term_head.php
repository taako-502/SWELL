<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * タームアーカイブのheadコンテンツ
 */
$term_id     = $variable['term_id'] ?? 0;
$description = $variable['description'] ?? '';


// タームメタ
$is_show_thumb = get_term_meta( $term_id, 'swell_term_meta_show_thumb', 1 );
$is_show_desc  = get_term_meta( $term_id, 'swell_term_meta_show_desc', 1 );
$is_show_thumb = ( '1' === $is_show_thumb );  // 標準：オフ
$is_show_desc  = ( '0' !== $is_show_desc );   // 標準：オン

$term_thumb  = $is_show_thumb ? get_term_meta( $term_id, 'swell_term_meta_image', 1 ) : '';
$description = $is_show_desc ? $description : '';

if ( ! $term_thumb && ! $description ) return '';

?>
<div class="p-termHead">
	<?php if ( $term_thumb ) : ?>
		<figure class="p-termHead__thumbWrap">
			<img src="<?=esc_url( \SWELL_Theme::$placeholder )?>" data-src="<?=esc_url( $term_thumb )?>" alt="" class="p-termHead__thumbImg lazyload">
		</figure>
	<?php endif; ?>
	<?php if ( $description ) : ?>
		<div class="p-termHead__desc"><?=do_shortcode( $description )?></div>
	<?php endif; ?>
</div>
