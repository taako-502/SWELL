<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$spmenu_class = ( 'center_left' === SWELL_Theme::get_setting( 'header_layout_sp' ) ) ? '-left' : '-right';
?>
<div id="sp_menu" class="p-spMenu <?=esc_attr( $spmenu_class )?>">
	<div class="p-spMenu__inner">
		<div class="p-spMenu__closeBtn">
			<div class="c-iconBtn -menuBtn" data-onclick="toggleMenu" aria-label="メニューを閉じる">
				<i class="c-iconBtn__icon icon-close-thin"></i>
			</div>
		</div>
		<div class="p-spMenu__body">
			<div class="c-widget__title -spmenu">
				<?=wp_kses( SWELL_Theme::get_setting( 'spmenu_main_title' ), SWELL_Theme::$allowed_text_html )?>
			</div>
			<div class="p-spMenu__nav">
				<?php
					if ( has_nav_menu( 'nav_sp_menu' ) ) :
						wp_nav_menu([
							'container'      => '',
							'fallback_cb'    => '',
							'theme_location' => 'nav_sp_menu',
							'items_wrap'     => '<ul class="c-spnav">%3$s</ul>',
						]);
					else :
						wp_nav_menu([
							'container'      => '',
							'theme_location' => 'header_menu',
							'items_wrap'     => '<ul class="c-spnav">%3$s</ul>',
						]);
					endif;
				?>
			</div>
			<?php
				if ( is_active_sidebar( 'sp_menu_bottom' ) ) :
				echo '<div id="sp_menu_bottom" class="p-spMenu__bottom w-spMenuBottom">';
				dynamic_sidebar( 'sp_menu_bottom' );
				echo '</div>';
				endif;
			?>
		</div>
	</div>
	<div class="p-spMenu__overlay c-overlay" data-onclick="toggleMenu"></div>
</div>
