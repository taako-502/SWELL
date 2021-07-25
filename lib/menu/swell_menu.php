<?php

use \SWELL_THEME\Admin_Menu;
if ( ! defined( 'ABSPATH' ) ) exit;

$setting_tabs = [
	'speed'     => __( 'Speeding up', 'swell' ),
	'structure' => __( 'Structured Data', 'swell' ),
	'jquery'    => 'jQuery',
	'fa'        => 'Font Awesome',
	'remove'    => __( 'Outage', 'swell' ),
	'ad'        => __( 'Ad Code', 'swell' ),
	'reset'     => __( 'Reset', 'swell' ),
];

// メッセージ
$green_message = '';

// Settings API は $_REQUEST でデータが渡ってくる
if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] ) {
	$green_message = __( 'Your settings have been saved.', 'swell' );

	// CSSキャッシュ削除
	\SWELL_FUNC::clear_cache( \SWELL_Theme::$cache_keys['style'] );
}

if ( $green_message ) {
	echo '<div class="notice updated is-dismissible"><p>' . esc_html( $green_message ) . '</p></div>';
}

// 現在のタブを取得
$now_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'speed'; //phpcs:ignore
// $now_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : array_key_first( $setting_tabs ); // v3.0~

?>

<div id="swell_setting_page" class="swell_settings">
	<h1 class="swell_settings__title"><?=esc_html__( 'SWELL Settings', 'swell' )?></h1>
	<p class="swell_settings__page_desc">
		<?php
			echo sprintf(
				esc_html__( 'You can set the Design and Color from the "%s" page.', 'swell' ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=swell_settings_editor' ) ) . '">' . esc_html__( 'Editor Settings', 'swell' ) . '</a>'
			);
		?>
	</p>
	<hr class="wp-header-end">
	<div class="swell_settings__tabs">
		<div class="nav-tab-wrapper">
			<?php
				foreach ( $setting_tabs as $key => $val ) :

				$tab_url   = admin_url( 'admin.php?page=' . \SWELL_Theme::MENU_SLUGS['basic'] ) . '&tab=' . $key;
				$nav_class = ( $now_tab === $key ) ? 'nav-tab act_' : 'nav-tab';
				echo '<a href="' . esc_url( $tab_url ) . '" class="' . esc_attr( $nav_class ) . '">' . esc_html( $val ) . '</a>';

				// $nav_class = ( reset( $setting_tabs ) === $val ) ? 'nav-tab act_' : 'nav-tab';
				// echo '<a href="#' . $key . '" class="' . $nav_class . '">' . $val . '</a>';

				endforeach;
			?>
		</div>
	</div>
	<div class="swell_settings__body">
		<form method="POST" action="options.php">
		<?php
			foreach ( $setting_tabs as $key => $val ) :
			// $tab_class = ( reset( $setting_tabs ) === $val ) ? 'tab-contents act_' : 'tab-contents';
			$tab_class = ( $now_tab === $key ) ? 'tab-contents act_' : 'tab-contents';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div id="' . $key . '" class="' . $tab_class . '">';

			// タブコンテンツの読み込み
			if ( file_exists( T_DIRE . '/lib/menu/tabs/' . $key . '.php' ) ) :
				include_once T_DIRE . '/lib/menu/tabs/' . $key . '.php';
				else :
					// ファイルなければ単純に do_settings_sections
					do_settings_sections( Admin_Menu::PAGE_NAMES[ $key ] );
					submit_button( '', 'primary large', 'submit_' . $key );
				endif;

				echo '</div>';
			endforeach;

			settings_fields( Admin_Menu::SETTING_GROUPS['options'] ); // settings_fieldsがnonceなどを出力するだけ
		?>
		</form>
	</div>
</div>
