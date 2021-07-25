<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_main_visual';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => 'メインビジュアル',
		'priority' => 10,
		'panel'    => 'swell_panel_top',
	]
);


// メインビジュアルの表示内容
Customizer::add(
	$section,
	'main_visual_type',
	[
		'classname'   => '-swell-mv',
		'label'       => 'メインビジュアルの表示内容',
		'type'        => 'radio',
		'description' => '画像が複数ある場合はスライダーになります。',
		'choices'     => [
			'none'    => __( 'Don\'t show', 'swell' ),
			'slider'  => __( 'Image', 'swell' ),
			'movie'   => __( 'Video', 'swell' ),
		],
	]
);

// ■ 表示設定
Customizer::big_title(
	$section,
	'mv_common',
	[
		'classname'   => 'swell-mv-common',
		'label'       => '表示設定',
		'description' => '※ 画像・動画のどちらでも有効な設定項目群です。',
	]
);

// 周りに余白をつける
Customizer::add(
	$section,
	'mv_on_margin',
	[
		'classname' => 'swell-mv-common',
		'label'     => '周りに余白をつける',
		'type'      => 'checkbox',
	]
);

// Scrollボタンを表示する
Customizer::add(
	$section,
	'mv_on_scroll',
	[
		'classname' => 'swell-mv-common',
		'label'     => 'Scrollボタンを表示する',
		'type'      => 'checkbox',
	]
);

// メインビジュアルの高さ設定
Customizer::add(
	$section,
	'mv_slide_size',
	[
		'classname'   => 'swell-mv-common',
		'label'       => 'メインビジュアルの高さ設定',
		'type'        => 'select',
		'choices'     => [
			'img'  => '画像・動画サイズのまま',
			'auto' => 'コンテンツに応じる',
			'set'  => '数値で指定する',
			'full' => 'ウィンドウサイズにフィットさせる',
		],
	]
);

// メインビジュアルの高さ（PC）
Customizer::add(
	$section,
	'mv_slide_height_pc',
	[
		'classname'   => 'swell-mv-common swell-mv-height',
		'description' => 'メインビジュアルの高さ（PC）',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// メインビジュアルの高さ（SP）
Customizer::add(
	$section,
	'mv_slide_height_sp',
	[
		'classname'   => 'swell-mv-common swell-mv-height',
		'description' => 'メインビジュアルの高さ（SP）',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// 画像（動画）の上に表示されるボタンの丸み
Customizer::add(
	$section,
	'mv_btn_radius',
	[
		'classname'   => 'swell-mv-common -radio-button',
		'label'       => '画像（動画）の上に表示されるボタンの丸み',
		// 'description' => '画像や動画の上に表示できるボタンの丸み',
		'type'        => 'radio',
		'choices'     => [
			'0'  => 'なし',
			'4'  => '少し丸める',
			'40' => '丸める',
		],
	]
);


// フィルター処理
Customizer::add(
	$section,
	'mv_img_filter',
	[
		'classname'   => 'swell-mv-common',
		'label'       => 'フィルター処理',
		'description' => 'メインビジュアルに適用するフィルター処理',
		'type'        => 'select',
		'choices'     => [
			'nofilter'        => 'なし',
			'filter-blur'     => 'ブラー',
			'filter-glay'     => 'グレースケール',
			'texture-dot'     => 'ドット',
			'texture-brushed' => 'ブラシ',
		],
	]
);

// オーバーレイカラー
Customizer::add(
	$section,
	'mv_overlay_color',
	[
		'classname'   => 'swell-mv-common',
		'label'       => 'オーバーレイカラー',
		'description' => 'メインビジュアルの画像・動画に被せるカラーレイヤー',
		'type'        => 'color',
	]
);

// オーバレイカラーの不透明度
Customizer::add(
	$section,
	'mv_overlay_opacity',
	[
		'classname'   => 'swell-mv-common',
		// 'label'       => 'オーバレイカラーの不透明度',
		'description' => 'オーバレイカラーの不透明度（CSSの opacity プロパティの値）',
		'type'        => 'number',
		'input_attrs' => [
			'step' => '0.1',
			'min'  => '0',
			'max'  => '1',
		],
	]
);

// 画像スライダー
require_once __DIR__ . '/mv/slider.php';

// 動画
require_once __DIR__ . '/mv/movie.php';
