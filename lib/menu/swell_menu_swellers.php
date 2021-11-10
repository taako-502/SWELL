<?php
use \SWELL_Theme as SWELL;
if ( ! defined( 'ABSPATH' ) ) exit;

// メッセージ用
$green_message = '';
if ( isset( $_POST['swell_afi_id'] ) ) {

	// nonceチェック
	if ( ! SWELL::check_nonce( '_afi_id' ) ) wp_die( '不正アクセスです。' );

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
	<h1 class="swell_settings__title">SWELL アクティベート設定</h1>
	<hr class="wp-header-end">
	<div class="swell_settings__body">
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<td>
							<h3 style="margin:0 0 1em">ユーザー認証</h3>
							<div>
								<label for="sweller_email">メールアドレス</label>
								<input type="text" name="sweller_email" id="sweller_email" class="regular-text" size="40" value="<?=esc_attr( get_option( 'sweller_email' ) )?>" placeholder="SWELLERS' 会員アドレスを入力">
							</div>
							<?php if ( 'ok' === SWELL::$licence_status ) : ?>
								<!-- <style>.submit.-to-swlr-activate{display:none}</style> -->
								<p class="swlr-status -ok" style="color: #18992d;"><span class="dashicons dashicons-yes"></span> 認証成功</p>
							<?php elseif ( 'waiting' === SWELL::$licence_status ) : ?>
								<p class="swlr-status -waiting" style="color: #e17622;">
									<span class="dashicons dashicons-warning"></span> 認証URLをメールアドレスへ送信しました。3分以内にURLへアクセスしてください。
									<br>
									認証完了後、「アクティベートを完了」ボタンを押してください。
								</p>
							<?php else : ?>
								<p class="swlr-status -ng">
									<span style="color: #f0001f;"><span class="dashicons dashicons-no"></span> 未認証</span>
									<small class="description">（メールアドレスの認証が完了するまで、最新版へのアップデートが制限されます。）</small>
								</p>
							<?php endif; ?>

							<?php if ( 'ok' !== SWELL::$licence_status ) : ?>
								<p class="description u-mt-20">
									※ SWELLユーザー限定サイト、「<a href="https://users.swell-theme.com/" target="_blank" rel="noopener">SWELLERS'</a>」への登録が必要です。
								</p>
								<p class="description u-mt-5">
									※ 購入したのに認証が失敗する場合や認証用のアドレスを変更したい場合は、<a href="https://users.swell-theme.com/contact/" target="_blank" rel="noopener">お問い合わせフォーム</a>からご連絡ください。
								</p>
							<?php endif; ?>

							<p class="submit u-mt-10 -to-swlr-activate" style="padding:0">
								<input type="submit" name="submit" class="button button-primary" value="<?php echo 'waiting' === SWELL::$licence_status ? 'アクティベートを完了' : '認証リクエストを送信'; ?>">
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
					<!-- <th scope="row"></th> -->
					<td>
						<div>
							<label for="swell_afi_id">SWELLERS' ID</label>
							<input type="text" name="swell_afi_id" id="swell_afi_id" class="regular-text" size="40" value="<?=esc_attr( get_option( 'swell_afi_id' ) )?>">
						</div>
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
