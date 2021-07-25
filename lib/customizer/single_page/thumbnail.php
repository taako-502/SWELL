<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_thumbnail';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => 'アイキャッチ画像',
		'priority' => 10,
		'panel'    => 'swell_panel_single_page',
	]
);

// ■ 固定ページ
Customizer::big_title(
	$section,
	'page_thumb',
	[
		'label' => '固定ページ',
	]
);

// 本文の始めにアイキャッチ画像を表示
Customizer::add(
	$section,
	'show_page_thumb',
	[
		'classname'   => '',
		'label'       => '本文の始めにアイキャッチ画像を表示',
		'type'        => 'checkbox',
	]
);


// ■ 投稿ページ
Customizer::big_title(
	$section,
	'post_thumb',
	[
		'label' => '投稿ページ',
	]
);

// 本文の始めにアイキャッチ画像を表示
Customizer::add(
	$section,
	'show_post_thumb',
	[
		'classname'   => '',
		'label'       => '本文の始めにアイキャッチ画像を表示',
		'type'        => 'checkbox',
	]
);

// 上記がチェックされている時、アイキャッチ画像がない場合に
Customizer::add(
	$section,
	'show_noimg_thumb',
	[
		'classname'   => '-show-noimg-thumb',
		'label'       => 'アイキャッチ画像なければ「NO IMAGE画像」を代わりに表示する',
		'type'        => 'checkbox',
	]
);
