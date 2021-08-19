<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$the_id   = get_the_ID();
$show_cat = $variable['show_cat'] ?? true;
$show_tag = $variable['show_tag'] ?? true;
$cat_data = $show_cat ? SWELL_Theme::get_the_terms_data( $the_id, 'category' ) : null;
$tag_data = $show_tag ? SWELL_Theme::get_the_terms_data( $the_id, 'post_tag' ) : null;

?>
<?php if ( ! empty( $cat_data ) ) : ?>
	<div class="p-articleMetas__termList c-categoryList">
		<?php foreach ( $cat_data as $data ) : ?>
			<a class="c-categoryList__link hov-flash-up" href="<?=esc_url( $data['url'] )?>" data-cat-id="<?=esc_attr( $data['id'] )?>">
				<?=esc_html( $data['name'] )?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if ( ! empty( $tag_data ) ) : ?>
	<div class="p-articleMetas__termList c-tagList">
		<?php foreach ( $tag_data as $data ) : ?>
			<a class="c-tagList__link hov-flash-up" href="<?=esc_url( $data['url'] )?>" data-tag-id="<?=esc_attr( $data['id'] )?>">
				<?=esc_html( $data['name'] )?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
