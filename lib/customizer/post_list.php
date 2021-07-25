<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セクション追加
 */
$section = 'swell_section_post_list';

$wp_customize->add_section( $section, [
	'title'    => '記事一覧リスト',
	'priority' => 4,
] );


// ■ リストのレイアウト設定
Customizer::big_title( $section, 'post_list_layout', [
	'label'     => 'リストのレイアウト設定',
] );

// リストレイアウト（PC/Tab）
Customizer::add( $section, 'post_list_layout', [
	'classname'   => '',
	'label'       => 'リストレイアウト（PC/Tab）',
	'type'        => 'select',
	'choices'     => \SWELL_Theme::$list_layouts,
] );

// リストレイアウト（Mobile）
Customizer::add( $section, 'post_list_layout_sp', [
	'classname'   => '',
	'label'       => 'リストレイアウト（Mobile）',
	'type'        => 'select',
	'choices'     => \SWELL_Theme::$list_layouts,
] );

// 最大カラム数（PC/Tab）
Customizer::add( $section, 'max_column', [
	'classname'   => '-radio-button',
	'label'       => '最大カラム数（PC/Tab）',
	'description' => '※ カード型・サムネイル型でのみ有効です。',
	'type'        => 'radio',
	'choices'     => [
		'1'  => '1カラム',
		'2'  => '2カラム',
		'3'  => '3カラム',
	],
] );

// 最大カラム数（Mobile）
Customizer::add( $section, 'max_column_sp', [
	'classname'   => '-radio-button',
	'label'       => '最大カラム数（Mobile）',
	'description' => '※ カード型・サムネイル型でのみ有効です。',
	'type'        => 'radio',
	'choices'     => [
		'1'  => '1カラム',
		'2'  => '2カラム',
	],
] );

// 「READ MORE」のテキスト
Customizer::add( $section, 'post_list_read_more', [
	'classname'   => '-radio-button',
	'label'       => '「READ MORE」のテキスト',
	'description' => 'ブログ型・リスト型（左右交互）で表示される「READ MORE」の表示を変更します',
	'type'        => 'text',
] );


// ■ 投稿情報の表示設定
Customizer::big_title( $section, 'post_list_design', [
	'label'     => '投稿情報の表示設定',
	// 'description' => '※ 「サムネイル型」のリストにのみ有効です。'
] );

// タイトルを隠す
Customizer::add( $section, 'hide_post_ttl', [
	'classname'   => '',
	'label'       => 'タイトルを隠す',
	'description' => '※ 「サムネイル型」のリストにのみ有効です。',
	'type'        => 'checkbox',
] );

// 公開日を表示する
Customizer::add( $section, 'show_list_date', [
	'classname'   => '',
	'label'       => __( 'Show release date', 'swell' ),
	'type'        => 'checkbox',
] );

// 更新日を表示する
Customizer::add( $section, 'show_list_mod', [
	'classname'   => '',
	'label'       => __( 'Show update date', 'swell' ),
	'type'        => 'checkbox',
] );

// 著者を表示する
Customizer::add( $section, 'show_list_author', [
	'classname'   => '',
	'label'       => __( 'Show author', 'swell' ),
	'type'        => 'checkbox',
] );

// 抜粋文の文字数（PC・Tab）
Customizer::add( $section, 'excerpt_length_pc', [
	'classname'   => '',
	'label'       => __( 'Number of characters in the excerpt', 'swell' ) . '（PC・Tab）',
	'type'        => 'select',
	'choices'     => [
		'0'    => '非表示',
		'40'   => '40字',
		'80'   => '80字',
		'120'  => '120字',
		'160'  => '160字',
		'240'  => '240字',
		'320'  => '320字',
	],
] );

// 抜粋文の文字数（Mobile）
Customizer::add( $section, 'excerpt_length_sp', [
	'classname'   => '',
	'label'       => __( 'Number of characters in the excerpt', 'swell' ) . '（Mobile）',
	'type'        => 'select',
	'choices'     => [
		'0'    => '非表示',
		'40'   => '40字',
		'80'   => '80字',
		'120'  => '120字',
		'160'  => '160字',
		'240'  => '240字',
		'320'  => '320字',
	],
] );


