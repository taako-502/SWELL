<?php
namespace SWELL_Theme\Meta\Button;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'post_updated', __NAMESPACE__ . '\hook_save_post', 10, 2 );

/**
 * 保存処理
 */
function hook_save_post( $post_id, $post ) {
	// 自動保存時には保存しないように
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 権限確認
	if ( ! SWELL::check_user_can_edit( $post_id ) ) {
		return;
	}

	// 保存済のボタンクリックデータを取得
	$btn_cv_metas = get_post_meta( $post_id, 'swell_btn_cv_data', true ) ?: [];

	if ( $btn_cv_metas ) $btn_cv_metas = json_decode( $btn_cv_metas, true );
	if ( ! is_array( $btn_cv_metas ) ) wp_die( json_encode( [] ) );

	// 新しいボタンID一覧
	$new_btn_ids = [];

	// コンテンツをパースしてSWELLボタンを抽出
	$blocks = parse_blocks( $post->post_content );

	foreach ( $blocks as $block ) {
		if ( $block['blockName'] === 'loos/button' ) {
			// SWELLボタンのボタンIDを格納
			preg_match( '/\sdata-id="([^"]*)"/', $block['innerHTML'], $id_matches );
			if ( $id_matches ) {
				$new_btn_ids[] = $id_matches[1];
			}
		}
	}

	if ( empty( $new_btn_ids ) ) return;

	// コンテンツ内のボタンIDがメタフィールドのキーに無ければ削除
	$new_btn_cv_metas = $btn_cv_metas;
	foreach ( $btn_cv_metas as $key => $value ) {
		if ( ! in_array( $key, $new_btn_ids, true ) ) {
			unset( $new_btn_cv_metas[ $key ] );
		}
	}

	// DBアップデート
	$new_btn_cv_metas = json_encode( $new_btn_cv_metas );
	update_post_meta( $post_id, 'swell_btn_cv_data', $new_btn_cv_metas );
}
