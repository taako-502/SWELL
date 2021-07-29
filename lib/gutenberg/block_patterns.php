<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', __NAMESPACE__ . '\register_block_patterns' );
add_action( 'init', __NAMESPACE__ . '\register_custom_block_patterns' );


/**
 * ブロックパターンの登録
 */
function register_block_patterns() {

	if ( \SWELL_Theme::get_option( 'remove_patterns' ) ) return;

	// SWELLのパターンカテゴリーを登録
	register_block_pattern_category(
		'swell-patterns',
		[ 'label' => 'SWELL' ]
	);

	// 改行
	$n = "\n";

	// 共通項目
	$paragraph   = '<!-- wp:paragraph -->' . $n . '<p>' . __( 'This is a paragraph block. Please enter your text here.', 'swell' ) . '</p>' . $n . '<!-- /wp:paragraph -->';
	$big_text    = '<!-- wp:paragraph {"fontSize":"large"} -->' . $n . '<p class="has-large-font-size">' . __( 'Big Text', 'swell' ) . '</p>' . $n . '<!-- /wp:paragraph -->';
	$triple_list = '<!-- wp:list -->' . $n . '<ul><li>' . __( 'List', 'swell' ) . '</li><li>' . __( 'List', 'swell' ) . '</li><li>' . __( 'List', 'swell' ) . '</li></ul>' . $n . '<!-- /wp:list -->';

	register_block_pattern(
		'swell-pattern/button-with-microcopy',
		[
			'title'         => __( 'Button with microcopy & icon code', 'swell' ),
			'content'       => '<!-- wp:paragraph {"align":"center","className":"u-mb-0 u-mb-ctrl","style":{"typography":{"lineHeight":"2"}}} -->' . $n . '<p class="has-text-align-center u-mb-0 u-mb-ctrl" style="line-height:2"><span class="swl-fz u-fz-s">' . __( '\ This is Button /', 'swell' ) . '</span></p>' . $n . '<!-- /wp:paragraph -->' . $n . $n . '<!-- wp:loos/button {"hrefUrl":"###","iconName":"icon-cart","className":"is-style-btn_normal blue_"} -->' . $n . '<div class="swell-block-button is-style-btn_normal blue_"><a href="###" class="swell-block-button__link" data-has-icon="1"><i class="icon-cart __icon"></i><span>' . __( 'Button', 'swell' ) . '</span></a></div>' . $n . '<!-- /wp:loos/button -->' . $n,
			'categories'    => ['swell-patterns' ],
			'description'   => '',
			'viewportWidth' => 480,
			'blockTypes'    => [ 'core/paragraph', 'loos/button' ],
		]
	);

	register_block_pattern(
		'swell-pattern/list-border',
		[
			'title'         => __( 'List with border', 'swell' ),
			'content'       => '<!-- wp:group {"className":"has-border -border01"} -->' . $n . '<div class="wp-block-group has-border -border01"><div class="wp-block-group__inner-container">' . $triple_list . '</div></div>' . $n . '<!-- /wp:group -->' . $n,
			'categories'    => ['swell-patterns' ],
			'description'   => '',
			'viewportWidth' => 480,
			'blockTypes'    => [ 'core/list', 'core/group' ],
		]
	);

	register_block_pattern(
		'swell-pattern/list-border-bg',
		[
			'title'         => __( 'List with light border and background', 'swell' ),
			'content'       => '<!-- wp:group {"className":"has-border -border04", "backgroundColor":"swl-pale-02"} -->' . $n . '<div class="wp-block-group has-border -border04  has-swl-pale-02-background-color has-background"><div class="wp-block-group__inner-container">' . $triple_list . '</div></div>' . $n . '<!-- /wp:group -->' . $n,
			'categories'    => ['swell-patterns' ],
			'description'   => '',
			'viewportWidth' => 560,
			'blockTypes'    => [ 'core/list', 'core/group' ],
		]
	);

	register_block_pattern(
		'swell-pattern/point-group',
		[
			'title'         => __( 'Point group', 'swell' ),
			'content'       => '<!-- wp:group {"className":"is-style-big_icon_point"} -->' . $n . '<div class="wp-block-group is-style-big_icon_point"><div class="wp-block-group__inner-container">' . $paragraph . $n . $triple_list . '</div></div>' . $n . '<!-- /wp:group -->' . $n,
			'categories'    => ['swell-patterns' ],
			'description'   => '',
			'viewportWidth' => 560,
			'blockTypes'    => [ 'core/list', 'core/group' ],
		]
	);

	$figure        = '<figure class="wp-block-media-text__media" style="background-image:url(https://s.w.org/images/core/5.3/MtBlanc1.jpg);background-position:50% 50%"><img src="https://s.w.org/images/core/5.3/MtBlanc1.jpg" alt=""/></figure>';
	$media_content = '<div class="wp-block-media-text__content">' . $big_text . $paragraph . '</div>';

	register_block_pattern(
		'swell-pattern/media-text-double-card',
		[
			'title'       => __( 'Card-style media and text', 'swell' ),
			'content'     => '<!-- wp:media-text {"mediaId":0,"mediaLink":"","mediaType":"image","imageFill":true,"className":"is-style-card"} -->' . $n . '<div class="wp-block-media-text alignwide is-stacked-on-mobile is-image-fill is-style-card">' . $figure . $media_content . '</div>' . $n . '<!-- /wp:media-text -->' . $n . '<!-- wp:media-text {"mediaPosition":"right", "mediaId":0,"mediaLink":"","mediaType":"image","imageFill":true,"className":"is-style-card"} -->' . $n . '<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-image-fill is-style-card">' . $figure . $media_content . '</div>' . $n . '<!-- /wp:media-text -->' . $n,
			'categories'  => ['swell-patterns' ],
			'description' => '',
		]
	);

	$figure = '<figure class="wp-block-media-text__media" style="background-image:url(https://s.w.org/images/core/5.3/Windbuchencom.jpg);background-position:50% 50%"><img src="https://s.w.org/images/core/5.3/Windbuchencom.jpg" alt=""/></figure>';

	$paragraph_r = '<!-- wp:paragraph {"align":"right"} -->' . $n . '<p class="has-text-align-right">' . __( 'This is a paragraph block. Please enter your text here.', 'swell' ) . '</p>' . $n . '<!-- /wp:paragraph -->';
	$big_text_r  = '<!-- wp:paragraph {"align":"right", "fontSize":"large"} -->' . $n . '<p class="has-text-align-right has-large-font-size">' . __( 'Big Text', 'swell' ) . '</p>' . $n . '<!-- /wp:paragraph -->';

	$media_content   = '<div class="wp-block-media-text__content"><!-- wp:group {"backgroundColor":"white"} -->' . $n . '<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">' . $big_text . $paragraph . '</div></div>' . $n . '<!-- /wp:group --></div>';
	$media_content_r = '<div class="wp-block-media-text__content"><!-- wp:group {"backgroundColor":"white"} -->' . $n . '<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">' . $big_text_r . $paragraph_r . '</div></div>' . $n . '<!-- /wp:group --></div>';

	register_block_pattern(
		'swell-pattern/media-text-double-broken',
		[
			'title'       => __( 'Broken media and text', 'swell' ),
			'content'     => '<!-- wp:media-text {"mediaId":0,"mediaLink":"","mediaType":"image","imageFill":true,"className":"is-style-broken"} -->' . $n . '<div class="wp-block-media-text alignwide is-stacked-on-mobile is-image-fill is-style-broken">' . $figure . $media_content . '</div>' . $n . '<!-- /wp:media-text -->' . $n . '<!-- wp:media-text {"mediaPosition":"right", "mediaId":0,"mediaLink":"","mediaType":"image","imageFill":true,"className":"is-style-broken"} -->' . $n . '<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-image-fill is-style-broken">' . $figure . $media_content_r . '</div>' . $n . '<!-- /wp:media-text -->' . $n,
			'categories'  => ['swell-patterns' ],
			'description' => '',
		]
	);

	register_block_pattern(
		'swell-pattern/full-wide-section',
		[
			'title'       => __( 'Full wide section', 'swell' ),
			'content'     => '<!-- wp:loos/full-wide -->' . $n . '<div class="swell-block-fullWide pc-py-60 sp-py-40 alignfull" style="background-color:#f7f7f7"><div class="swell-block-fullWide__inner l-article"><!-- wp:heading {"className":"is-style-section_ttl"} -->' . $n . '<h2 class="is-style-section_ttl">' . __( 'SECTION', 'swell' ) . '<small class="mininote">section</small></h2>' . $n . '<!-- /wp:heading -->' . $n . $n . $paragraph . '</div></div>' . $n . '<!-- /wp:loos/full-wide -->' . $n,
			'categories'  => ['swell-patterns' ],
			'description' => '',
		]
	);
}

/**
 * ブロックパターンの登録
 */
function register_custom_block_patterns() {

	// SWELLのカスタムパターンカテゴリーを登録
	register_block_pattern_category(
		'swell-custom-patterns',
		[ 'label' => 'SWELL custom patterns' ]
	);

	$args = [
		'post_type'              => 'blog_parts',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'posts_per_page'         => -1,
		'tax_query'              => [
			[
				'taxonomy' => 'parts_use',
				'field'    => 'slug',
				'terms'    => 'pattern',
				'operator' => 'AND',
			],
		],
	];

	$the_query = new \WP_Query( $args );
	foreach ( $the_query->posts as $parts ) :
		register_block_pattern( 'swell-patterns/parts-' . $parts->ID, [
			'title'       => $parts->post_title,
			'content'     => $parts->post_content,
			'categories'  => [ 'swell-custom-patterns' ],
		] );
	endforeach;
	wp_reset_postdata();
}