// カテゴリーの表示設定
Customizer::big_title( $section, 'post_list_cat', [
	'label'     => __( 'Category display settings', 'swell' ), // 'カテゴリーの表示設定',
] );

// 投稿のカテゴリー表示位置
Customizer::add( $section, 'category_pos', [
	'classname'   => '',
	'label'       => __( 'Category display position', 'swell' ), // '投稿のカテゴリー表示位置',
	'description' => '※ テキスト型リストでは表示位置は固定です。',
	'type'        => 'select',
	'choices'     => [
		'none'        => __( 'Don\'t show', 'swell' ),
		'on_thumb'    => 'サムネイル画像の上に表示',
		'beside_date' => '投稿日時の横に表示',
	],
] );

// サムネイル画像上に表示される時の追加設定
Customizer::sub_title( $section, 'cat_on_thmb', [
	'classname' => '-cat-on-thumb',
	'label'     => 'サムネイル画像上に表示される時の追加設定',
] );

// カテゴリーの文字色
Customizer::add( $section, 'pl_cat_txt_color', [
	'classname'   => '-cat-on-thumb',
	// 'label'       => 'カテゴリーの文字色',
	'description' => 'カテゴリーの文字色',
	'type'        => 'color',
] );

// カテゴリーの背景色
Customizer::add( $section, 'pl_cat_bg_color', [
	'classname'   => '-cat-on-thumb',
	// 'label'       => 'カテゴリーの背景色',
	'description' => 'カテゴリーの背景色<br><small>※ 指定がない場合はメインカラーと同じ色になります</small>',
	'type'        => 'color',
] );

// カテゴリーの背景効果
Customizer::add( $section, 'pl_cat_bg_style', [
	'classname'   => '-cat-on-thumb',
	'description' => 'カテゴリーの背景効果',
	'type'        => 'select',
	'choices'     => [
		'no'        => 'なし',
		'stripe'    => 'ストライプ',
		'gradation' => 'グラデーション',
	],
] );

// カテゴリーアイコンを表示するかどうか

// ■ サムネイル画像の比率設定
Customizer::big_title( $section, 'thumb_ratio', [
	'label'     => 'サムネイル画像の比率設定',
] );

$thumb_ratio_choices = array_map( function( $ratio ) {
	return $ratio['label'];
}, \SWELL_Theme::$thumb_ratios );

// カード型リストでの画像比率
Customizer::add( $section, 'card_posts_thumb_ratio', [
	'classname'   => '',
	'label'       => 'カード型リストでの画像比率',
	'type'        => 'select',
	'choices'     => $thumb_ratio_choices,
] );

// リスト型リストでの画像比率
Customizer::add( $section, 'list_posts_thumb_ratio', [
	'classname'   => '',
	'label'       => 'リスト型リストでの画像比率',
	'type'        => 'select',
	'choices'     => $thumb_ratio_choices,
] );

// サムネイル型リストでの画像比率
Customizer::add( $section, 'thumb_posts_thumb_ratio', [
	'classname'   => '',
	'label'       => 'サムネイル型リストでの画像比率',
	'type'        => 'select',
	'choices'     => $thumb_ratio_choices,
] );

// ブログ型での画像比率
Customizer::add( $section, 'big_posts_thumb_ratio', [
	'classname'   => '',
	'label'       => 'ブログ型での画像比率',
	'type'        => 'select',
	'choices'     => $thumb_ratio_choices,
] );


// ■ マウスホバー時の設定
Customizer::big_title( $section, 'post_list_hover', [
	'label'     => 'マウスホバー時の設定',
] );

// グラデーション色１
Customizer::add( $section, 'color_gradient1', [
	'classname'   => '',
	'label'       => 'グラデーション色１',
	'description' => '画像に着色されるグラデーション色の左側',
	'type'        => 'color',
] );

// グラデーション色2
Customizer::add( $section, 'color_gradient2', [
	'classname'   => '',
	'label'       => 'グラデーション色2',
	'description' => '画像に着色されるグラデーション色の右側',
	'type'        => 'color',
] );


