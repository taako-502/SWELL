<?php
use \SWELL_Theme as SWELL;
if ( ! defined( 'ABSPATH' ) ) exit;

// メッセージ用
$green_message = '';

// ads.txtのパス
$root_path     = $_SERVER['DOCUMENT_ROOT'] ?? '';
$ads_text_path = $root_path . '/ads.txt';

if ( isset( $_POST['ads_txt'] ) ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_ads_txt' ) ) {
		echo '不正アクセスです。';
		exit;
	}


	// ファイル編集
	$new_text = trim( $_POST['ads_txt'] );

	// まだ ads.txt ファイルがなければ、空で作成
	if ( ! file_exists( $ads_text_path ) ) {
		echo 'no File!';
		file_put_contents( $ads_text_path, '' );
	}

	// 内容を更新
	file_put_contents( $ads_text_path, $new_text );

	$green_message = 'ads.txtファイルを編集しました';

}

// メッセージの表示
if ( $green_message ) {
	echo '<div class="notice updated is-dismissible"><p>' . esc_html( $green_message ) . '</p></div>';
}

$ads_content = file_exists( $ads_text_path ) ? file_get_contents( $ads_text_path ) : '';

?>
<div id="swell_setting_page" class="swell_settings">
	<h1 class="swell_settings__title">ads.txt編集</h1>
	<hr class="wp-header-end">
	<div class="swell_settings__body">
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row"><label for="ads_txt"><code>ads.txt</code>ファイル：</label></th>
					<td>
						<textarea name="ads_txt" id="ads_txt" cols="90" rows="10"><?=esc_html( $ads_content )?></textarea>
						<p class="description">
							Google AdSenseの形式：<code>google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0</code><br>
							<a href="https://support.google.com/adsense/answer/7532444?hl=ja" target="_blank" rel="noopener">詳しくはこちら</a>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
			<?php SWELL::set_nonce_field( '_ads_txt' ); ?>
			<p class="submit">
				<input type="submit" name="submit" id="ads_submit" class="button button-primary" value="ads.txtを変更する">
			</p>
		</form>
	</div>
</div>
