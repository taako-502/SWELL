<?php
use \SWELL_Theme\Customizer;

if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_header';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => __( 'Header', 'swell' ),
	'priority' => 3,
	// 'description' => 'kaisetu',
] );


// ■ カラー設定
Customizer::big_title( $section, 'header_color', [
	'label' => 'カラー設定',
] );

// ヘッダー背景色
Customizer::add( $section, 'color_header_bg', [
	'label' => 'ヘッダー背景色',
	'type'  => 'color',
] );

// ヘッダー文字色
Customizer::add( $section, 'color_header_text', [
	'label' => 'ヘッダー文字色',
	'type'  => 'color',
] );


// ■ ヘッダーロゴの設定
Customizer::big_title( $section, 'header_logo', [
	'label' => 'ヘッダーロゴの設定',
] );

// ロゴ画像の設定
Customizer::add( $section, 'logo_id', [
	'label'     => 'ロゴ画像の設定',
	'type'      => 'media',
	'mime_type' => 'image',
	'partial'   => [
		'selector'            => '.c-headLogo',
		'container_inclusive' => true,
		'render_callback'     => ['\SWELL_THEME\Customizer\Partial', 'head_logo' ],
	],
] );
// 古いデータだけ残っている場合
if ( ! \SWELL_Theme::get_setting( 'logo_id' ) && \SWELL_Theme::get_setting( 'logo' ) ) {
	Customizer::add( $section, 'logo', [
		'label' => '【旧】ロゴ画像',
		'type'  => 'image',
	] );
}

// 画像サイズ（PC）
Customizer::add( $section, 'logo_size_pc', [
	// 'label' => '画像サイズ（PC）',
	'description' => '画像サイズ（PC）: 32~120px',
	'type'        => 'number',
	'input_attrs' => [
		'step'    => '1',
		'min'     => '32',
		'max'     => '120',
	],
	'sanitize'    => ['\SWELL_THEME\Customizer\Sanitize', 'int' ],
] );

// 画像サイズ（PC追従ヘッダー）
Customizer::add( $section, 'logo_size_pcfix', [
	// 'label' => '画像サイズ（PC追従ヘッダー）',
	'description' => '画像サイズ（PC追従ヘッダー）: 24~48px',
	'type'        => 'number',
	'input_attrs' => [
		'step'    => '1',
		'min'     => '24',
		'max'     => '48',
	],
	'sanitize'    => ['\SWELL_THEME\Customizer\Sanitize', 'int' ],
] );

// 画像サイズ（SP）
Customizer::add( $section, 'logo_size_sp', [
	// 'label' => '画像サイズ（SP）',
	'description' => '画像サイズ（SP）: 40~80px',
	'type'        => 'number',
	'input_attrs' => [
		'step'    => '1',
		'min'     => '40',
		'max'     => '80',
	],
	'sanitize'    => ['\SWELL_THEME\Customizer\Sanitize', 'int' ],
] );


// ■ レイアウト・デザイン設定
Customizer::big_title( $section, 'header_layout', [
	'label' => 'レイアウト・デザイン設定',
] );

// ヘッダーのレイアウト(PC)
Customizer::add( $section, 'header_layout', [
	'label'       => 'ヘッダーのレイアウト(PC)',
	'type'        => 'select',
	'choices'     => [
		'series_right'    => 'ヘッダーナビをロゴの横に（右寄せ）',
		'series_left'     => 'ヘッダーナビをロゴの横に（左寄せ）',
		'parallel_bottom' => 'ヘッダーナビを下に',
		'parallel_top'    => 'ヘッダーナビを上に',
	],
] );

// ヘッダーのレイアウト(SP)
Customizer::add( $section, 'header_layout_sp', [
	'label'   => 'ヘッダーのレイアウト(SP)',
	'type'    => 'select',
	'choices' => [
		'left_right'    => 'ロゴ：左 / メニュー：右',
		'center_right'  => 'ロゴ:中央 / メニュー：右',
		'center_left'   => 'ロゴ:中央 / メニュー：左',
	],
] );

// ヘッダー境界線
Customizer::add( $section, 'header_border', [
	'label'   => 'ヘッダー境界線',
	'type'    => 'select',
	'choices' => [
		''       => 'なし',
		'border' => '線',
		'shadow' => '影',
	],
] );


// ■ トップページでの特別設定
Customizer::big_title( $section, 'top_header', [
	'label'       => 'トップページでの特別設定',
	'description' => '※ この設定を使う場合、PCのヘッダーレイアウトは横並びにしてください。',
] );

