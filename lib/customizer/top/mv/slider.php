<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

// ■ 画像スライダー設定
Customizer::big_title(
	$section,
	'mv_slider',
	[
		'classname'   => 'swell-mv-slider -slider-area-bigttl',
		'label'       => '画像スライダー設定',
		'description' => '※ スライド画像を<b>２枚以上設定すると</b>、追加の設定が出現します。',
	]
);



// スライドの切り替えアニメーション
Customizer::add(
	$section,
	'mv_slide_effect',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting -radio-button',
		'label'       => 'スライドの切り替えアニメーション',
		'type'        => 'radio',
		'choices'     => [
			'fade'  => 'フェード',
			'slide' => 'スライド',
		],
	]
);

// スライドの表示中アニメーション
Customizer::add(
	$section,
	'mv_slide_animation',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting -radio-button',
		'label'       => 'スライドの表示中アニメーション',
		'type'        => 'radio',
		'choices'     => [
			'no'          => 'なし',
			'zoomUp'      => 'ズームイン',
			'leftToRight' => '左から右へ',
		],
	]
);

// スライドの切り替わり速度
Customizer::add(
	$section,
	'mv_slide_speed',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'スライドの切り替わり速度',
		'type'        => 'number',
		'sanitize'    => 'absint',
		'input_attrs' => [
			'step'     => '100',
		],
	]
);

// スライドが切り替わる間隔
Customizer::add(
	$section,
	'mv_slide_delay',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'スライドが切り替わる間隔',
		'type'        => 'number',
		'sanitize'    => 'absint',
		'input_attrs' => [
			'step'     => '100',
		],
	]
);

// スライドの表示枚数
Customizer::add(
	$section,
	'mv_slide_num',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'スライドの表示枚数',
		'description' => '<small>(1より大きい時、スライドの切り替えは「スライド」となります)</small>',
		'type'        => 'number',
		'input_attrs' => [
			'step'     => '0.1',
			'min'      => '1',
			'max'      => '3',
		],
	]
);

// スライドの表示枚数（SP）
Customizer::add(
	$section,
	'mv_slide_num_sp',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'スライドの表示枚数（SP）',
		'description' => '<small>(1より大きい時、スライドの切り替えは「スライド」となります)</small>',
		'type'        => 'number',
		'input_attrs' => [
			'step'     => '0.1',
			'min'      => '1',
			'max'      => '3',
		],
	]
);

// ナビゲーションの表示設定
Customizer::sub_title(
	$section,
	'mv_slider_nav',
	[
		'classname' => 'swell-mv-slider -mv-slider-setting',
		'label'     => 'ナビゲーションの表示設定',
	]
);

// 矢印ナビゲーションを表示する
Customizer::add(
	$section,
	'mv_on_nav',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => '矢印ナビゲーションを表示する',
		'type'        => 'checkbox',
	]
);

// ページネーションを表示する
Customizer::add(
	$section,
	'mv_on_pagination',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'ページネーションを表示する',
		'type'        => 'checkbox',
	]
);

// テキストの固定表示設定
Customizer::sub_title(
	$section,
	'mv_fix_text',
	[
		'classname' => 'swell-mv-slider -mv-slider-setting',
		'label'     => 'テキストの固定表示設定',
	]
);

// テキストの固定表示
Customizer::add(
	$section,
	'mv_fix_text',
	[
		'classname'   => 'swell-mv-slider -mv-slider-setting',
		'label'       => 'スライド１枚目のテキストを常に表示する',
		'type'        => 'checkbox',
	]
);

// ■ 各スライドの設定
Customizer::big_title(
	$section,
	'mv_per_slide',
	[
		'classname' => 'swell-mv-slider',
		'label'     => '各スライドの設定',
	]
);

