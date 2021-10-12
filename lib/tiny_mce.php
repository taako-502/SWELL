<?php
namespace SWELL_Theme\TinyMCE;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * TinyMCEのエディタ内CSS
 */
add_action( 'admin_init', __NAMESPACE__ . '\hook_admin_init' );
function hook_admin_init() {
	$editor_style_path = [ T_DIRE_URI . '/assets/css/editor_style.css?v=' . SWELL_VERSION ];
	add_editor_style( $editor_style_path );
}


/**
 * TinyMCE設定
 */
add_action( 'tiny_mce_before_init', __NAMESPACE__ . '\hook_tiny_mce_before_init' );
function hook_tiny_mce_before_init( $mceInit ) {

	// 見出し4まで
	$mceInit['block_formats'] = '段落=p; 見出し 2=h2; 見出し 3=h3; 見出し 4=h4;';

	// id など消させない
	$mceInit['valid_elements']          = '*[*]';
	$mceInit['extended_valid_elements'] = '*[*]';

	// styleや、divの中のdiv,span、spanの中のspanを消させない
	$mceInit['valid_children'] = '+body[style],+div[div|span],+span[span],+td[style]';

	// 空タグや、属性なしのタグとか消そうとしたりするのを停止。
	$mceInit['verify_html'] = false;

	// テキストエディタがぐしゃっとなるのを防ぐ
	$mceInit['indent'] = true;

	// テーブルリサイズの制御
	$mceInit['table_resize_bars'] = false;
	$mceInit['object_resizing']   = 'img';

	$mceInit = set_content_style( $mceInit );
	$mceInit = set_body_class( $mceInit );
	$mceInit = set_style_formats( $mceInit );

	return $mceInit;

}


/**
 * 拡張機能スクリプトの読み込み
 */
add_filter( 'mce_external_plugins', __NAMESPACE__ . '\add_mce_external_plugins' );

function add_mce_external_plugins( $plugins ) {
	$plugins['table']        = T_DIRE_URI . '/assets/js/tinymce/table_plugin.min.js';
	$plugins['swellButtons'] = T_DIRE_URI . '/build/js/admin/tinymce.min.js?v=' . SWELL_VERSION;
	return $plugins;
}

/**
 * 拡張機能スクリプトの読み込み
 */
add_filter( 'mce_buttons_2', __NAMESPACE__ . '\add_mce_buttons_2' );
function add_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'shortcode_select' );
	array_unshift( $buttons, 'styleselect' );
	$buttons[] = 'table';
	return $buttons;
}


/**
 * インラインスタイルをセット
 */
function set_content_style( $mceInit ) {

	$current_screen  = get_current_screen();
	$is_block_editor = $current_screen && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();

	if ( $is_block_editor ) {
		return $mceInit;
	}

	// content_styleがまだなければ空でセット
	if ( ! isset( $mceInit['content_style'] ) ) {
		$mceInit['content_style'] = '';
	}

	// editor用インラインスタイル取得
	$add_styles = \SWELL_Theme\Style::get_editor_css();
	$add_styles = str_replace( '\\', '', $add_styles );  // contentのバックスラッシュで変になってしまうのでtinymceは別途指定
	$add_styles = preg_replace( '/(?:\n|\r|\r\n)/su', '', $add_styles );
	$add_styles = str_replace( '"', "'", $add_styles );

	$mceInit['content_style'] .= $add_styles;

	return $mceInit;
}


/**
 * TinyMCEのbodyクラス
 */
function set_body_class( $mceInit ) {

	$SETTING = \SWELL_Theme::get_setting();
	$EDITOR  = \SWELL_Theme::get_editor();

	$body_class = '';

	if ( 'quotation' === $EDITOR['blockquote_type'] ) {
		$body_class .= ' blockquote_quotation';
	}
	if ( 'check' === $SETTING['h4_type'] ) {
		$body_class .= ' h4_check';
	}

	if ( ! isset( $mceInit['body_class'] ) ) {
		// body_class がまだなければそのままセット
		$mceInit['body_class'] = $body_class;
	} else {
		// body_class がすでにあれば追記
		$mceInit['body_class'] .= $body_class;
	}

	return $mceInit;
}


/**
 * スタイルフォーマットのセット
 */
