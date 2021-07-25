<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_toc';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => '目次',
		'priority' => 10,
		'panel'    => 'swell_panel_single_page',
	]
);

// 目次を表示するかどうか
Customizer::sub_title(
	$section,
	'is_show_index',
	[
		'label'       => '目次を表示するかどうか',
		'description' => '目次を最初のH2タグの直前に自動生成することができます。',
	]
);

// 投稿ページに目次を表示
Customizer::add(
	$section,
	'show_index',
	[
		'classname'   => '',
		'label'       => '投稿ページに目次を表示',
		'type'        => 'checkbox',
	]
);

// 固定ページに目次を表示
Customizer::add(
	$section,
	'show_index_page',
	[
		'classname'   => '',
		'label'       => '固定ページに目次を表示',
		'type'        => 'checkbox',
	]
);

// 目次のタイトル
Customizer::add(
	$section,
	'toc_title',
	[
		'label'       => '目次のタイトル',
		'type'        => 'text',
	]
);

// 目次のデザイン
Customizer::add(
	$section,
	'index_style',
	[
		'classname'   => '',
		'label'       => '目次のデザイン',
		'type'        => 'select',
		'choices'     => [
			'simple'  => 'シンプル',
			'capbox'  => 'ボックス',
			'border'  => '上下ボーダー',
			'double'  => 'ストライプ背景',
		],
	]
);

// 目次のリストタグ
Customizer::add(
	$section,
	'index_list_tag',
	[
		'classname'   => '-radio-button',
		'label'       => '目次のリストタグ',
		'type'        => 'radio',
		'choices'     => [
			'ol' => 'olタグ',
			'ul' => 'ulタグ',
		],
	]
);

// 擬似要素(ドット・数字部分)のカラー
Customizer::add(
	$section,
	'toc_before_color',
	[
		'classname'   => '',
		'label'       => '擬似要素(ドット・数字部分)のカラー',
		'type'        => 'select',
		'choices'     => [
			'text'   => 'テキストカラー',
			'main'   => 'メインカラー',
			'custom' => 'カスタムカラー',
		],
	]
);

Customizer::add(
	$section,
	'toc_before_custom_color',
	[
		'classname'   => '-toc-custom-color',
		// 'description' => 'カスタムカラー',
		'type'        => 'color',
	]
);


// どの階層の見出しまで抽出するか
Customizer::add(
	$section,
	'toc_target',
	[
		'classname'   => '',
		'label'       => 'どの階層の見出しまで抽出するか',
		'type'        => 'select',
		'choices'     => [
			'h2' => 'h2',
			'h3' => 'h3',
		],
	]
);


// 見出しが何個以あれば表示するか
Customizer::add(
	$section,
	'toc_minnum',
	[
		'classname'   => '',
		'label'       => '見出しが何個以上あれば表示するか',
		'type'        => 'number',
		'input_attrs' => [
			'step'    => '1',
			'min'     => '1',
			'max'     => '10',
		],
		'sanitize'    => ['\SWELL_THEME\Customizer\Sanitize', 'int' ],
	]
);


// ■ 目次広告の表示設定
Customizer::big_title(
	$section,
	'toc_ad',
	[
		'label'       => '目次広告の表示設定',
		'description' => '<a href="' . admin_url( 'admin.php?page=swell_settings#ad' ) . '" target="_blank">「SWELL設定」</a>から広告コードを設定すると表示されます。',
	]
);

// 目次広告の位置
Customizer::add(
	$section,
	'toc_ad_position',
	[
		'classname'   => '',
		'label'       => '目次広告の位置',
		'type'        => 'select',
		'choices'     => [
			'before' => '目次の前に設置する',
			'after'  => '目次の後に設置する',
		],
	]
);

Customizer::sub_title(
	$section,
	'toc_ad_code',
	[
		'label'       => '目次がなくても広告を表示するかどうか',
		'description' => '※ 1つ目のh2タグの前に表示されます。',
	]
);

Customizer::add(
	$section,
	'show_toc_ad_alone_post',
	[
		'classname'   => '',
		'label'       => '投稿ページで表示する',
		'type'        => 'checkbox',
	]
);

Customizer::add(
	$section,
	'show_toc_ad_alone_page',
	[
		'classname'   => '',
		'label'       => '固定ページで表示する',
		'type'        => 'checkbox',
	]
);
