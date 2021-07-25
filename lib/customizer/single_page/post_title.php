<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_post_title';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => 'タイトル',
		'priority' => 10,
		'panel'    => 'swell_panel_single_page',
	]
);

// ■ 投稿ページ
Customizer::big_title(
	$section,
	'post_title',
	[
		'label' => '投稿ページ',
	]
);

// タイトルの表示位置
Customizer::add(
	$section,
	'post_title_pos',
	[
		'classname'   => '',
		'label'       => 'タイトルの表示位置',
		'type'        => 'select',
		'choices'     => [
			'top'    => __( 'Above the content', 'swell' ),
			'inner'  => __( 'Inside the content', 'swell' ),
		],
	]
);

// 表示する日付
Customizer::add(
	$section,
	'title_date_type',
	[
		'classname'   => '',
		'label'       => 'タイトル横に表示する日付',
		'type'        => 'radio',
		'choices'     => [
			'date'     => '公開日',
			'modified' => '更新日',
		],
	]
);

// タイトル横に日付を表示する（PC）
Customizer::add(
	$section,
	'show_title_date',
	[
		'classname'   => '',
		'label'       => 'タイトル横に日付を表示する（PC）',
		'type'        => 'checkbox',
	]
);

// タイトル横に日付を表示する（SP）
Customizer::add(
	$section,
	'show_title_date_sp',
	[
		'classname'   => '',
		'label'       => 'タイトル横に日付を表示する（SP）',
		'type'        => 'checkbox',
	]
);

// タイトル下に表示する情報
Customizer::sub_title(
	$section,
	'post_title_terms',
	[
		'label' => 'タイトル下に表示する情報',
	]
);

// タイトル下にカテゴリーを表示する
Customizer::add(
	$section,
	'show_meta_cat',
	[
		'classname'   => '',
		'label'       => 'タイトル下にカテゴリーを表示する',
		'type'        => 'checkbox',
	]
);

// タイトル下にタグを表示する
Customizer::add(
	$section,
	'show_meta_tag',
	[
		'classname'   => '',
		'label'       => 'タイトル下にタグを表示する',
		'type'        => 'checkbox',
	]
);

// タイトル下に公開日を表示する
Customizer::add(
	$section,
	'show_meta_posted',
	[
		'classname'   => '',
		'label'       => 'タイトル下に公開日を表示する',
		'type'        => 'checkbox',
	]
);

// タイトル下に更新日を表示する
Customizer::add(
	$section,
	'show_meta_modified',
	[
		'classname'   => '',
		'label'       => 'タイトル下に更新日を表示する',
		'type'        => 'checkbox',
	]
);


// タイトル下に著者を表示する
Customizer::add(
	$section,
	'show_meta_author',
	[
		'classname'   => '',
		'label'       => 'タイトル下に著者を表示する',
		'type'        => 'checkbox',
	]
);


// ■ 固定ページ
Customizer::big_title(
	$section,
	'page_title',
	[
		'label' => '固定ページ',
	]
);

// タイトルの表示位置
Customizer::add(
	$section,
	'page_title_pos',
	[
		'classname'   => '',
		'label'       => 'タイトルの表示位置',
		'type'        => 'select',
		'choices'     => [
			'top'    => __( 'Above the content', 'swell' ),
			'inner'  => __( 'Inside the content', 'swell' ),
		],
	]
);


// ページタイトルのデザイン
Customizer::add(
	$section,
	'page_title_style',
	[
		'classname'   => '',
		'label'       => 'コンテンツ内タイトルデザイン',
		'description' => 'タイトルが「コンテンツ内」に表示される場合のデザイン',
		'type'        => 'select',
		'choices'     => [
			''         => '装飾なし',
			'b_bottom' => '下線',
		],
	]
);
