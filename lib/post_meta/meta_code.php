<?php
namespace SWELL_Theme\Meta\Code;

use \SWELL_Theme as SWELL;
use \SWELL_THEME\Parts\Setting_Field as Field;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\hook_add_meta_box', 1 );
add_action( 'save_post', __NAMESPACE__ . '\hook_save_post' );


/**
 * メタボックス追加
 */
function hook_add_meta_box() {
	add_meta_box(
		'swell_post_meta_code',       // メタボックスのID名(html)
		'カスタムCSS & JS',     // メタボックスのタイトル
		__NAMESPACE__ . '\code_meta_cb',
		['post', 'page', 'lp' ],
		'normal',                     // 表示場所 : 'normal', 'advanced', 'side'
		'high',                       // 表示優先度 : 'high', 'core', 'default' または 'low'
		null                          // $callback_args
	);
}


/**
 * 【SWELL】カスタムCSS & JS
 */
function code_meta_cb( $post ) {
	$code_metas = [
		'swell_meta_css' => [
			'label' => 'CSS',
			'desc'  => 'wp_head(<head>内)で出力されます。',
			'type'  => 'textarea',
		],
		'swell_meta_js' => [
			'label' => 'JS',
			'desc'  => 'wp_footer(</body>前)で出力されます。',
			'type'  => 'textarea',
		],
	];

	SWELL::set_nonce_field( '_meta_code' );

	// @codingStandardsIgnoreStart
	?>
	<div id="swell_metabox_css" class="swl-meta -code">
	<?php
		foreach ( $code_metas as $key => $data ) :
		$meta_val = get_post_meta( $post->ID, $key, true );
		$desc     = esc_html( $data['desc'] );
		?>
		<div class="swl-meta__item">
			<div class="swl-meta__subttl"><?=$data['label']?></div>
			<div class="swl-meta__field">
				<textarea id="<?=$key?>" name="<?=$key?>" rows="8"><?=$meta_val?></textarea>
				<?php if ( $desc ) : ?>
					<p class="swl-meta__desc">
						<?=$desc?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
<?php
}


/**
 * 保存処理
 */
function hook_save_post( $post_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_code' ) ) {
		return;
	}

	// 自動保存時には保存しないように
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// 権限確認
	if ( ! SWELL::check_user_can_edit( $post_id ) ) {
		return;
	}


	$METAKEYS = [
		'swell_meta_css',
		'swell_meta_js',
	];

	foreach ( $METAKEYS as $key ) {
		// 保存したい情報が渡ってきていれば更新作業に入る
		if ( isset( $_POST[ $key ] ) ) {

			$meta_val = $_POST[ $key ]; // コードなのでサニタイズなし

			// DBアップデート
			update_post_meta( $post_id, $key, $meta_val );

		}
	}

}
