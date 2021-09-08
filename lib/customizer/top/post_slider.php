<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_post_slider';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => '記事スライダー',
	'priority' => 10,
	'panel'    => 'swell_panel_top',
] );


// 記事スライダーを設置するかどうか
Customizer::add( $section, 'show_post_slide', [
	'label'   => '記事スライダーを設置するかどうか',
	'type'    => 'radio',
	'choices' => [
		'off' => '設置しない',
		'on'  => '設置する',
	],
] );


// ■ 記事のピックアップ方法
Customizer::big_title( $section, 'ps_pickup_tag', [
	'classname' => '',
	'label'     => '記事のピックアップ方法',
] );

// ピックアップ対象
Customizer::add( $section, 'ps_pickup_type', [
	'classname' => '-radio-button -pickup-post',
	'label'     => 'ピックアップ対象',
	'type'      => 'radio',
	'choices'   => [
		'category' => 'カテゴリー',
		'tag'      => 'タグ',
	],
] );

// ピックアップ対象のタグ名
Customizer::add( $section, 'pickup_tag', [
	'classname'   => '-pickup-tag',
	'label'       => 'ピックアップ対象のタグ名',
	'description' => '※ 空白の場合、全記事の中から表示します。',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );

// ピックアップ対象のカテゴリーID
Customizer::add( $section, 'pickup_cat', [
	'classname'   => '-pickup-cat',
	'label'       => 'ピックアップ対象のカテゴリーID',
	'description' => '※ 空白の場合、全記事の中から表示します。',
	'type'        => 'number',
	'sanitize'    => ['\SWELL_THEME\Customizer\Sanitize', 'int' ],
] );

// 並び順
Customizer::add( $section, 'ps_orderby', [
	'label'   => '並び順',
	'type'    => 'select',
	'choices' => [
		'rand'           => 'ランダム',
		'date'           => '投稿日',
		'modified'       => '更新日',
		'meta_value_num' => '人気順',
	],
] );


// ■ 記事の表示設定
Customizer::big_title( $section, 'ps_per_post', [
	'label' => '記事の表示設定',
] );


// タイトルや日付などの表示位置
Customizer::add( $section, 'ps_style', [
	'label'   => 'タイトルや日付などの表示位置',
	'type'    => 'select',
	'choices' => [
		'normal' => '画像の下側',
		'on_img' => '画像の上に被せる',
	],
] );

// カテゴリー表示位置
Customizer::add( $section, 'pickup_cat_pos', [
	'label'   => __( 'Category display position', 'swell' ),
	'type'    => 'select',
	'choices' => [
		'none'     => __( 'Don\'t show', 'swell' ),
		'on_thumb' => 'サムネイル画像の上',
		'on_title' => 'タイトルの下',
	],
] );

// 日付の表示設定
Customizer::sub_title( $section, 'ps_date', [
	'classname' => '',
	'label'     => '日付の表示設定',
] );

// 公開日を表示する
Customizer::add( $section, 'ps_show_date', [
	'label' => '公開日を表示する',
	'type'  => 'checkbox',
] );

// 更新日を表示する
Customizer::add( $section, 'ps_show_modified', [
	'label' => '更新日を表示する',
	'type'  => 'checkbox',
] );

// 著者の表示設定
Customizer::sub_title( $section, 'ps_author', [
	'label' => '著者の表示設定',
] );

// 著者を表示する
Customizer::add( $section, 'ps_show_author', [
	'label' => '著者を表示する',
	'type'  => 'checkbox',
] );


// ■ スライド設定
Customizer::big_title( $section, 'ps_slider', [
	'label' => 'スライド設定',
] );

// スライダーの枚数設定（PC）
Customizer::add( $section, 'ps_num', [
	'label'       => 'スライダーの枚数設定（PC）',
	'type'        => 'number',
	'input_attrs' => [
		'step'     => '1',
		'min'      => '1',
		'max'      => '6',
	],
] );

// スライダーの枚数設定（SP）
Customizer::add( $section, 'ps_num_sp', [
	'label'       => 'スライダーの枚数設定（SP）',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '0.1',
		'min'  => '1',
		'max'  => '3',
	],
] );

// スライドのアニメーション速度
Customizer::add( $section, 'ps_speed', [
	'label'       => 'スライドのアニメーション速度',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '100',
	],
	'sanitize'    => 'absint',
] );


// スライドが切り替わる間隔
Customizer::add( $section, 'ps_delay', [
	'label'       => 'スライドが切り替わる間隔',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '100',
	],
	'sanitize'    => 'absint',
] );


// その他の設定
Customizer::sub_title( $section, 'ps_other', [
	'label' => 'その他の設定',
] );

// 矢印ナビゲーションを表示する
Customizer::add( $section, 'ps_on_nav', [
	'label' => '矢印ナビゲーションを表示する',
	'type'  => 'checkbox',
] );

// ページネーションを表示する
Customizer::add( $section, 'ps_on_pagination', [
	'label' => 'ページネーションを表示する',
	'type'  => 'checkbox',
] );

// スライド間の余白をなくす
Customizer::add( $section, 'ps_no_space', [
	'label' => 'スライド間の余白をなくす',
	'type'  => 'checkbox',
] );


// ■ その他の表示設定
Customizer::big_title( $section, 'ps_others', [
	'label' => 'その他の表示設定',
] );

// 記事スライダーエリアのタイトル
Customizer::add( $section, 'pickup_title', [
	'label'       => '記事スライダーエリアのタイトル',
	'description' => '空白の場合は出力されません。',
	'type'        => 'text',
] );

// 上下の余白量
Customizer::add( $section, 'pickup_pad_tb', [
	'classname' => '-radio-button',
	'label'     => '上下の余白量',
	'type'      => 'radio',
	'choices'   => [
		'none'   => 'なし',
		'small'  => '小',
		'middle' => '中',
		'wide'   => '大',
	],
] );

// 左右の幅
Customizer::add( $section, 'pickup_pad_lr', [
	'label'       => '左右の幅',
	'description' => '※ PCサイズで表示時のみ有効',
	'type'        => 'select',
	'choices'     => [
		'no'    => 'フルワイド',
		'small' => '左右に少し余白あり',
		'wide'  => 'コンテンツ幅に収める',
	],
] );

// 記事スライダーエリアの文字色
Customizer::add( $section, 'pickup_font_color', [
	'label'       => '記事スライダーエリアの文字色',
	'description' => '※ 投稿タイトルや日付情報の位置が「画像の上に被せる」設定の場合は、投稿情報は白色ので表示されます。',
	'type'        => 'color',
] );

// 記事スライダーエリアの背景色
Customizer::add( $section, 'ps_bg_color', [
	'label' => '記事スライダーエリアの背景色',
	'type'  => 'color',
] );

// 記事スライダーエリアの背景画像
Customizer::add( $section, 'ps_bgimg_id', [
	'label'     => '記事スライダーエリアの背景画像',
	'type'      => 'media',
	'mime_type' => 'image',
] );

// 古いデータだけ残っている場合
if ( ! \SWELL_Theme::get_setting( 'ps_bgimg_id' ) && \SWELL_Theme::get_setting( 'bg_pickup' ) ) {
	Customizer::add( $section, 'bg_pickup', [
		'label' => '【旧】記事スライダーエリアの背景画像',
		'type'  => 'image',
	] );
}

// 背景画像の透過設定
Customizer::add( $section, 'ps_img_opacity', [
	'label'       => '背景画像の透過設定',
	'description' => '不透明度を指定（CSSのopacityプロパティの値）',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '0.1',
		'min'  => '0',
		'max'  => '1',
	],
] );
