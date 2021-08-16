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
			<?php
				$term_thumb_id = attachment_url_to_postid( $term_thumb ) ?: 0;
				\SWELL_Theme::get_image( $term_thumb_id, [
					'class' => 'p-termHead__thumbImg u-obf-cover', // obfはdescription長い時用
					'echo'  => true,
				]);
			?>
		</figure>
	<?php endif; ?>
	<?php if ( $description ) : ?>
		<div class="p-termHead__desc">
			<?=wp_kses( do_shortcode( nl2br( $description ) ), SWELL_Theme::$allowed_text_html )?>
		</div>
	<?php endif; ?>
</div>
