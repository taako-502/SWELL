<?php
/**
 * 追加CSSと同じコードエディターを追加するコード
 */

// add_action( 'customize_register', function( WP_Customize_Manager $wp_customize ) {

// } );

// $wp_customize->add_setting( 'favorite_html' );
// $control = new WP_Customize_Code_Editor_Control( $wp_customize, 'favorite_html', array(
//         'label' => 'My Favorite HTML',
//         'code_type' => 'text/html',
//         'settings' => 'favorite_html',
//         'section' => 'title_tagline',
// ) );
// $wp_customize->add_control( $control );

// コードテスト
// Customizer::add( $section, 'favorite_html', [
// 	'label'       => 'コードテスト',
//     'type'        => 'code_editor',
//     'code_type'   => 'text/html',
// ], '\WP_Customize_Code_Editor_Control' );

// $custom_css_setting = new WP_Customize_Custom_CSS_Setting(
//     $this,
//     sprintf( 'custom_css[%s]', get_stylesheet() ),
//     array(
//         'capability' => 'edit_css',
//         'default'    => '',
//     )
// );
// $this->add_setting( $custom_css_setting );

$wp_customize->add_setting(
	'swell_code_test',
	[
		'default'    => '',
		'capability' => 'edit_css',
		'type'       => 'option',
	// 'sanitize_callback' => '',
	]
);

$wp_customize->add_control(
	new WP_Customize_Code_Editor_Control(
		$wp_customize,
		'swell_code_test',
		[
			'label'       => 'ブロックエディターで読み込むCSS',
			'section'     => 'custom_css_for_gutenberg',
			// 'settings'    => array( 'default' => $custom_css_setting->id ),
			'code_type'   => 'text/css',
			'input_attrs' => [
				'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
			],
		]
	)
);
