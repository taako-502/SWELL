<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

// ■ 動画の設定
Customizer::big_title(
	$section,
	'mv_movie',
	[
		'classname' => 'swell-mv-movie',
		'label'     => '動画の設定',
	]
);

// 動画(PC / Tab)
Customizer::add(
	$section,
	'mv_video',
	[
		'classname'   => 'swell-mv-movie -video',
		'label'       => '動画(PC / Tab) ',
		'type'        => 'media',
		'mime_type'   => 'video',
	]
);

// ポスター画像(PC / Tab)
Customizer::add(
	$section,
	'mv_video_poster',
	[
		'classname'   => 'swell-mv-movie -image',
		'label'       => 'ポスター画像(PC / Tab) ',
		'description' => '動画が読み込まれるまで表示される画像',
		'type'        => 'image',
	]
);

// 動画(SP)
Customizer::add(
	$section,
	'mv_video_sp',
	[
		'classname'   => 'swell-mv-movie -video',
		'label'       => '動画(SP)',
		'type'        => 'media',
		'mime_type'   => 'video',
	]
);

// ポスター画像(SP)
Customizer::add(
	$section,
	'mv_video_poster_sp',
	[
		'classname'   => 'swell-mv-movie -image',
		'label'       => 'ポスター画像(SP)',
		'description' => '動画が読み込まれるまで表示される画像',
		'type'        => 'image',
	]
);

// メインテキスト
Customizer::add(
	$section,
	'movie_title',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'メインテキスト',
		'type'        => 'text',
	]
);

// サブテキスト
Customizer::add(
	$section,
	'movie_text',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'サブテキスト',
		'type'        => 'textarea',
	]
);

// ブログパーツID
Customizer::add(
	$section,
	'movie_parts_id',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'ブログパーツID',
		'type'        => 'text',
	]
);

// ボタンのリンク先URL
Customizer::add(
	$section,
	'movie_url',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'ボタンのリンク先URL',
		'type'        => 'text',
		'sanitize'    => 'esc_url_raw',
	]
);

// ボタンテキスト
Customizer::add(
	$section,
	'movie_btn_text',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'ボタンテキスト',
		'type'        => 'text',
	]
);

// テキストの位置
Customizer::add(
	$section,
	'movie_txtpos',
	[
		'classname'   => 'swell-mv-movie -radio-button',
		'label'       => 'テキストの位置',
		'type'        => 'radio',
		'choices'     => [
			'l' => '左',
			'c' => '中央',
			'r' => '右',
		],
	]
);

// テキストカラー
Customizer::add(
	$section,
	'movie_txtcol',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'テキストカラー',
		'type'        => 'color',
	]
);

// テキストのシャドウカラー
Customizer::add(
	$section,
	'movie_shadowcol',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'テキストのシャドウカラー',
		'type'        => 'color',
	]
);

// ボタンカラー
Customizer::add(
	$section,
	'movie_btncol',
	[
		'classname'   => 'swell-mv-movie',
		'label'       => 'ボタンカラー',
		'type'        => 'color',
	]
);

// ボタンタイプ
Customizer::add(
	$section,
	'movie_btntype',
	[
		'classname'   => 'swell-mv-movie -radio-button',
		'label'       => 'ボタンタイプ',
		'type'        => 'radio',
		'choices'     => [
			'n' => '白抜き',
			'b' => 'ボーダー',
		],
	]
);