// ヘッダーの背景を透明にするかどうか
Customizer::add( $section, 'header_transparent', [
	'label'   => 'ヘッダーの背景を透明にするかどうか',
	'type'    => 'select',
	'choices' => [
		'no'     => 'しない',
		't_fff'  => 'する(文字色：白)',
		't_000'  => 'する(文字色：黒)',
	],
] );

// 透過時のロゴ画像
Customizer::add( $section, 'logo_top_id', [
	'classname' => '-top-header-setting',
	'label'     => '透過時のロゴ画像',
	'type'      => 'media',
	'mime_type' => 'image',
	'partial'   => [
		'selector'            => '.c-headLogo',
		'container_inclusive' => true,
		'render_callback'     => ['\SWELL_THEME\Customizer\Partial', 'head_logo' ],
	],
] );
// 古いデータだけ残っている場合
if ( ! \SWELL_Theme::get_setting( 'logo_top_id' ) && \SWELL_Theme::get_setting( 'logo_top' ) ) {
	Customizer::add( $section, 'logo_top', [
		'label' => '【旧】透過時のロゴ画像',
		'type'  => 'image',
	] );
}


// ■ ヘッダーの追従設定
Customizer::big_title( $section, 'fix_head', [
	'label' => 'ヘッダーの追従設定',
] );

// ヘッダーを追従させる（PC）
Customizer::add( $section, 'fix_header', [
	'label'   => 'ヘッダーを追従させる（PC）',
	'type'    => 'checkbox',
] );

// ヘッダーを追従させる（SP）
Customizer::add( $section, 'fix_header_sp', [
	'label'   => 'ヘッダーを追従させる（SP）',
	'type'    => 'checkbox',
] );

// 追従ヘッダー（PC）の背景不透明度
Customizer::add( $section, 'fix_header_opacity', [
	'classname'   => '-fixhead-pc-setting',
	'label'       => '追従ヘッダー（PC）の背景不透明度',
	'description' => '（CSSのopacityプロパティの値を指定してください）',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '0.1',
		'min'  => '0',
		'max'  => '1',
	],
] );


// ■ ヘッダーバー設定
Customizer::big_title( $section, 'headbar', [
	'label'       => 'ヘッダーバー設定',
	'description' => '※ 「ヘッダーバー」はPC表示中にのみ表示されます。',
] );

// ヘッダーバー背景色
Customizer::add( $section, 'color_head_bar_bg', [
	'label'       => 'ヘッダーバー背景色',
	'description' => '※ 色を指定しない場合はメインカラーが適用されます。',
	'type'        => 'color',
] );

// ヘッダーバー文字色
Customizer::add( $section, 'color_head_bar_text', [
	'label'   => 'ヘッダーバー文字色',
	'type'    => 'color',
] );


// 表示設定
Customizer::sub_title( $section, 'headbar_content', [
	'classname' => '',
	'label'     => '表示設定',
] );

// SNSアイコンリストを表示する
Customizer::add( $section, 'show_icon_list', [
	'label'   => 'SNSアイコンリストを表示する',
	'type'    => 'checkbox',
] );

// コンテンツが空でもボーダーとして表示する
Customizer::add( $section, 'show_head_border', [
	'label'   => 'コンテンツが空でもボーダーとして表示する',
	'type'    => 'checkbox',
] );


// ■ キャッチフレーズ設定
Customizer::big_title( $section, 'phrase', [
	'label' => 'キャッチフレーズ設定',
] );

// キャッチフレーズの表示位置
Customizer::add( $section, 'phrase_pos', [
	'label'   => 'キャッチフレーズの表示位置',
	'type'    => 'select',
	'choices' => [
		'none'      => __( 'Don\'t show', 'swell' ),
		'head_bar'  => 'ヘッダーバーに表示',
		'head_wrap' => 'ヘッダーロゴの近くに表示',
	],
] );

// キャッチフレーズにタイトル表示
Customizer::add( $section, 'show_title', [
	'label'   => 'キャッチフレーズに「| ' . \SWELL_Theme::site_data( 'title' ) . '」を表示する',
	'type'    => 'checkbox',
] );


// ■ ヘッダーメニュー（グローバルナビ）設定
Customizer::big_title( $section, 'head_menu_pc', [
	'label' => 'ヘッダーメニュー（グローバルナビ）設定',
] );

// マウスホバーエフェクト
Customizer::add( $section, 'headmenu_effect', [
	'label'   => 'マウスホバーエフェクト',
	'type'    => 'select',
	'choices' => [
		'line_center' => 'ラインの出現（中央から）',
		'line_left'   => 'ラインの出現（左から）',
		'block'       => 'ブロックの出現',
		'bg_gray'     => '背景グレー',
		'bg_light'    => '背景明るく',
	],
] );

