<?php
namespace SWELL_Theme\Term_Meta;

use \SWELL_Theme as SWELL;
use \SWELL_THEME\Parts\Setting_Field as Field;

if ( ! defined( 'ABSPATH' ) ) exit;


// @codingStandardsIgnoreStart

/**
 * ターム「新規追加」画面にフィールド追加
 */
add_action( 'category_add_form_fields', __NAMESPACE__ . '\add_term_fields' );
add_action( 'post_tag_add_form_fields', __NAMESPACE__ . '\add_term_fields' );
function add_term_fields() {
	SWELL::set_nonce_field( '_meta_term' );
?>
	<div class="form-field">
		<label><?=__( 'Featured image', 'swell' )?></label>
		<?php Field::media_btns( 'swell_term_meta_image', '', 'id' ); ?>
	</div>
<?php
}


/*
 * ターム「編集」画面にフィールド追加
 */
add_action( 'category_edit_form_fields', __NAMESPACE__ . '\add_term_edit_fields' );
add_action( 'post_tag_edit_form_fields', __NAMESPACE__ . '\add_term_edit_fields' );
function add_term_edit_fields( $term ) {

	$the_term_id = $term->term_id;
	$the_tax     = $term->taxonomy;

	$term_ttl         = get_term_meta( $the_term_id, 'swell_term_meta_ttl', 1 );
	$term_subttl      = get_term_meta( $the_term_id, 'swell_term_meta_subttl', 1 );
	$term_image       = get_term_meta( $the_term_id, 'swell_term_meta_image', 1 );
	$term_ttlbg       = get_term_meta( $the_term_id, 'swell_term_meta_ttlbg', 1 );
	$is_show_thumb    = get_term_meta( $the_term_id, 'swell_term_meta_show_thumb', 1 );
	$is_show_desc     = get_term_meta( $the_term_id, 'swell_term_meta_show_desc', 1 );
	$is_show_list     = get_term_meta( $the_term_id, 'swell_term_meta_show_list', 1 );
	$term_newttl      = get_term_meta( $the_term_id, 'swell_term_meta_newttl', 1 );
	$term_rankttl     = get_term_meta( $the_term_id, 'swell_term_meta_rankttl', 1 );
	$parts_id         = get_term_meta( $the_term_id, 'swell_term_meta_display_parts', 1 );
	$cta_id           = get_term_meta( $the_term_id, 'swell_term_meta_cta_parts', 1 );

	$note_text = __( 'Note : ', 'swell' );

	SWELL::set_nonce_field( '_meta_term' );
?>
	<tr class="swell_term_meta_title">
		<th colspan="2">
			<h2><?=__( 'SWELL Settings', 'swell' )?></h2>
		</th>
	</tr>
	<tr class="form-field">
		<th><?=__( 'Title to display on the page', 'swell' )?></th>
		<td>
			<input type="text" name="swell_term_meta_ttl" id="swell_term_meta_ttl" size="40" value="<?=esc_attr( $term_ttl )?>">
			<p class="description">
				<?=__( 'If left blank, the term name will be output as is.', 'swell' )?>
			</p>
		</td>
	</tr>
	<tr class="form-field">
		<th><?=__( 'Subtitle to display on the page', 'swell' )?></th>
		<td>
			<input type="text" name="swell_term_meta_subttl" id="swell_term_meta_subttl" size="40" value="<?=esc_attr( $term_subttl )?>">
			<p class="description">
				<?=__( 'If left blank, "category" or "tag" will be output.', 'swell' )?>
			</p>
		</td>
	</tr>
	<tr class="form-field">
		<th><?=__( 'List layout', 'swell' )?></th>
		<td>
			<?php
				$default = '-- ' . __( 'Follow base settings', 'swell' ) . ' --';
				$meta_val  = get_term_meta( $the_term_id, 'swell_term_meta_list_type', 1 );
				// $options  = [
				// 	'card' => __( 'Card type', 'swell' ),
				// 	'list' => __( 'List type', 'swell' ),
				// ];
				Field::meta_select( 'swell_term_meta_list_type', \SWELL_Theme::$list_layouts, $meta_val, $default );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th><?=__( 'Whether to separate tabs by "new arrival" / "popularity"', 'swell' )?></th>
		<td>
			<?php
				$default = '-- ' . __( 'Follow base settings', 'swell' ) . ' --';
				$meta_val  = get_term_meta( $the_term_id, 'swell_term_meta_show_rank', 1 );
				$options  = [
					'1'    => _x( 'Yes', 'do', 'swell' ), // '1' なのは過去の設定（チェックボックスだった頃）を引き継ぐため
					'none' => _x( 'No', 'do', 'swell' ),
				];
				Field::meta_select( 'swell_term_meta_show_rank', $options, $meta_val, $default );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th><?=__( 'Title position', 'swell' )?></th>
		<td>
			<?php
				$default = '-- ' . __( 'Follow base settings', 'swell' ) . ' --';
				$meta_val  = get_term_meta( $the_term_id, 'swell_term_meta_ttlpos', 1 );
				$options  = [
					'top'   => __( 'Above the content', 'swell' ),
					'inner' => __( 'Inside the content', 'swell' ),
				];
				Field::meta_select( 'swell_term_meta_ttlpos', $options, $meta_val, $default );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th><?=__( 'Sidebar', 'swell' )?></th>
		<td>
			<?php
				$default = '-- ' . __( 'Follow base settings', 'swell' ) . ' --';
				$meta_val  = get_term_meta( $the_term_id, 'swell_term_meta_show_sidebar', 1 );
				$options  = [
					'show' => _x( 'Show', 'show', 'swell' ),
					'hide' => _x( 'Hide', 'show', 'swell' ),
				];
				Field::meta_select( 'swell_term_meta_show_sidebar', $options, $meta_val, $default );
			?>
		</td>
	</tr>
	<?php if ( 'category' === $the_tax ) : ?>
		<tr class="form-field">
			<th>
				タームナビゲーション
			</th>
			<td>
				<?php
					$default = '-- ' . __( 'Follow base settings', 'swell' ) . ' --';
					$meta_val  = get_term_meta( $the_term_id, 'swell_term_meta_show_nav', 1 );
					$options  = [
						'show' => _x( 'Show', 'show', 'swell' ),
						'hide' => _x( 'Hide', 'show', 'swell' ),
					];
					Field::meta_select( 'swell_term_meta_show_nav', $options, $meta_val, $default );
				?>
			</td>
		</tr>
	<?php endif; ?>
	<tr class="form-field">
		<th><label for="swell_term_meta_ttlbg"><?=__( 'Background image for title', 'swell' )?></label></th>
		<td>
			<?php Field::media_btns( 'swell_term_meta_ttlbg', $term_ttlbg, 'id' ); ?>
		</td>
	</tr>
	<tr class="form-field">
		<th><label for="swell_term_meta_image"><?=__( 'Featured image', 'swell' )?></label></th>
		<td>
			<?php Field::media_btns( 'swell_term_meta_image', $term_image, 'id' ); ?>
		</td>
	</tr>
	<tr class="form-field">
		<th>「アイキャッチ画像」をページに表示させるかどうか</th>
		<td>
			<?php
				$checked = ( $is_show_thumb === '1' ) ? ' checked' : ''; // 標準：オフ
				Field::switch_checkbox( 'swell_term_meta_show_thumb', $is_show_thumb, $checked );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th>「説明」の内容をページに表示させるかどうか</th>
		<td>
			<?php
				$checked = ( $is_show_desc !== '0' ) ? ' checked' : ''; // 標準：オン
				Field::switch_checkbox( 'swell_term_meta_show_desc', $is_show_desc, $checked );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th>記事一覧リストを表示するかどうか</th>
		<td>
			<?php
				$checked = ( $is_show_list !== '0' ) ? ' checked' : ''; // 標準：オン
				Field::switch_checkbox( 'swell_term_meta_show_list', $is_show_list, $checked );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th>ページで呼び出すブログパーツ</th>
		<td>
			<input type="text" name="swell_term_meta_display_parts" id="swell_term_meta_display_parts" size="20" value="<?=$parts_id?>" style="width: 6em">
			<?php
				if ( 'category' === $the_tax ) {
					Field::parts_select( 'for_cat', 'swell_term_meta_display_parts', $parts_id );
				} elseif ( 'post_tag' === $the_tax ) {
					Field::parts_select( 'for_tag', 'swell_term_meta_display_parts', $parts_id );
				}
			?>
			<?php if ( $parts_id ) : ?>
				<a href="<?=admin_url( '/post.php?post=' . $parts_id . '&action=edit' )?>">編集ページへ</a>
			<?php endif; ?>
			<p class="description">
				<?=$note_text?> ブログパーツのIDを半角で入力してください。<br>
				<?=$note_text?> アーカイブページにコンテンツが表示されます。
			</p>
			<p class="u-mt-10">
			<?php
				$is_hide_parts_paged     = get_term_meta( $the_term_id, 'swell_term_meta_hide_parts_paged', 1 );
				Field::meta_checkbox( 'swell_term_meta_hide_parts_paged', '２ページ目以降は表示しない', $is_hide_parts_paged );
			?>
			</p>
		</td>
	</tr>
	<?php if ( 'category' === $the_tax ) : ?>
	<tr class="form-field">
		<th>このカテゴリーのCTA</th>
		<td>
			<input type="text" name="swell_term_meta_cta_parts" id="swell_term_meta_cta_parts" size="20" value="<?=$cta_id?>" style="width: 6em">
			<?php 
				Field::parts_select( 'cta', 'swell_term_meta_cta_parts', $cta_id );

				if ( $cta_id ) : ?>
					<a href="<?=admin_url( '/post.php?post=' . $cta_id . '&action=edit' )?>">編集ページへ</a>
			<?php endif; ?>
			<p class="description">
				<?=$note_text?> ブログパーツのIDを半角で入力してください。<br>
				<?=$note_text?> 投稿ページのCTAエリアにコンテンツが表示されます。
			</p>
		</td>
	</tr>
	<?php endif; ?>
<?php
}


// 保存処理
add_action( 'created_term', __NAMESPACE__ . '\save_term_filds' );  // 新規追加用フック
add_action( 'edited_terms', __NAMESPACE__ . '\save_term_filds' );  // 編集ページ用フック
function save_term_filds( $term_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_term' ) ) {
		return;
	}

	$meta_array = [
		'swell_term_meta_list_type',
		'swell_term_meta_ttl',
		'swell_term_meta_subttl',
		'swell_term_meta_image',
		'swell_term_meta_ttlbg',
		'swell_term_meta_ttlpos',
		'swell_term_meta_show_thumb',
		'swell_term_meta_show_desc',
		'swell_term_meta_show_rank',
		'swell_term_meta_display_parts',
		'swell_term_meta_cta_parts',
		'swell_term_meta_show_list',
		'swell_term_meta_show_sidebar',
		'swell_term_meta_show_nav',
		'swell_term_meta_hide_parts_paged',
	];
	foreach ( $meta_array as $metakey ) {
		if ( isset( $_POST[ $metakey ] ) ) {
			update_term_meta( $term_id, $metakey, $_POST[ $metakey ] );
		}
	}
}
