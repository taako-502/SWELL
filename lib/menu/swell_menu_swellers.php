<?php
use \SWELL_Theme as SWELL;
if ( ! defined( 'ABSPATH' ) ) exit;

// メッセージ用
$green_message = '';

if ( isset( $_POST['swell_afi_id'] ) ) {

	// nonceキーチェック
	if ( ! SWELL::check_nonce( '_afi_id' ) ) {
		echo '不正アクセスです。';
		exit;
	}

	// 設定更新
	update_option( 'swell_afi_id', $_POST['swell_afi_id'] );
	$green_message = '設定を保存しました';

}

// メッセージの表示
if ( $green_message ) {
	echo '<div class="notice updated is-dismissible"><p>' . esc_html( $green_message ) . '</p></div>';
}

?>
<div id="swell_setting_page" class="swell_settings">
	<h1 class="swell_settings__title">SWELLERS' 設定</h1>
	<hr class="wp-header-end">
	<div class="swell_settings__body">
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row"><label for="swell_afi_id">SWELLERS' ID</label></th>
					<td>
						<input type="text" name="swell_afi_id" id="swell_afi_id" class="regular-text" size="40" value="<?=esc_attr( get_option( 'swell_afi_id' ) )?>">
						<p class="description">
							<a href="https://u.swell-theme.com/" target="_blank" rel="noopener">SWELLERS'</a>の会員IDを入力してください。
						</p>
					</td>
				</tr>
				</tbody>
			</table>
			<?php SWELL::set_nonce_field( '_afi_id' ); ?>
			<p class="submit">
				<input type="submit" name="submit" class="button button-primary" value="保存する">
			</p>
		</form>
	</div>
</div>
