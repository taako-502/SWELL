<?php
namespace SWELL_Theme\Updated_Action;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * バージョン更新時の処理
 */
add_action( 'init', '\SWELL_Theme\Updated_Action\updated_hook', 1 );
function updated_hook() {

	$now_ver  = \SWELL_Theme::$swell_version;
	$old_ver  = get_option( 'swell_version' ); // データベースに保存されているバージョン
	$free_ver = get_option( 'swell_free_version' );

	if ( $free_ver ) {
		// 無料版からの移行であれば
		delete_option( 'swell_free_version' );
		$old_ver = $free_ver;
	}

	// まだバージョン情報が記憶されていなければ
	if ( false === $old_ver ) {

		update_option( 'swell_version', $now_ver ); // 現在のバージョンをDBに記憶
		\SWELL_Theme\Updated_Action\all_cache_delete();

	} elseif ( $old_ver !== $now_ver ) {

		// バージョンが更新されていれば、アップデート時に一度だけ処理

		update_option( 'swell_version', $now_ver ); // 現在のバージョンをDBに記憶

		// バージョンアップ時の処理
		\SWELL_Theme\Updated_Action\all_cache_delete();
		\SWELL_Theme\Updated_Action\rewrite_settings();
	}
}


/**
 * SWELL用 transientキャッシュをすべて削除
 */
function all_cache_delete() {
	// \SWELL_Theme::clear_cache( [] );
	\SWELL_Theme::clear_all_parts_cache_by_wpdb(); // 念のため、query回して削除
}


/**
 * カスタマイザーの設定値を書き換える
 */
function rewrite_settings() {

	$is_change = false;
	$settings  = get_option( \SWELL_Theme::DB_NAME_CUSTOMIZER ) ?: [];

	// 完全にキーを切り替えるパターン
	$change_keys = [
		'pos_info_bar'      => 'info_bar_pos',
		'notice_bar_effect' => 'info_bar_effect',
		'flow_info_bar'     => 'info_flowing',
		'color_notice_text' => 'color_info_text',
		'color_notice_bg'   => 'color_info_bg',
		'color_notice_bg2'  => 'color_info_bg2',
		'color_notice_btn'  => 'color_info_btn',
	];
	foreach ( $change_keys as $old_key => $new_key ) {
		// 新しいキーがまだなく、古いキーでデータを持つ時。
		if ( ! isset( $settings[ $new_key ] ) && isset( $settings[ $old_key ] ) ) {
			$settings[ $new_key ] = $settings[ $old_key ];
			unset( $settings[ $old_key ] );
			$is_change = true;
		}
	}

	// １つの設定を2つに切り分けるパターン
	$change_keys = [
		'page_title_style' => 'archive_title_style',
	];
	foreach ( $change_keys as $old_key => $new_key ) {
		// 新しいキーがまだなく、古いキーでデータを持つ時。 (unsetはしない)
		if ( ! isset( $settings[ $new_key ] ) && isset( $settings[ $old_key ] ) ) {
			$settings[ $new_key ] = $settings[ $old_key ];
			$is_change            = true;
		}
	}

	// 2.3.9
	if ( isset( $settings['frame_only_post'] ) && $settings['frame_only_post'] ) {
		$settings['frame_scope'] = 'post_page';
		unset( $settings['frame_only_post'] );
		$is_change = true;
	}

	// 変更があれば、設定を更新
	if ( $is_change ) {
		update_option( \SWELL_Theme::DB_NAME_CUSTOMIZER, $settings );
	}

	// 2.0.3
	\SWELL_Theme\Updated_Action\moving_db_to_options();

	// 2.0.5
	\SWELL_Theme\Updated_Action\moving_db_to_editors();

	\SWELL_Theme\Updated_Action\change_db_in_options();
}


/**
 * カスタマイザーデータの一部を options へ
 */
