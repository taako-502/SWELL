<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// phpcs:disable WordPress.WP.I18n.MissingArgDomain

$wp_customize->add_panel( 'swl_panel_wordpress', [
	'title'    => __( 'WordPress Settings', 'swell' ),
	'priority' => 1,
] );
$wp_customize->add_section( 'title_tagline', [
	'title'    => __( 'Site Identity' ),
	'priority' => 1,
	'panel'    => 'swl_panel_wordpress',
] );
$wp_customize->add_section( 'static_front_page', [
	'title'    => __( 'Homepage Settings' ),
	'priority' => 1,
	'panel'    => 'swl_panel_wordpress',
] );