function set_style_formats( $mceInit ) {

	$style_formats = [
		[
			'title' => 'テキスト装飾',
			'items' => [
				[
					'title'   => 'マーカー（オレンジ）',
					'inline'  => 'span',
					'classes' => 'mark_orange',
				],
				[
					'title'   => 'マーカー（イエロー）',
					'inline'  => 'span',
					'classes' => 'mark_yellow',
				],
				[
					'title'   => 'マーカー（グリーン）',
					'inline'  => 'span',
					'classes' => 'mark_green',
				],
				[
					'title'   => 'マーカー（ブルー）',
					'inline'  => 'span',
					'classes' => 'mark_blue',
				],
				[
					'title'  => '注釈',
					'inline' => 'small',
				],
				[
					'title'  => 'インラインコード',
					'inline' => 'code',
				],
				[
					'title'   => 'フォント極小',
					'inline'  => 'span',
					'classes' => 'u-fz-xs',
				],
				[
					'title'   => 'フォント小',
					'inline'  => 'span',
					'classes' => 'u-fz-s',
				],
				[
					'title'   => 'フォント大',
					'inline'  => 'span',
					'classes' => 'u-fz-l',
				],
				[
					'title'   => 'フォント特大',
					'inline'  => 'span',
					'classes' => 'u-fz-xl',
				],
			],
		],
		[
			'title' => '画像効果',
			'items' => [
				[
					'title'    => '枠あり',
					'selector' => 'img',
					'classes'  => 'border',
				],
				[
					'title'    => '影あり',
					'selector' => 'img',
					'classes'  => 'shadow',
				],
				[
					'title'    => '写真フレーム',
					'selector' => 'img',
					'classes'  => 'photo_frame',
				],
				[
					'title'    => '少し小さく表示',
					'selector' => 'img',
					'classes'  => 'size_s',
				],
				[
					'title'    => '小さく表示',
					'selector' => 'img',
					'classes'  => 'size_xs',
				],
			],
		],
		[
			'title' => 'シンプルボックス',
			'items' => [
				[
					'title'   => '線枠（グレー）',
					'block'   => 'div',
					'classes' => 'is-style-border_sg',
					'wrapper' => true,
				],
				[
					'title'   => '点線枠（グレー）',
					'block'   => 'div',
					'classes' => 'is-style-border_dg',
					'wrapper' => true,
				],
				[
					'title'   => '線枠（メイン色）',
					'block'   => 'div',
					'classes' => 'is-style-border_sm',
					'wrapper' => true,
				],
				[
					'title'   => '点線枠（メイン色）',
					'block'   => 'div',
					'classes' => 'is-style-border_dm',
					'wrapper' => true,
				],
				[
					'title'   => '背景（メイン色）',
					'block'   => 'div',
					'classes' => 'is-style-bg_main',
					'wrapper' => true,
				],
				[
					'title'   => '背景（薄メイン色）',
					'block'   => 'div',
					'classes' => 'is-style-bg_main_thin',
					'wrapper' => true,
				],
				[
					'title'   => '背景（グレー）',
					'block'   => 'div',
					'classes' => 'is-style-bg_gray',
					'wrapper' => true,
				],
			],
		],
		[
			'title' => '効果付きボックス',
			'items' => [
				[
					'title'   => 'ストライプ',
					'block'   => 'div',
					'classes' => 'is-style-bg_stripe',
					'wrapper' => true,
				],
				[
					'title'   => '方眼',
					'block'   => 'div',
					'classes' => 'is-style-bg_grid',
					'wrapper' => true,
				],
				[
					'title'   => '角に折り目',
					'block'   => 'div',
					'classes' => 'is-style-crease',
					'wrapper' => true,
				],
				[
					'title'   => 'スティッチ',
					'block'   => 'div',
					'classes' => 'is-style-stitch',
					'wrapper' => true,
				],
				[
					'title'   => '左に縦線',
					'block'   => 'p',
					'classes' => 'is-style-border_left',
				],
				[
					'title'   => '付箋風',
					'block'   => 'p',
					'classes' => 'is-style-sticky_box',
				],
				[
					'title'   => '吹き出し風(塗りつぶし)',
					'block'   => 'p',
					'classes' => 'is-style-balloon_box',
				],
				[
					'title'   => '吹き出し風2（白抜き）',
					'block'   => 'p',
					'classes' => 'is-style-balloon_box2',
				],
				[
					'title'   => 'かぎ括弧',
					'block'   => 'div',
					'classes' => 'is-style-kakko_box',
					'wrapper' => true,
				],
				[
					'title'   => 'かぎ括弧（大）',
					'block'   => 'div',
					'classes' => 'is-style-big_kakko_box',
					'wrapper' => true,
				],
				[
					'title'   => '窪み',
					'block'   => 'div',
					'classes' => 'is-style-dent_box',
					'wrapper' => true,
				],
				[
					'title'   => '浮き出し',
					'block'   => 'div',
					'classes' => 'is-style-emboss_box',
					'wrapper' => true,
				],
							// [ 'title' => '左に縦ライン', 'block' => 'div', 'classes' => 'is-style-left_line', 'wrapper' => true ],
							// [ 'title' => 'チェックアイコン', 'block' => 'div', 'classes' => 'is-style-check_icon', 'wrapper' => true ],
			],
		],
		[
			'title' => 'アイコン付きボックス（小）',
			'items' => [
				[
					'title'   => 'Goodアイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_good',
				],
				[
					'title'   => 'Badアイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_bad',
				],
				[
					'title'   => 'インフォアイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_info',
				],
				[
					'title'   => 'アナウンスアイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_announce',
				],
				[
					'title'   => 'えんぴつアイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_pen',
				],
				[
					'title'   => '書籍アイコン',
					'block'   => 'p',
					'classes' => 'is-style-icon_book',
				],
			],
		],
		[
			'title' => 'アイコン付きボックス（大）',
			'items' => [
				[
					'title'   => '電球アイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_point',
					'wrapper' => true,
				],
				[
					'title'   => 'チェックアイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_good',
					'wrapper' => true,
				],
				[
					'title'   => 'バツ印アイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_bad',
					'wrapper' => true,
				],
				[
					'title'   => 'はてなアイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_hatena',
					'wrapper' => true,
				],
				[
					'title'   => 'アラートアイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_caution',
					'wrapper' => true,
				],
				[
					'title'   => 'ペンアイコン',
					'block'   => 'div',
					'classes' => 'is-style-big_icon_memo',
					'wrapper' => true,
				],
			],
		],
		[
			'title' => 'リスト装飾',
			'items' => [
				[
					'title'    => '【ul】チェックリスト',
					'selector' => 'ul',
					'classes'  => 'is-style-check_list',
				],
				[
					'title'    => '【ul】注釈リスト',
					'selector' => 'ul',
					'classes'  => 'is-style-note_list',
				],
				[
					'title'    => '【ul】Goodリスト',
					'selector' => 'ul',
					'classes'  => 'is-style-good_list',
				],
				[
					'title'    => '【ul】Badリスト',
					'selector' => 'ul',
					'classes'  => 'is-style-bad_list',
				],
				[
					'title'    => '【ol】番号丸塗り',
					'selector' => 'ol',
					'classes'  => 'is-style-num_circle',
				],
				[
					'title'    => '【ul&ol】下線を付ける',
					'selector' => 'ul,ol',
					'classes'  => 'border_bottom',
				],
			],
		],
		[
			'title' => 'ボタン装飾',
			'items' => [
				[
					'title'   => 'ノーマルボタン（赤）',
					'block'   => 'div',
					'classes' => ['is-style-btn_normal', 'red_' ],
					'wrapper' => true,
				],
				[
					'title'   => 'ノーマルボタン（青）',
					'block'   => 'div',
					'classes' => ['is-style-btn_normal', 'blue_' ],
					'wrapper' => true,
				],
				[
					'title'   => 'ノーマルボタン（緑）',
					'block'   => 'div',
					'classes' => ['is-style-btn_normal', 'green_' ],
					'wrapper' => true,
				],
				[
					'title'   => '立体ボタン（赤）',
					'block'   => 'div',
					'classes' => ['is-style-btn_solid', 'red_' ],
					'wrapper' => true,
				],
				[
					'title'   => '立体ボタン（青）',
					'block'   => 'div',
					'classes' => ['is-style-btn_solid', 'blue_' ],
					'wrapper' => true,
				],
				[
					'title'   => '立体ボタン（緑）',
					'block'   => 'div',
					'classes' => ['is-style-btn_solid', 'green_' ],
					'wrapper' => true,
				],
				[
					'title'   => 'キラッとボタン（赤）',
					'block'   => 'div',
					'classes' => ['is-style-btn_shiny', 'red_' ],
					'wrapper' => true,
				],
				[
					'title'   => 'キラッとボタン（青）',
					'block'   => 'div',
					'classes' => ['is-style-btn_shiny', 'blue_' ],
					'wrapper' => true,
				],
				[
					'title'   => 'キラッとボタン（緑）',
					'block'   => 'div',
					'classes' => ['is-style-btn_shiny', 'green_' ],
					'wrapper' => true,
				],

			],
		],
		[
			'title' => 'レイアウト',
			'items' => [
				[
					'title'   => '中央寄せボックス',
					'block'   => 'div',
					'classes' => ['swell-styleBox', 'u-ta-c' ],
					'wrapper' => true,
				],
				[
					'title'   => 'SPのみ表示',
					'block'   => 'div',
					'classes' => ['swell-styleBox', 'sp_only' ],
					'wrapper' => true,
				],
				[
					'title'   => 'PCのみ表示',
					'block'   => 'div',
					'classes' => ['swell-styleBox', 'pc_only' ],
					'wrapper' => true,
				],
			],
		],
		[
			'title' => 'セクション用見出し',
			'items' => [
				[
					'title'   => '見出し2',
					'block'   => 'h2',
					'classes' => 'is-style-section_ttl',
				],
				[
					'title'   => '見出し3',
					'block'   => 'h3',
					'classes' => 'is-style-section_ttl',
				],
				[
					'title'   => '見出し4',
					'block'   => 'h4',
					'classes' => 'is-style-section_ttl',
				],
			],
		],

	];

	// すでにスタイルセレクトが設定されている場合はまとめて最後に追加
	if ( isset( $mceInit['style_formats'] ) ) {
		$old_style_json = $mceInit['style_formats'];

		$old_style_array = json_decode( $old_style_json, true );

		$old_style_array = [
			'title' => 'ユーザーカスタム',
			'items' => $old_style_array,
		];
		$style_formats[] = $old_style_array;
	}
	$mceInit['style_formats'] = json_encode( $style_formats );
	return $mceInit;
}
