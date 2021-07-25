<?php
namespace SWELL_Theme\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', __NAMESPACE__ . '\add_parts_tax' );
add_action( 'current_screen', __NAMESPACE__ . '\set_parts_tax_terms' );


/**
 * 投稿タイプの登録
 */
function add_parts_tax() {
	$tax = __( 'Use', 'swell' );
	register_taxonomy(
		'parts_use',
		[ 'blog_parts' ],
		[
			'public'             => false,
			'hierarchical'       => true,
			'labels'             => [
				'name'                => $tax,
				'singular_name'       => $tax,
				'search_items'        => sprintf( __( 'Search %s', 'swell' ), $tax ),
				'all_items'           => sprintf( __( 'All %s', 'swell' ), $tax ),
				'parent_item'         => sprintf( __( 'Parent %s', 'swell' ), $tax ),
				'parent_item_colon'   => sprintf( __( 'Parent %s', 'swell' ), $tax ) . ':',
				'edit_item'           => sprintf( __( 'Edit %s', 'swell' ), $tax ),
				'update_item'         => sprintf( __( 'Update %s', 'swell' ), $tax ),
				'add_new_item'        => sprintf( __( 'Add New %s', 'swell' ), $tax ),
				'new_item_name'       => sprintf( __( 'New %s', 'swell' ), $tax ),
				'menu_name'           => $tax,
			],
			'show_ui'            => true,
			// 'show_in_nav_menus'  => false,
			// 'show_in_quick_edit' => false,
			'capabilities'       => [
				// 'manage_terms' => false,
				'edit_terms'   => false,
				'delete_terms' => false,
				// 'assign_terms' => false, // 投稿画面で設定できる権限
			],
			'show_admin_column'  => true,
			'query_var'          => true,
			'show_in_rest'       => true,
			'rewrite'            => [ 'slug' => 'parts_use' ],
		]
	);
}


/**
 * パーツタグのタームを登録
 */
function set_parts_tax_terms( $current_screen ) {

	if ( 'blog_parts' !== $current_screen->post_type ) return;

	$tags = [
		'pattern'   => __( 'Block Pattern', 'swell' ),
		'for_cat'   => __( 'For category', 'swell' ),
		'for_tag'   => __( 'For tag', 'swell' ),
		'cta'       => 'CTA',
	];

	foreach ( $tags as $slug => $name ) {
		$the_term = get_term_by( 'slug', $slug, 'parts_use' );
		if ( false === $the_term ) {
			wp_insert_term( $name, 'parts_use', ['slug' => $slug ] );
		} elseif ( $name !== $the_term->name ) {
			$new_data = [
				'slug' => $slug,
				'name' => $name,
			];
			wp_update_term( $the_term->term_id, 'parts_use', $new_data );
		}
	}
}