function moving_db_to_options() {

	$is_change = false;
	$settings  = get_option( \SWELL_Theme::DB_NAME_CUSTOMIZER ) ?: [];
	$options   = get_option( \SWELL_Theme::DB_NAME_OPTIONS ) ?: [];

	// 移動させたいキー
	$move_keys = \SWELL_Theme::get_default_option();

	// カスタマイザーで既に設定している場合、それを options へ移動。
	foreach ( $move_keys as $key => $value ) {

		// $options にはまだデータがなくて、かつ $settings に残っている時
		if ( isset( $settings[ $key ] ) && ! isset( $options[ $key ] ) ) {
			$options[ $key ] = $settings[ $key ];
			unset( $settings[ $key ] );
			$is_change = true;
		}
	}

	// 2.1.9
	if ( isset( $settings['use_luminous'] ) && ! isset( $options['remove_luminous'] ) ) {
		if ( $settings['use_luminous'] ) {
			$options['remove_luminous'] = '';
		} else {
			$options['remove_luminous'] = '1';
		}
		unset( $settings['use_luminous'] );
		$is_change = true;
	}

	if ( ! $is_change ) return;
	update_option( \SWELL_Theme::DB_NAME_OPTIONS, $options );
	update_option( \SWELL_Theme::DB_NAME_CUSTOMIZER, $settings );
}


/**
 * カスタマイザーデータの一部を editors へ
 */
function moving_db_to_editors() {

	$is_change = false;
	$settings  = get_option( \SWELL_Theme::DB_NAME_CUSTOMIZER ) ?: [];
	$editors   = get_option( \SWELL_Theme::DB_NAME_EDITORS ) ?: [];

	$switch_keys = [
		'color_font_red'     => 'color_deep01',
		'color_font_blue'    => 'color_deep02',
		'color_font_green'   => 'color_deep03',
		'color_cap_01'       => '',
		'color_cap_01_light' => '',
		'color_cap_02'       => '',
		'color_cap_02_light' => '',
		'color_cap_03'       => '',
		'color_cap_03_light' => '',
		'iconbox_type'       => '',
		'iconbox_s_type'     => '',
		'blockquote_type'    => '',

		// 2.0.7
		'marker_type'        => '',
		'color_mark_blue'    => '',
		'color_mark_green'   => '',
		'color_mark_yellow'  => '',
		'color_mark_orange'  => '',

		'color_btn_red'      => '',
		'color_btn_red2'     => '',
		'color_btn_blue'     => '',
		'color_btn_blue2'    => '',
		'color_btn_green'    => '',
		'color_btn_green2'   => '',
		'btn_radius_normal'  => '',
		'btn_radius_solid'   => '',
		'btn_radius_shiny'   => '',
		'btn_radius_flat'    => 'btn_radius_line',
		'is_btn_gradation'   => '',
	];

	foreach ( $switch_keys as $old_key => $new_key ) {

		$new_key = $new_key ?: $old_key; // new key の指定がなければ同じキーで移動させる

		// $editors にはまだデータがなくて、かつ $settings に残っている時
		if ( isset( $settings[ $old_key ] ) && ! isset( $editors[ $new_key ] ) ) {
			$editors[ $new_key ] = $settings[ $old_key ];
			unset( $settings[ $old_key ] );
			$is_change = true;
		}
	}

	if ( ! $is_change ) return;
	update_option( \SWELL_Theme::DB_NAME_EDITORS, $editors );
	update_option( \SWELL_Theme::DB_NAME_CUSTOMIZER, $settings );
}

/**
 * options のデータを変更する
 */
function change_db_in_options() {
	$is_change = false;
	$options   = get_option( \SWELL_Theme::DB_NAME_OPTIONS ) ?: [];

	// 2.2.0
	if ( isset( $options['use_lazyload'] ) ) {
		$now_option = $options['use_lazyload'];
		if ( '1' === $now_option ) {
			$options['use_lazyload'] = 'swell';
		} elseif ( '' === $now_option ) {
			$options['use_lazyload'] = 'core';
		}
		$is_change = true;
	}

	if ( $is_change ) {
		update_option( \SWELL_Theme::DB_NAME_OPTIONS, $options );
	}
}
