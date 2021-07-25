<?php
namespace SWELL_Theme\Meta\LP;

use \SWELL_Theme as SWELL;
use \SWELL_THEME\Parts\Setting_Field as Field;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\hook_add_meta_box', 1 );
add_action( 'save_post', __NAMESPACE__ . '\hook_save_post' );


/**
 * メタボックスの追加
 */
function hook_add_meta_box() {
	add_meta_box(
		'swell_post_meta_lp',
		__( 'LP Settings', 'swell' ),
		__NAMESPACE__ . '\lp_meta_cb',
		['lp' ],
		'side',
		'default',
		null
	);
}


/**
 * 【LP設定】
 */
function lp_meta_cb( $post ) {
	$the_id = $post->ID;

	SWELL::set_nonce_field( '_meta_lp' );
?>
	<div id="swell_metabox_lp" class="swl-meta -lp -side">

		<div class="swl-meta__item">
			<?php
				$field_args = [
					'id'          => 'lp_content_width',
					'title'       => __( 'コンテンツの最大幅', 'swell' ),
					'meta'        => get_post_meta( $the_id, 'lp_content_width', true ) ?: '900px',
				];
				Field::meta_text_input( $field_args );
			?>
		</div>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">コンテンツの囲み枠</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'lp_body_style', true ) ?: 'no';
				$choices  = [
					'no'     => 'なし',
					'border' => '線で囲む',
					'shadow' => '影をつける',
				];
				Field::meta_radiobox( 'lp_body_style', $choices, $meta_val );
			?>
		</div>
		<div class="swl-meta__item">
			<label class="swl-meta__subttl">アイキャッチ画像の表示設定</label>
			<?php
				$meta_val = get_post_meta( $the_id, 'lp_thumb_pos', true ) ?: 'no';
				$choices  = [
					'no'    => _x( 'Hide', 'show', 'swell' ),
					'top'   => 'フルワイドで表示',
					'inner' => 'コンテンツに収めて表示',
				];
				Field::meta_radiobox( 'lp_thumb_pos', $choices, $meta_val );
			?>
		</div>
		<div class="swl-meta__item">
			<label class="swl-meta__subttl">タイトルの表示設定</label>
			<?php
				$meta_val = get_post_meta( $the_id, 'lp_title_pos', true ) ?: 'no';
				$choices  = [
					'no'    => _x( 'Hide', 'show', 'swell' ),
					'inner' => _x( 'Show', 'show', 'swell' ),
				];
				Field::meta_radiobox( 'lp_title_pos', $choices, $meta_val );
			?>
		</div>
		<div class="swl-meta__item">
			<label class="swl-meta__subttl">SWELLのスタイルを適用するか</label>
			<?php
				$meta_val = get_post_meta( $the_id, 'lp_use_swell_style', true ) ?: 'on';
				$choices  = [
					'on'  => '適用する',
					'off' => '適用しない',
				];
				Field::meta_radiobox( 'lp_use_swell_style', $choices, $meta_val );
			?>
			<p class="description">
				通常の投稿ページのように、記事のコンテンツにSWELLのスタイルを適用するかどうか。
			</p>
		</div>

		<div class="swl-meta__item">
			<label class="swl-meta__subttl">ヘッダー・フッター設定</label>
			<?php
				$meta_checkboxes = [
					'lp_use_swell_header' => __( 'SWELLのヘッダーを使用する', 'swell' ),
					'lp_use_swell_footer' => __( 'SWELLのフッターを使用する', 'swell' ),
				];
				foreach ( $meta_checkboxes as $key => $label ) :
					$meta_val = get_post_meta( $the_id, $key, true );
				?>
					<div class="swl-meta__field">
						<?php Field::meta_checkbox( $key, $label, $meta_val ); ?>
					</div>
				<?php
				endforeach;
			?>
		</div>
	</div>
	<?php
}


/**
 * 保存処理
 */
function hook_save_post( $post_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_lp' ) ) {
		return;
	}

	$METAKEYS = [
		'lp_content_width',
		'lp_body_style',
		'lp_thumb_pos',
		'lp_title_pos',
		'lp_use_swell_style',
		'lp_use_swell_header',
		'lp_use_swell_footer',
	];

	foreach ( $METAKEYS as $key ) {
		// 保存したい情報が渡ってきていれば更新作業に入る
		if ( isset( $_POST[ $key ] ) ) {

			$meta_val = $_POST[ $key ];
			if ( ! is_bool( $meta_val ) ) {
				$meta_val = sanitize_text_field( $meta_val );  // 入力された値をサニタイズ
			}

			// DBアップデート
			update_post_meta( $post_id, $key, $meta_val );

		}
	}
}
