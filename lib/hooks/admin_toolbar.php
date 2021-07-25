<?php
namespace SWELL_Theme\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'admin_bar_menu', __NAMESPACE__ . '\hook_admin_bar_menu', 99 );
function hook_admin_bar_menu( $wp_admin_bar ) {
	// 「カスタマイズ」
	if ( is_admin() ) {
		$wp_admin_bar->add_menu(
			[
				'id'    => 'customize',
				'title' => '<span class="ab-icon"></span><span class="ab-label">カスタマイズ</span>',
				'href'  => admin_url( 'customize.php' ),
			]
		);
	}

	// 親メニュー
	$wp_admin_bar->add_menu(
		[
			'id'    => 'swell_settings',
			'title' => '<span class="ab-icon icon-swell u-fz-16"></span><span class="ab-label">' . __( 'SWELL Settings', 'swell' ) . '</span>',
			'href'  => admin_url( 'admin.php?page=swell_settings' ),
			'meta'  => [
				'class' => 'swell-menu',
			],
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings_menu',
			'meta'   => [],
			'title'  => __( 'To setting page', 'swell' ),
			'href'   => admin_url( 'admin.php?page=swell_settings' ),
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings_editor_menu',
			'meta'   => [],
			'title'  => __( 'Editor Settings', 'swell' ),
			'href'   => admin_url( 'admin.php?page=swell_settings_editor' ),
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings_manual_link',
			'meta'   => ['target' => '_blank' ],
			'title'  => __( 'Manual', 'swell' ),
			'href'   => 'https://swell-theme.com/manual/',
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings_forum_link',
			'meta'   => ['target' => '_blank' ],
			'title'  => __( 'Forum', 'swell' ),
			'href'   => 'https://u.swell-theme.com/community/',
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings_icon_demo',
			'meta'   => ['target' => '_blank' ],
			'title'  => __( 'List of icons', 'swell' ),
			'href'   => 'https://swell-theme.com/icon-demo/',
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings__clear_cache',
			'meta'   => [],
			'title'  => __( 'Cache clear', 'swell' ) . ' (' . __( 'Content', 'swell' ) . ')',
			'href'   => '###',
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => 'swell_settings',
			'id'     => 'swell_settings__clear_card_cache',
			'meta'   => [],
			'title'  => __( 'Cache clear', 'swell' ) . ' (' . __( 'Blog card', 'swell' ) . ')',
			'href'   => '###',
		]
	);
}
