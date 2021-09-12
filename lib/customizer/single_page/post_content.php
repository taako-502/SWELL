<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_post_content';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'       => 'コンテンツのデザイン',
	'description' => 'ボタンやマーカーなどの設定は、SWELL設定の<br>「<a href="' . admin_url( 'admin.php?page=swell_settings_editor' ) . '">エディター設定</a>」へ移動しました。<br><br>',
	'priority'    => 10,
	'panel'       => 'swell_panel_single_page',
] );


// ■ 見出しのデザイン設定
Customizer::big_title( $section, 'headline', [
	'label' => '見出しのデザイン設定',
] );

// 見出しのキーカラー
Customizer::add( $section, 'color_htag', [
	'label'   => '見出しのキーカラー',
	'type'    => 'color',
] );

// 見出し2のデザイン
Customizer::add( $section, 'h2_type', [
	'label'   => '見出し2のデザイン',
	'type'    => 'select',
	'choices' => [
		'band'        => '帯',
		'block'       => '塗り潰し',
		'b_left'      => '左に縦線',
		'b_left2'     => '左に２色のブロック',
		'tag_normal'  => '付箋風',
		'tag'         => '付箋風（ストライプ）',
		'stitch'      => 'ステッチ',
		'stitch_thin' => 'ステッチ（薄）',
		'balloon'     => 'ふきだし風',
		'b_topbottom' => '上下に線',
		'letter'      => '1文字目にアクセント',
		''            => '装飾なし',
	],
] );

// 見出し3のデザイン
Customizer::add( $section, 'h3_type', [
	'label'   => '見出し3のデザイン',
	'type'    => 'select',
	'choices' => [
		'main_gray' => '２色の下線（メイン・グレー）',
		'main_thin' => '２色の下線（メイン・薄メイン）',
		'main_line' => '下線（メインカラー）',
		'gradation' => '下線（グラデーション）',
		'stripe'    => '下線（ストライプ）',
		'l_border'  => '左に縦線',
		'l_block'   => '左に２色のブロック',
		''          => '装飾なし',
	],
] );

// 見出し4のデザイン
Customizer::add( $section, 'h4_type', [
	'label'   => '見出し4のデザイン',
	'type'    => 'select',
	'choices' => [
		'left_line' => '左に縦線',
		'check'     => 'チェックアイコン',
		''          => '装飾なし',
	],
] );


// ■ セクション見出しのデザイン設定
Customizer::big_title( $section, 'sec_headline', [
	'label' => 'セクション見出しのデザイン設定',
] );


// セクション見出しのキーカラー
Customizer::add( $section, 'color_sec_htag', [
	'label' => 'セクション見出しのキーカラー',
	'type'  => 'color',
] );

// セクション用見出し2のデザイン
Customizer::add( $section, 'sec_h2_type', [
	'label'   => 'セクション用見出し2のデザイン',
	'type'    => 'select',
	'choices' => [
		''         => '装飾なし',
		'b_bottom' => '下に線',
		'b_lr'     => '左右に線',
	],
] );

// ■ 太字
Customizer::big_title( $section, 'bold', [
	'label' => '太字',
] );

// 太字の下に点線をつける
Customizer::add( $section, 'show_border_strong', [
	'label'       => '太字の下に点線をつける',
	'description' => '※ pタグ直下でのみ有効',
	'type'        => 'checkbox',
] );


// ■ テキストリンク
Customizer::big_title( $section, 'link', [
	'label' => 'テキストリンク',
] );

// テキストリンクにアンダーラインを付ける
Customizer::add( $section, 'show_link_underline', [
	'label' => 'テキストリンクにアンダーラインを付ける',
	'type'  => 'checkbox',
] );
