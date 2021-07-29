<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING      = SWELL_Theme::get_setting();
$header_class = SWELL_Theme::get_header_class(); // ヘッダーとfixバーへのクラクラス

// お知らせバー（上部表示）
if ( $SETTING['info_bar_pos'] === 'head_top' ) SWELL_Theme::get_parts( 'parts/header/info_bar' );
?>
<header id="header" class="l-header <?=esc_attr( $header_class )?>" data-spfix="<?=$SETTING['fix_header_sp'] ? '1' : '0'?>">
	<?php SWELL_Theme::get_parts( 'parts/header/head_bar' ); // ヘッダーバー ?>
	<div class="l-header__inner l-container">
		<div class="l-header__logo">
			<?php echo SWELL_PARTS::head_logo( $SETTING['header_transparent'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $SETTING['phrase_pos'] === 'head_wrap' ) : ?>
				<div class="c-catchphrase u-thin"><?=esc_html( SWELL_Theme::site_data( 'catchphrase' ) )?></div>
			<?php endif; ?>
		</div>
		<nav id="gnav" class="l-header__gnav c-gnavWrap">
			<?php
				SWELL_Theme::pluggable_parts( 'gnav', [
					'use_search' => 'head_menu' === $SETTING['search_pos'],
				] );
			?>
		</nav>
		<?php if ( is_active_sidebar( 'head_box' ) ) : // ヘッダー内ウィジェット ?>
			<div class="w-header pc_">
				<div class="w-header__inner">
					<?php dynamic_sidebar( 'head_box' ); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php SWELL_Theme::get_parts( 'parts/header/sp_btns' ); // メニューボタン & カスタムボタン ?>
	</div>
	<?php
	if ( has_nav_menu( 'sp_head_menu' ) ) : // SP用ヘッダーメニュー
		SWELL_Theme::get_parts( 'parts/header/sp_head_nav', $SETTING['sp_head_nav_loop'] );
	endif;
	?>
</header>
<?php

// FIXヘッダー
if ( $SETTING['fix_header'] ) SWELL_Theme::get_parts( 'parts/header/fix_header', $header_class );

// お知らせバー（下部表示）
if ( $SETTING['info_bar_pos'] === 'head_bottom' ) SWELL_Theme::get_parts( 'parts/header/info_bar' );