// ホバー時に出てくるラインの色
Customizer::add( $section, 'color_head_hov', [
	'classname'   => '-radio-button -gnav-line-setting',
	'label'       => 'ホバー時に出てくるラインの色',
	'description' => '※ 背景色が設定されている場合は白色になります。',
	'type'        => 'radio',
	'choices'     => [
		'main'  => 'メインカラー',
		'text'  => 'テキストカラー',
	],
] );

// ヘッダーメニューの背景色
Customizer::add( $section, 'gnav_bg_type', [
	'classname'   => '-gnav-bg-type',
	'label'       => 'ヘッダーメニューの背景色',
	'description' => '※ PCではヘッダーのレイアウトが縦並びの時に有効です。',
	'type'        => 'radio',
	'choices'     => [
		'default'    => '背景色は設定しない',
		'overwrite'  => '色を指定する',
	],
] );

// ヘッダーメニュー背景色
Customizer::add( $section, 'color_gnav_bg', [
	'classname'   => '-gnav-bg-setting',
	// 'label'   => 'ヘッダーメニュー背景色',
	'description' => '色指定が空の時はメインカラーと同じ色になります。',
	'type'        => 'color',
] );

// サブメニューの背景色
Customizer::add( $section, 'head_submenu_bg', [
	'classname' => '-radio-button',
	'label'     => 'サブメニューの背景色',
	'type'      => 'radio',
	'choices'   => [
		'white' => 'ホワイト',
		'main'  => 'メインカラー',
	],
] );


// ■ ヘッダーメニュー（SP）設定
Customizer::big_title( $section, 'sp_head_menu', [
	'label' => 'ヘッダーメニュー（SP）設定',
] );

// スマホ表示時のループ設定
Customizer::sub_title( $section, 'sp_headmenu_loop', [
	'label' => 'スマホ表示時のループ設定',
] );

// ヘッダーメニューをループさせる
Customizer::add( $section, 'sp_head_nav_loop', [
	'label' => 'ヘッダーメニューをループさせる',
	'type'  => 'checkbox',
] );


// ■ 検索ボタン設定
Customizer::big_title( $section, 'head_search_btn', [
	'label' => '検索ボタン設定',
] );

// 検索ボタンの表示位置（PC）
Customizer::add( $section, 'search_pos', [
	'label'   => '検索ボタンの表示位置（PC）',
	'type'    => 'select',
	'choices' => [
		'none'      => __( 'Don\'t show', 'swell' ),
		'head_bar'  => 'ヘッダーバー内のアイコンリストに表示',
		'head_menu' => 'ヘッダーメニューに表示',
	],
] );

// 検索ボタンの表示設定（SP）
Customizer::add( $section, 'search_pos_sp', [
	'label'   => '検索ボタンの表示設定（SP）',
	'type'    => 'select',
	'choices' => [
		'none'   => __( 'Don\'t show', 'swell' ),
		'header' => 'カスタムボタンにセット',
	],
] );


// ■ メニューボタン設定
Customizer::big_title( $section, 'menu_btn', [
	'label'       => 'メニューボタン設定',
	'description' => 'スマホで表示される <code><i class="icon icon-menu-thin"></i></code> ボタンに関する設定',
] );

// アイコン下に表示するテキスト
Customizer::add( $section, 'menu_btn_label', [
	'label' => 'アイコン下に表示するテキスト',
	'type'  => 'text',
] );

// メニューボタン背景色
Customizer::add( $section, 'menu_btn_bg', [
	'label' => 'メニューボタン背景色',
	'type'  => 'color',
] );


// ■ カスタムボタン設定
Customizer::big_title( $section, 'custom_btn', [
	'label'       => 'カスタムボタン設定',
	'description' => '※ デフォルトでは検索ボタンがセットされています。',
] );

// アイコンクラス名
Customizer::add( $section, 'custom_btn_icon', [
	'label'       => 'アイコンクラス名',
	'description' => '<small>（<a href="https://swell-theme.com/icon-demo/" target="_blank">アイコン一覧はこちら</a>）</small>',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );

// アイコン下に表示するテキスト
Customizer::add( $section, 'custom_btn_label', [
	'label' => 'アイコン下に表示するテキスト',
	'type'  => 'text',
] );

// カスタムボタン背景色
Customizer::add( $section, 'custom_btn_bg', [
	'label' => 'カスタムボタン背景色',
	'type'  => 'color',
] );

// リンク先URL
Customizer::add( $section, 'custom_btn_url', [
	'label'       => 'リンク先URL',
	'description' => '<small>※検索ボタンがカスタムボタンにセットされている場合は無効</small>',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );
