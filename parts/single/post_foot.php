<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * $variable : $post_id
 */
$post_id  = $variable ?: get_the_ID();
$cat_list = \SWELL_FUNC::get_the_term_links( $post_id, 'cat' );
$tag_list = \SWELL_FUNC::get_the_term_links( $post_id, 'tag' );
?>
<div class="p-articleFoot">
	<div class="p-articleMetas -bottom">
		<?php if ( $cat_list ) : ?>
			<div class="p-articleMetas__termList c-categoryList"><?=$cat_list?></div>
		<?php endif; ?>
		<?php if ( $tag_list ) : ?>
			<div class="p-articleMetas__termList c-tagList"><?=$tag_list?></div>
		<?php endif; ?> 
	</div>
</div>