for ( $i = 1; $i < 6; $i++ ) {
	// Setting
	Customizer::sub_title( $section, "mv_subttl_slider{$i}", [
		'classname' => "swell-mv-slider -on-border -slide-num-{$i}",
		'label'     => "スライド[{$i}]",
	] );

	// スライド画像 PC
	Customizer::add( $section, "slider{$i}_imgid", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "スライド画像 [{$i}]（PC用）",
		'type'        => 'media',
		'mime_type'   => 'image',
	] );

	// 古いデータだけ残っている場合
	if ( ! \SWELL_Theme::get_setting( "slider{$i}_imgid" ) && \SWELL_Theme::get_setting( "slider{$i}_img" ) ) {
		Customizer::add( $section, "slider{$i}_img", [
			'classname' => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
			'label'     => "【旧】スライド画像 [{$i}]（PC用）",
			'type'      => 'image',
		] );
	}

	// スライド画像 SP
	Customizer::add( $section, "slider{$i}_imgid_sp", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "スライド画像 [{$i}]（SP用）",
		'type'        => 'media',
		'mime_type'   => 'image',
	] );
	// 古いデータだけ残っている場合
	if ( ! \SWELL_Theme::get_setting( "slider{$i}_imgid_sp" ) && \SWELL_Theme::get_setting( "slider{$i}_img_sp" ) ) {
		Customizer::add( $section, "slider{$i}_img_sp", [
			'classname' => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
			'label'     => "【旧】スライド画像 [{$i}]（SP用）",
			'type'      => 'image',
		] );
	}

	// メインテキスト
	Customizer::add( $section, "slider{$i}_title", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "メインテキスト [{$i}]",
		'type'        => 'text',
	] );

	// サブテキスト
	Customizer::add( $section, "slider{$i}_text", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "サブテキスト [{$i}",
		'type'        => 'textarea',
	] );

	// ブログパーツID
	Customizer::add( $section, "slider{$i}_parts_id", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "ブログパーツID [{$i}",
		'type'        => 'text',
	] );

	// スライドalt
	Customizer::add( $section, "slider{$i}_alt", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "alt属性値 [{$i}]",
		'type'        => 'text',
		'sanitize'    => 'sanitize_text_field',
	] );

	// リンク先URL
	Customizer::add( $section, "slider{$i}_url", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "リンク先URL [{$i}]",
		'type'        => 'text',
		'sanitize'    => 'esc_url_raw',
	] );

	// ボタンテキスト
	Customizer::add( $section, "slider{$i}_btn_text", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "ボタンテキスト [{$i}]",
		'type'        => 'text',
	] );

	// テキストの位置
	Customizer::add( $section, "slider{$i}_txtpos", [
		'classname'   => "swell-mv-slider -radio-button -ttl-mt-small -slide-num-{$i}",
		'label'       => "テキストの位置 [{$i}]",
		'description' => '※ ブログパーツの中には適用されません。',
		'type'        => 'radio',
		'choices'     => [
			'l' => '左',
			'c' => '中央',
			'r' => '右',
		],
	] );

	// テキストカラー
	Customizer::add( $section, "slider{$i}_txtcol", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "テキストカラー [{$i}]",
		'type'        => 'color',
	] );

	// テキストシャドウカラー
	Customizer::add( $section, "slider{$i}_shadowcol", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "テキストシャドウカラー [{$i}]",
		'type'        => 'color',
	] );

	// ボタンカラー
	Customizer::add( $section, "slider{$i}_btncol", [
		'classname'   => "swell-mv-slider -ttl-mt-small -slide-num-{$i}",
		'label'       => "ボタンカラー [{$i}]",
		'type'        => 'color',
	] );

	// ボタンタイプ
	Customizer::add( $section, "slider{$i}_btntype", [
		'classname'   => "swell-mv-slider -radio-button -ttl-mt-small -slide-num-{$i}",
		'label'       => "ボタンタイプ [{$i}]",
		'type'        => 'radio',
		'choices'     => [
			'n' => '白抜き',
			'b' => 'ボーダー',
		],
	] );
}
