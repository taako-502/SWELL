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


if ( isset( $_POST['sweller_email'] ) ) {

	// nonceキーチェック
	if ( ! SWELL::check_nonce( '_email' ) ) {
		echo '不正アクセスです。';
		exit;
	}

	// 先にDB程度更新
	update_option( 'sweller_email', $_POST['sweller_email'] );
	$green_message = '設定を保存しました';

	SWELL::check_swlr_licence( $_POST['sweller_email'], 1 );
}

// メッセージの表示
if ( $green_message ) {
	echo '<div class="notice updated is-dismissible"><p>' . esc_html( $green_message ) . '</p></div>';
}

?>
<div id="swell_setting_page" class="swell_settings">
	<h1 class="swell_settings__title">SWELL ユーザー認証</h1>
	<hr class="wp-header-end">
	<div class="swell_settings__body">
		<form action="" method="post">
			<?php
				$is_user = 'ok' === SWELL::$licence_status;
			?>
			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row">
						<label for="sweller_email">購入アドレス</label>
					</th>
					<td>
						<input type="text" name="sweller_email" id="sweller_email" class="regular-text" size="40" value="<?=esc_attr( get_option( 'sweller_email' ) )?>" placeholder="SWELL購入時のアドレスを入力してください。">

						<?php if ( $is_user ) : ?>
							<p class="swlr-status -ok" style="color: #18992d;"><span class="dashicons dashicons-yes"></span> 認証成功</p>
						<?php else : ?>
							<p class="swlr-status -ng">
								<span style="color: #f0001f;"><span class="dashicons dashicons-no"></span> 未認証</span>
								<small class="description">（メールアドレスの認証が完了するまで、最新版へのアップデートが制限されます。）</small>
							</p>
						<?php endif; ?>
						<p class="description u-mt-20">
							※ 購入したのに認証が失敗する場合や認証用のアドレスを変更したい場合は、<a href="https://users.swell-theme.com/contact/" target="_blank" rel="noopener">お問い合わせフォーム</a>からご連絡ください。
						</p>
						<p class="submit u-mt-10" style="padding:0">
							<input type="submit" name="submit" class="button button-primary" value="認証する">
						</p>
					</td>
				</tr>
				</tbody>
			</table>
			<?php SWELL::set_nonce_field( '_email' ); ?>
		</form>

		<form action="" method="post" class="u-mt-20">
			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row"><label for="swell_afi_id">SWELLERS' ID</label></th>
					<td>
						<input type="text" name="swell_afi_id" id="swell_afi_id" class="regular-text" size="40" value="<?=esc_attr( get_option( 'swell_afi_id' ) )?>">
						<p class="description">
							このサイトでSWELLのアフィリエイトプログラムを利用したい場合は、<a href="https://u.swell-theme.com/" target="_blank" rel="noopener">SWELLERS'</a>の会員IDを入力してください。
						</p>
						<p class="submit u-mt-10" style="padding:0">
							<input type="submit" name="submit" class="button button-primary" value="保存する">
						</p>
					</td>
				</tr>
				</tbody>
			</table>
			<?php SWELL::set_nonce_field( '_afi_id' ); ?>
		</form>
	</div>
</div>
