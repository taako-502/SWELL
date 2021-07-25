<?php
namespace SWELL_Theme\Meta\Balloon;

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
		'swell_post_meta_speech_balloon',
		'吹き出し設定',
		__NAMESPACE__ . '\balloon_meta_cb',
		['speech_balloon' ],
		'normal',
		'default',
		null
	);
}
// @codingStandardsIgnoreStart

/**
 * ふきだし設定
 */
function balloon_meta_cb( $post ) {
	$the_id = $post->ID;

	SWELL::set_nonce_field( '_meta_balloon' );
?>
<div class="swl-meta swl-meta--balloon">
	<div class="swl-meta--balloon__preview">
		<div class="swl-meta--balloon__preview__inner">
			<div class="swl-meta--balloon__preview__title">ふきだしプレビュー（設定を保存すると変更が反映されます）</div>
			<?php
				$balloon = do_shortcode( '[ふきだし id="' . $post->ID . '"]ふきだしの内容がここに入ります[/ふきだし]' );
				echo preg_replace( '/\ssrc="([^"]*)"\sdata-src=/', ' src=', $balloon );
			?>
		</div>
	</div>
	<div class="swl-meta--balloon__inner -left">
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">アイコン画像</div>
				<?php 
					$icon_image = get_post_meta( $the_id, 'balloon_icon', 1 );
					Field::media_btns( 'balloon_icon', $icon_image );
				?>
		</div>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">
				名前
			</div>
			<?php
				$field_args = [
					'id'          => 'balloon_icon_name',
					'meta'        => get_post_meta( $the_id, 'balloon_icon_name', true ),
				];
				Field::meta_text_input( $field_args );
			?>
		</div>
	</div>
	<div class="swl-meta--balloon__inner -right">
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">アイコンの丸枠</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'balloon_icon_shape', true ) ?: 'circle';
				$choices  = [
					'square' => 'なし',
					'circle' => 'あり',
				];
				Field::meta_radiobox( 'balloon_icon_shape', $choices, $meta_val, false );
			?>
		</div>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">ふきだしの形</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'balloon_type', true ) ?: 'speaking';
				$choices  = [
					'speaking' => '発言',
					'thinking' => '心の声',
				];
				Field::meta_radiobox( 'balloon_type', $choices, $meta_val, false );
			?>
		</div>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">ふきだしの向き</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'balloon_align', true ) ?: 'left';
				$choices  = [
					'left'  => '左',
					'right' => '右',
				];
				Field::meta_radiobox( 'balloon_align', $choices, $meta_val, false );
			?>
		</div>

		<div class="swl-meta__item">
			<div class="swl-meta__subttl">ふきだしの線</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'balloon_border', true ) ?: 'none';
				$choices  = [
					'none' => 'なし',
					'on'   => 'あり',
				];
				Field::meta_radiobox( 'balloon_border', $choices, $meta_val, false );
			?>
		</div>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl">ふきだしの色</div>
			<?php
				$meta_val = get_post_meta( $the_id, 'balloon_col', true ) ?: 'gray';
				$choices  = [
					'gray'   => 'グレー',
					'green'  => 'グリーン',
					'blue'   => 'ブルー',
					'red'    => 'レッド',
					'yellow' => 'イエロー',
				];
				Field::meta_radiobox( 'balloon_col', $choices, $meta_val, false );
			?>
			<p class="swl-meta__desc">
				※ ふきだしカラーは「SWELL設定」内の「<a href="<?=admin_url( '/admin.php?page=swell_settings_editor#balloon' )?>" target="_blank">エディター設定</a>」から編集できます。
			</p>
		</div>
	</div>
</div>

<?php
}


/**
 * 保存処理
 */
function hook_save_post( $post_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_balloon' ) ) {
		return;
	}

	$METAKEYS = [
		'balloon_icon',
		'balloon_col',
		'balloon_type',
		'balloon_align',
		'balloon_border',
		'balloon_icon_shape',
		'balloon_icon_name',
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