// ■ タブ切り替え設定（トップページ）
Customizer::big_title( $section, 'post_list_tab', [
	'classname'   => '',
	'label'       => 'タブ切り替え設定（トップページ）',
	'description' => 'トップページまたはホームページ設定で「投稿ページ」に指定した固定ページに表示される記事一覧リストの上に表示できる、切り替えタブの設定。',
] );

// 表示するタブの設定
Customizer::sub_title( $section, 'pop_tab', [
	'label'     => '表示するタブの設定',
] );

// 「新着記事タブ」を追加する
Customizer::add( $section, 'show_new_tab', [
	'classname'   => '',
	'label'       => '「新着記事タブ」を追加する',
	'type'        => 'checkbox',
] );

// 「人気記事タブ」を追加する
Customizer::add( $section, 'show_ranking_tab', [
	'classname'   => '',
	'label'       => '「人気記事タブ」を追加する',
	'type'        => 'checkbox',
] );

// 「タームタブ」の設定
Customizer::add( $section, 'top_tab_terms', [
	'classname'   => '',
	'label'       => '「タームタブ」の設定',
	'description' => 'カテゴリーやタグのIDを,区切りで指定してください（例：「2,6,8」）',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );

// 「新着記事タブ」の表示名
Customizer::add( $section, 'new_tab_title', [
	'classname'   => '',
	'label'       => '「新着記事タブ」の表示名',
	'type'        => 'text',
] );

// 「人気記事タブ」の表示名
Customizer::add( $section, 'ranking_tab_title', [
	'classname'   => '',
	'label'       => '「人気記事タブ」の表示名',
	// 'description' => '「人気記事タブ」の表示名',
	'type'        => 'text',
] );

// タブデザイン
Customizer::add( $section, 'top_tab_style', [
	'classname'   => '-radio-button',
	'label'       => 'タブデザイン',
	'type'        => 'radio',
	'choices'     => [
		'default' => '標準',
		'simple'  => 'グレーボックス',
		'bb'      => '下線',
	],
] );


// ■ タブ切り替え設定（その他のページ）
Customizer::big_title( $section, 'post_list_tab_other', [
	'label'     => 'タブ切り替え設定（その他のページ）',
	// 'description' => 'トップページまたはホームページ設定で「投稿ページ」に指定した固定ページに表示される記事一覧リストの上に表示できる、切り替えタブの設定。'
] );

// タームアーカイブに「人気記事タブ」を追加
Customizer::add( $section, 'show_tab_on_term', [
	'classname'   => '',
	'label'       => 'タームアーカイブに「人気記事タブ」を追加',
	'type'        => 'checkbox',
] );

// 著者アーカイブに「人気記事タブ」を追加
Customizer::add( $section, 'show_tab_on_author', [
	'classname'   => '',
	'label'       => '著者アーカイブに「人気記事タブ」を追加',
	'type'        => 'checkbox',
] );


// ■ 投稿一覧から除外するカテゴリー・タグ
Customizer::big_title( $section, 'exc_posts', [
	'classname'   => '',
	'label'       => '投稿一覧から除外するカテゴリー・タグ',
	'description' => 'トップページまたはホームページ設定で「投稿ページ」に指定した固定ページに表示される記事一覧リスト、およびウィジェットでの記事一覧リストでのみ有効です。',
	// 'description' => '<small>※ 保存し、画面を更新すると反映されます。</small>'
] );

// 除外したいカテゴリーのID
Customizer::add( $section, 'exc_cat_id', [
	'classname'   => '',
	'label'       => '除外したいカテゴリーのID',
	'description' => '複数の場合は<code>,</code>区切りで指定してください。<br><small>※ 「新着記事一覧」のリストから除外されます。</small>',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );

// 除外したいタグのID
Customizer::add( $section, 'exc_tag_id', [
	'classname'   => '',
	'label'       => '除外したいタグのID',
	'description' => '複数の場合は<code>,</code>区切りで指定してください。<br><small>※ 「新着記事一覧・人気記事一覧」のリストから除外されます。</small>',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );
