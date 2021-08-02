<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ブロックカテゴリー追加
 * // ver5.8.0~: block_categories_all
 */
$hookname = \SWELL_Theme::wpver_is_above( '5.8' ) ? 'block_categories_all' : 'block_categories';
add_filter( $hookname, __NAMESPACE__ . '\register_swell_categories', 5 );
function register_swell_categories( $categories ) {
	$my_category = [
		[
			'slug'  => 'swell-blocks',  // ブロックカテゴリーのスラッグ
			'title' => __( 'SWELL Blocks', 'swell' ),   // ブロックカテゴリーの表示名
			'icon'  => null,
		],
	];
	array_splice( $categories, 3, 0, $my_category );
	return $categories;
}


/**
 * ダイナミックブロックを登録
 */
add_action( 'init', __NAMESPACE__ . '\register_swell_blocks' );
function register_swell_blocks() {
	register_normal_blocks();
	register_dynamic_blocks();
}


/**
 * 普通のカスタムブロックの登録
 */
function register_normal_blocks() {
	$blocks = [
		'ab-test',
		'accordion',
		'accordion-item',
		'banner-link',
		'full-wide',
		'tab',
		'tab-body',
	];

	foreach ( $blocks as $block_name ) {

		$handle = "swell-block/{$block_name}";
		$asset  = include T_DIRE . "/build/blocks/{$block_name}/index.asset.php";

		wp_register_script(
			$handle,
			T_DIRE_URI . "/build/blocks/{$block_name}/index.js",
			array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
			SWELL_VERSION,
			true
		);

		register_block_type( "loos/{$block_name}", [ 'editor_script' => $handle ] );
	}

	// リファクタリング済み
	$blocks = [
		'button',
		'cap-block',
		'dl',
		'dl-dt',
		'dl-dd',
		'faq',
		'faq-item',
		'step',
		'step-item',
	];
	foreach ( $blocks as $block_name ) {

		$handle = "swell-block/{$block_name}";
		$asset  = include T_DIRE . "/build/blocks/{$block_name}/index.asset.php";

		wp_register_script(
			$handle,
			T_DIRE_URI . "/build/blocks/{$block_name}/index.js",
			array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
			SWELL_VERSION,
			true
		);

		register_block_type_from_metadata(
			T_DIRE . "/src/gutenberg/blocks/{$block_name}",
			[
				'editor_script' => $handle,
			]
		);
	}

}


/**
 * ダイナミックブロックの登録
 */
function register_dynamic_blocks() {
	$dynamic_blocks = [
		'ab-test',
		'ad-tag',
		'balloon',
		'blog-parts',
		'post-list',
		'post-link',
		'rss',
	];
	foreach ( $dynamic_blocks as $block_name ) {
		require_once __DIR__ . "/block/{$block_name}.php";
	}
}
