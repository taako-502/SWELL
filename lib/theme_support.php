<?php
namespace SWELL_Theme;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * add_theme_supports
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_theme' );
function setup_theme() {
	add_theme_support( 'menus' );
	add_theme_support( 'widgets' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'page', 'excerpt' ); // 固定ページでも抜粋文を使用可能にする

	// Gutenberg用
	add_theme_support( 'align-wide' ); // 画像の全幅表示などを可能に
	add_theme_support( 'disable-custom-font-sizes' ); // フォントサイズのピクセル指定を不可に

	// 16:9で保つ...？
	// add_theme_support( 'responsive-embeds' );

	// コメントエリアをHTML5のタグで出力
	add_theme_support( 'html5', [
		'comment-form',
		'comment-list',
		// 'navigation-widgets',
	] );

	// 5.5からの機能
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-units', 'px', 'vw', 'vh' );

	// フォントサイズ
	add_theme_support(
		'editor-font-sizes',
		[
			[
				'name'      => _x( 'Tiny', 'size', 'swell' ),
				'shortName' => 'XS',
				'size'      => 10,
				'slug'      => 'xs',
			],
			[
				'name'      => _x( 'Small', 'size', 'swell' ),
				'shortName' => 'S',
				'size'      => 12,
				'slug'      => 'small',
			],
			[
				'name'      => _x( 'Medium', 'size', 'swell' ),
				'shortName' => 'M',
				'size'      => 18,
				'slug'      => 'medium',
			],
			[
				'name'      => _x( 'Large', 'size', 'swell' ),
				'shortName' => 'L',
				'size'      => 20,
				'slug'      => 'large',
			],
			[
				'name'      => _x( 'Huge', 'size', 'swell' ),
				'shortName' => 'XL',
				'size'      => 28,
				'slug'      => 'huge',
			],
		]
	);

	$thin           = _x( 'Thin', 'col', 'swell' );
	$dark           = _x( 'Dark', 'col', 'swell' );
	$custom         = __( 'Custom', 'swell' );
	$palette_colors = [
		[
			'name'  => __( 'Main Color', 'swell' ),
			'slug'  => 'swl-main',
			'color' => 'var( --color_main )',
		],
		[
			'name'  => __( 'Main Color', 'swell' ) . '(' . $thin . ')',
			'slug'  => 'swl-main-thin',
			'color' => 'var( --color_main_thin )',
		],
		[
			'name'  => __( 'Gray' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'slug'  => 'swl-gray',
			'color' => 'var( --color_gray )',
		],
		[
			'name'  => __( 'White' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'slug'  => 'white',
			'color' => '#fff',
		],
		[
			'name'  => __( 'Black' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'slug'  => 'black',
			'color' => '#000',
		],
		[
			'name'  => $custom . '(' . $dark . '-01)',
			'slug'  => 'swl-deep-01',
			'color' => 'var(--color_deep01)',
		],
		[
			'name'  => $custom . '(' . $dark . '-02)',
			'slug'  => 'swl-deep-02',
			'color' => 'var(--color_deep02)',
		],
		[
			'name'  => $custom . '(' . $dark . '-03)',
			'slug'  => 'swl-deep-03',
			'color' => 'var(--color_deep03)',
		],
		[
			'name'  => $custom . '(' . $dark . '-04)',
			'slug'  => 'swl-deep-04',
			'color' => 'var(--color_deep04)',
		],
		[
			'name'  => $custom . '(' . $thin . '-01)',
			'slug'  => 'swl-pale-01',
			'color' => 'var(--color_pale01)',
		],
		[
			'name'  => $custom . '(' . $thin . '-02)',
			'slug'  => 'swl-pale-02',
			'color' => 'var(--color_pale02)',
		],
		[
			'name'  => $custom . '(' . $thin . '-03)',
			'slug'  => 'swl-pale-03',
			'color' => 'var(--color_pale03)',
		],
		[
			'name'  => $custom . '(' . $thin . '-04)',
			'slug'  => 'swl-pale-04',
			'color' => 'var(--color_pale04)',
		],
	];

	// カラーパレット
	add_theme_support( 'editor-color-palette', $palette_colors );

	// コアのブロックパターンを全部削除
	remove_theme_support( 'core-block-patterns' );

	// ブロックウィジェット機能停止
	remove_theme_support( 'widgets-block-editor' );
}
