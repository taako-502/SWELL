<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;


/**
 * 記事リストのカテゴリー
 */
if ( ! function_exists( 'swl_parts__post_list_category' ) ) :
	function swl_parts__post_list_category( $args ) {
		$the_id   = $args['post_id'] ?? get_the_ID();
		$class    = $args['class'] ?? 'p-postList__cat u-thin';
		$cat_data = get_the_category( $the_id );
		if ( empty( $cat_data ) ) {
			return;
		}

		$the_cat = apply_filters( 'swell_post_list_cat_data', [
			'id'   => $cat_data[0]->term_id,
			'name' => $cat_data[0]->name,
		], $the_id );
	?>
		<span class="<?=esc_attr( $class )?> icon-folder" data-cat-id="<?=esc_attr( $the_cat['id'] )?>"><?=esc_html( $the_cat['name'] )?></span>
	<?php
}
endif;


/**
 * 記事リストに表示するPV
 */
if ( ! function_exists( 'swl_parts__post_list_pv' ) ) :
	function swl_parts__post_list_pv( $args ) {
		$the_id = $args['post_id'] ?? get_the_ID();
		$pv     = get_post_meta( $the_id, SWELL_CT_KEY, true ) ?: '0';
	?>
		<span class="p-postList__views icon-eye u-thin"><?=esc_html( $pv )?></span>
	<?php
}
endif;


/**
 * 投稿リストの著者アイコンを取得する
 */
if ( ! function_exists( 'swl_parts__post_list_author' ) ) :
	function swl_parts__post_list_author( $args ) {
	// $author_id = 0, $add_class = '', $is_link = false )

		$author_id   = $args['author_id'] ?? 0;
		$author_data = wp_cache_get( 'author_data_' . $author_id, 'swell_post_list' );
		if ( ! $author_data ) {
			$author_data = SWELL::get_author_data( $author_id );
		}

		if ( empty( $author_data ) ) return;

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
		<div class="c-postAuthor p-postList__author">
			<figure class="c-postAuthor__figure"><?=$author_data['avatar']?></figure>
			<span class="c-postAuthor__name u-thin"><?=esc_html( $author_data['name'] )?></span>
		</div>
	<?php
	// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;
