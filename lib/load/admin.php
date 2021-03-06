<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 管理画面で読み込むファイル
 */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\hook_admin_enqueue_scripts' );
function hook_admin_enqueue_scripts( $hook_suffix ) {

	global $post_type;
	$assets = T_DIRE_URI . '/assets';
	$build  = T_DIRE_URI . '/build';

	// グーロバル変数はhead内で渡しておきたい
	wp_register_script( 'swell_vars', false ); // phpcs:ignore
	wp_enqueue_script( 'swell_vars' );

	// 管理画面側に渡すグローバル変数
	wp_localize_script( 'swell_vars', 'swellVars', global_vars_on_admin() );

	// メディアアップローダー用
	wp_enqueue_media();
	wp_enqueue_script( 'mediauploader', $build . '/js/admin/mediauploader.min.js', ['jquery' ], SWELL_VERSION, true );

	// カラーピッカー
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	// 管理画面用CSS（共通）
	wp_enqueue_style( 'swell_admin_style', $assets . '/css/admin.css', [], SWELL_VERSION );

	// 管理画面用JS（共通）
	wp_enqueue_script( 'swell_admin_script', $build . '/js/admin/admin_script.min.js', [], SWELL_VERSION, true );

	// ページの種類で分岐
	if ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix ) {

		// タイトルカウント
		wp_enqueue_script( 'swell_title_count', $build . '/js/admin/count_title.min.js', ['jquery' ], SWELL_VERSION, true );

	} elseif ( 'widgets.php' === $hook_suffix && ! \SWELL_Theme::use_widgets_block() ) {

		// カラーピッカー
		wp_enqueue_script( 'swell_colorpicker', $build . '/js/admin/colorpicker.min.js', [ 'wp-color-picker' ], SWELL_VERSION, true );

	} elseif ( strpos( $hook_suffix, 'swell_settings' ) !== false ) {

		// カラーピッカー
		wp_enqueue_script( 'swell_colorpicker', $build . '/js/admin/colorpicker.min.js', [ 'wp-color-picker' ], SWELL_VERSION, true );

		// 設定画面用ファイル
		wp_enqueue_style( 'swell_settings_css', $assets . '/css/admin/settings.css', [], SWELL_VERSION );
		wp_enqueue_script( 'swell_settings_js', $build . '/js/admin/settings.min.js', ['jquery' ], SWELL_VERSION, false );

		// codemirror
		// see: https://codemirror.net/doc/manual.html#config
		$codemirror = [
			'tabSize'           => 4,
			'indentUnit'        => 4,
			'indentWithTabs'    => true,
			'inputStyle'        => 'contenteditable',
			'lineNumbers'       => true,
			'smartIndent'       => true,
			'lineWrapping'      => true, // 横長のコードを折り返すかどうか
			'autoCloseBrackets' => true,
			'styleActiveLine'   => true,
			'continueComments'  => true,
			// 'extraKeys'         => [],
		];

		$settings = wp_enqueue_code_editor( [
			'type'       => 'text/css',
			'codemirror' => $codemirror,
		] );

		wp_localize_script( 'wp-theme-plugin-editor', 'codeEditorSettings', $settings );
		wp_enqueue_script( 'wp-theme-plugin-editor' );
		wp_add_inline_script(
			'wp-theme-plugin-editor',
			'jQuery(document).ready(function($) {
				var swellCssEditor = $(".swell-css-editor");
				if(swellCssEditor.length < 1) return;
				wp.codeEditor.initialize($(".swell-css-editor"), codeEditorSettings );
			})'
		);
		wp_enqueue_style( 'wp-codemirror' );
	}

	// 投稿タイプで分岐
	if ( 'ad_tag' === $post_type ) {
		wp_enqueue_style( 'swell_ad_css', $assets . '/css/admin/ad_tag.css', [], SWELL_VERSION );
		wp_enqueue_script( 'swell_ad_js', $build . '/js/admin/ad_tag.min.js', ['jquery' ], SWELL_VERSION, false );
	} elseif ( 'speech_balloon' === $post_type ) {
		wp_enqueue_style( 'swell_balloon_css', $assets . '/css/admin/balloon.css', [], SWELL_VERSION );
		wp_enqueue_script( 'swell_balloon_js', $build . '/js/admin/balloon.min.js', ['jquery' ], SWELL_VERSION, false );
	}
}


/**
 * 管理画面用のJSグローバル変数 ( swellVars にセットする)
 */
function global_vars_on_admin() {

	$global_vars = [
		// 'homeUrl' => home_url( '/' ),
		'restUrl'   => rest_url() . 'wp/v2/',
		'adminUrl'  => admin_url(),
		'direUri'   => T_DIRE_URI,
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'ajaxNonce' => wp_create_nonce( 'swell-ajax-nonce' ),
	];

	// カスタム書式
	$custom_formats = [];
	for ( $i = 1; $i < 3; $i++ ) {
		$format_title = \SWELL_Theme::get_editor( 'format_title_' . $i );
		if ( $format_title ) {
			$custom_formats[] = [
				'name'      => 'loos/custom-format' . $i,
				'title'     => $format_title,
				'tagName'   => 'span',
				'className' => 'swl-format-' . $i,
			];
		}
	}

	if ( $custom_formats ) {
		$global_vars['customFormats'] = apply_filters( 'swell_custom_formats', $custom_formats );
	}

	// カスタム書式セット
	$custom_format_sets = [];
	$editor             = \SWELL_Theme::get_editor();

	for ( $i = 1; $i < 3; $i++ ) {
		$format_set = [];

		$format_bold      = $editor[ 'format_set_bold_' . $i ];
		$format_italic    = $editor[ 'format_set_italic_' . $i ];
		$format_color     = $editor[ 'format_set_color_' . $i ];
		$format_bg        = $editor[ 'format_set_bg_' . $i ];
		$format_marker    = $editor[ 'format_set_marker_' . $i ];
		$format_font_size = $editor[ 'format_set_font_size_' . $i ];

		if ( $format_bg ) {
			if ( $format_bg === 'white' || $format_bg === 'black' ) {
				$color_slug = $format_bg;
			} else {
				$color_slug = 'swl-' . $format_bg;
			}

			$format_set[] = [
				'type'       => 'loos/bg-color',
				'attributes' => [
					'class' => "swl-bg-color has-{$color_slug}-background-color",
				],
			];
		}

		if ( $format_marker ) {
			$format_set[] = [
				'type'       => 'loos/marker',
				'attributes' => [
					'class' => "mark_{$format_marker}",
				],
			];
		}

		if ( $format_color ) {
			if ( $format_color === 'white' || $format_color === 'black' ) {
				$color_slug = $format_bg;
			} else {
				$color_slug = 'swl-' . $format_color;
			}

			$format_set[] = [
				'type'       => 'core/text-color',
				'attributes' => [
					'class' => "has-inline-color has-{$color_slug}-color",
				],
			];
		}

		if ( $format_font_size ) {
			$format_set[] = [
				'type'       => 'loos/font-size',
				'attributes' => [
					'class' => "u-fz-{$format_font_size}",
				],
			];
		}

		if ( $format_bold ) {
			$format_set[] = [
				'type'      => 'core/bold',
			];
		}

		if ( $format_italic ) {
			$format_set[] = [
				'type'      => 'core/italic',
			];
		}

		if ( $format_set ) {
			$custom_format_sets[] = $format_set;
		}
	}

	if ( $custom_format_sets ) {
		$global_vars['customFormatSets'] = apply_filters( 'swell_custom_format_sets', $custom_format_sets );
	}

	return $global_vars;
}
