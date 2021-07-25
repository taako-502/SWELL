<?php
namespace SWELL_Theme\Meta\Ad;

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
		'swell_post_meta_ad_tag',
		'広告設定',
		__NAMESPACE__ . '\ad_meta_cb',
		['ad_tag' ],
		'normal',
		'default',
		null
	);
}


/**
 * 広告
 */
function ad_meta_cb( $post ) {
	$the_id = $post->ID;

	// メタ情報
	$ad_type     = get_post_meta( $the_id, 'ad_type', true ) ?: 'normal';
	$meta_border = get_post_meta( $the_id, 'ad_border', true ) ?: 'off';
	$meta_rank   = get_post_meta( $the_id, 'ad_rank', true ) ?: 'rank0';
	$meta_name   = get_post_meta( $the_id, 'ad_name', true );
	$meta_price  = get_post_meta( $the_id, 'ad_price', true );
	$meta_price  = get_post_meta( $the_id, 'ad_price', true );
	$meta_desc   = get_post_meta( $the_id, 'ad_desc', true );
	$meta_star   = get_post_meta( $the_id, 'ad_star', true );

	$meta_btn1_text = get_post_meta( $the_id, 'ad_btn1_text', true ) ?: '詳しくみる';
	$meta_btn2_text = get_post_meta( $the_id, 'ad_btn2_text', true ) ?: '購入する';
	$meta_btn1_url  = get_post_meta( $the_id, 'ad_btn1_url', true );
	$meta_btn2_url  = get_post_meta( $the_id, 'ad_btn2_url', true );

	$btn1_hidden = $meta_btn1_url ? '' : ' u-none';
	$btn2_hidden = $meta_btn2_url ? '' : ' u-none';

	$ad_title = $meta_name ?: get_the_title( $the_id );
	// if ( empty( $meta_border ) ) $meta_border = 'off';

	SWELL::set_nonce_field( '_meta_ad' );
	?>
	<div class="swl-meta">
		<div class="swl-meta--ad" data-adtype="<?=$ad_type ?: 'normal' ?>">
			<div class="swl-meta--ad__preview">
				<div class="p-adBox -border-<?=$meta_border?>" data-ad="<?=$ad_type ?: 'normal' ?>"">
					<div class="p-adBox__title -<?=$meta_rank?>"><?=$ad_title?></div>
					<div class="p-adBox__body">
						<div class="p-adBox__img">
							<span class="p-adBox__dammyImage">広告</span>
						</div>
						<div class="p-adBox__details">
							<div class="p-adBox__name"><?=$ad_title?></div>
							<div class="p-adBox__star c-reviewStars"><?=\SWELL_PARTS::review_stars( $meta_star )?></div>
							<div class="p-adBox__price u-thin u-fz-s"><?=$meta_price?></div>
							<div class="p-adBox__desc"><?=$meta_desc?></div>
							<div class="p-adBox__btns">
								<a href="###" class="p-adBox__btn -btn1<?=$btn1_hidden?>"><?=$meta_btn1_text?></a>
								<a href="###" class="p-adBox__btn -btn2<?=$btn2_hidden?>"><?=$meta_btn2_text?></a>
							</div>
						</div>
					</div>
					<div class="p-adBox__btns">
						<a href="###" class="p-adBox__btn -btn1<?=$btn1_hidden?>"><?=$meta_btn1_text?></a>
						<a href="###" class="p-adBox__btn -btn2<?=$btn2_hidden?>"><?=$meta_btn2_text?></a>
					</div>
				</div>
			</div>
			<div class="swl-meta--ad__inner -left">
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">広告タイプ<small>（レイアウトが変化します）</small></div>
					<?php
						$choices = [
							'text'      => 'テキスト型',
							'normal'    => 'バナー型',
							'affiliate' => 'アフィリエイト型',
							'amazon'    => 'Amazon型',
							'ranking'   => 'ランキング型',
						];
						Field::meta_radiobox( 'ad_type', $choices, $ad_type, false );
					?>
					<p class="swl-meta--ad__description">
						<small>※「テキスト型」は広告タグブロックで呼び出すことはできませんが、<br>　ショートコードで文中に呼び出すことができます。</small>
					</p>
				</div>

				<div class="swl-meta__item -border">
					<div class="swl-meta__subttl">広告ボックスの枠</div>
						<?php
						$choices = [
							'off' => 'なし',
							'on'  => 'あり',
						];
						Field::meta_radiobox( 'ad_border', $choices, $meta_border, false );
					?>
				</div>

				<div class="swl-meta__subttl">広告タグ</div>
				<?php $meta_val = get_post_meta( $the_id, 'ad_img', true ); ?>
				<textarea name="ad_img" id="ad_img" cols="60" rows="10"><?=$meta_val?></textarea>

			</div>
			<div class="swl-meta--ad__inner -right">
				<div class="swl-meta__item swl-meta--ad__ranking">
					<div class="swl-meta__subttl">順位</div>
					<?php
						$choices = [
							'rank1' => '１位',
							'rank2' => '２位',
							'rank3' => '３位',
							'rank0' => '順位なし',
						];
						Field::meta_radiobox( 'ad_rank', $choices, $meta_rank, false );
					?>
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">評価</div>
					<?php
						$stars = [
							'0.5' => __( 'Star', 'swell' ) . ' : 0.5',
							'1'   => __( 'Star', 'swell' ) . ' : 1',
							'1.5' => __( 'Star', 'swell' ) . ' : 1.5',
							'2'   => __( 'Star', 'swell' ) . ' : 2',
							'2.5' => __( 'Star', 'swell' ) . ' : 2.5',
							'3'   => __( 'Star', 'swell' ) . ' : 3',
							'3.5' => __( 'Star', 'swell' ) . ' : 3.5',
							'4'   => __( 'Star', 'swell' ) . ' : 4',
							'4.5' => __( 'Star', 'swell' ) . ' : 4.5',
							'5'   => __( 'Star', 'swell' ) . ' : 5',
						];
						Field::meta_select( 'ad_star', $stars, $meta_star, '評価を選択' );
					?>
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">表示名<small> （空の場合はタイトルが出力されます）</small></div>
					<input type="text" id="ad_name" name="ad_name" size="40" value="<?=$meta_name?>">
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">価格</div>
					<input type="text" id="ad_price" name="ad_price" size="40" value="<?=$meta_price?>">
				</div>

				<div class="swl-meta__item meta_ad_desc">
					<div class="swl-meta__subttl">説明文</div>
					<textarea name="ad_desc" id="ad_desc" cols="60" rows="5"><?=$meta_desc?></textarea>
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">ボタン1リンク先</div>
					<input type="text" id="ad_btn1_url" name="ad_btn1_url" size="40" value="<?=$meta_btn1_url?>">
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">ボタン1テキスト</div>
					<input type="text" id="ad_btn1_text" name="ad_btn1_text" size="40" value="<?=$meta_btn1_text?>">
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">ボタン2リンク先</div>
					<input type="text" id="ad_btn2_url" name="ad_btn2_url" size="40" value="<?=$meta_btn2_url?>">
				</div>
				<div class="swl-meta__item">
					<div class="swl-meta__subttl">ボタン2テキスト</div>
					<input type="text" id="ad_btn2_text" name="ad_btn2_text" size="40" value="<?=$meta_btn2_text?>">
				</div>
			</div>
		</div>
	</div>
<?php
}

/**
 * 保存処理
 */
function hook_save_post( $the_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_ad' ) ) {
		return;
	}

	$METAKEYS = [
		'ad_type',
		'ad_rank',
		'ad_img',
		'ad_border',
		'ad_name',
		'ad_star',
		'ad_desc',
		'ad_price',
		'ad_btn1_url',
		'ad_btn1_text',
		'ad_btn2_url',
		'ad_btn2_text',
	];

	foreach ( $METAKEYS as $key ) {
		// 保存したい情報が渡ってきていれば更新作業に入る
		if ( isset( $_POST[ $key ] ) ) {

			$meta_val = $_POST[ $key ];
			// scriptも通すので、ad はサニタイズなし

			// DBアップデート
			update_post_meta( $the_id, $key, $meta_val );
		}
	}
}
