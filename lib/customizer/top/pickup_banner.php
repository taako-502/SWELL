<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_pickup_banner';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'       => 'ピックアップバナー',
		'description' => '位置設定を「ピックアップバナー」に設定しているメニューがある場合にのみ有効です。',
		'priority'    => 10,
		'panel'       => 'swell_panel_top',
	]
);


// ■ バナーレイアウト
Customizer::big_title(
	$section,
	'pickup_layout',
	[
		'label' => 'バナーレイアウト',
	]
);

// バナーレイアウト（PC）
Customizer::add(
	$section,
	'pickbnr_layout_pc',
	[
		'label'   => 'バナーレイアウト（PC）',
		'type'    => 'select',
		'choices' => [
			'fix_col4' => '固定幅 4列',
			'fix_col3' => '固定幅 3列',
			'fix_col2' => '固定幅 2列',
			'flex'     => 'フレックス（横一列に全て並べる）',
		],
	]
);

// バナーレイアウト（SP）
Customizer::add(
	$section,
	'pickbnr_layout_sp',
	[
		'label'   => 'バナーレイアウト（SP）',
		'type'    => 'select',
		'choices' => [
			'fix_col2' => '固定幅 2列',
			'fix_col1' => '固定幅 1列',
			'slide'    => 'スライド（横スクロール可能に）',
		],
	]
);


// ■ バナーデザイン
Customizer::big_title(
	$section,
	'pickup_style',
	[
		'label' => 'バナーデザイン',
	]
);

// バナータイトルのデザイン
Customizer::add(
	$section,
	'pickbnr_style',
	[
		'label'   => 'バナータイトルのデザイン',
		'type'    => 'select',
		'choices' => [
			'none'       => __( 'Don\'t show', 'swell' ),
			'top_left'   => '左上に表示',
			'btm_right'  => '右下に表示',
			'ctr_simple' => '中央（シンプル）',
			'ctr_button' => '中央（ボタン風）',
			'btm_wide'   => '下にワイド表示',
		],
	]
);

// 内側に白線を
Customizer::add(
	$section,
	'pickbnr_border',
	[
		'label'   => '内側に白線を',
		'type'    => 'radio',
		'choices' => [
			'off' => 'つけない',
			'on'  => 'つける',
		],
	]
);

// バナー画像を少し暗く
Customizer::add(
	$section,
	'pickbnr_bgblack',
	[
		'label'   => 'バナー画像を少し暗く',
		'type'    => 'radio',
		'choices' => [
			'off' => 'しない',
			'on'  => 'する',
		],
	]
);


// その他
Customizer::big_title(
	$section,
	'pickup_others',
	[
		'label' => 'その他',
	]
);

// トップページ以外の下層ページにも表示する
Customizer::add(
	$section,
	'pickbnr_show_under',
	[
		'label' => 'トップページ以外の下層ページにも表示する',
		'type'  => 'checkbox',
	]
);

Customizer::add(
	$section,
	'pickbnr_lazy_off',
	[
		'label'       => 'Lazyloadを強制オフにする',
		'type'        => 'checkbox',
		'description' => 'チェックを外すと、サイト全体（「SWELL設定」→「画像等のLazyload」）の設定に従います。',
	]
);

// Customizer::add(
// 	$section,
// 	'pickbnr_lazy_type',
// 	[
//
// 		'label' => 'Lazyload種別',
// 		'type'  => 'select',
// 		'choices'  => [
// 			'none' => 'オフ',
// 			'lazy' => 'loading="lazy"を付与',
// 			'lazysizes'  => 'lazysizes.jsを使う',
// 		],
// 	]
// );
