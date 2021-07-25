<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$spmenu_class = ( SWELL_FUNC::get_setting('header_layout_sp') === 'center_left' ) ? '-left': '-right';
?>
<div id="sp_menu" class="p-spMenu <?=$spmenu_class?>">
	<div class="p-spMenu__inner">
		<div class="p-spMenu__closeBtn">
			<div class="c-iconBtn -menuBtn" data-onclick="toggleMenu" aria-label="メニューを閉じる">
				<i class="c-iconBtn__icon icon-close-thin"></i>
			</div>
		</div>
		<div class="p-spMenu__body">
			<div class="c-widget__title -spmenu"><?=SWELL_FUNC::get_setting('spmenu_main_title')?></div>
			<div class="p-spMenu__nav">
			<?php
				if ( has_nav_menu('nav_sp_menu') ) :
					// echo '<div class="p-spMenu__nav">';
						wp_nav_menu([
							'container'       => '',
							'fallback_cb'     => '',
							'theme_location'  => 'nav_sp_menu',
							'items_wrap'      => '<ul class="c-spnav">%3$s</ul>',
						]);
					// echo '</div>';
				// else : 
				// 	echo '<div class="p-spMenu__nav -gnav"></div>'; //gnavクローン場所
				// endif;

				else : 
					wp_nav_menu([
						'container'       => '',
						// 'fallback_cb'     => [$this,'default_head_menu'],
						'theme_location'  => 'header_menu',
						'items_wrap'      => '<ul class="c-spnav">%3$s</ul>',
						// 'link_before' => '<span class="ttl">',
						// 'link_after' => '</span>',
					]);
				endif;

			?>
			</div>
			<?php if ( is_active_sidebar( 'sp_menu_bottom' ) ) :
				echo '<div id="sp_menu_bottom" class="p-spMenu__bottom w-spMenuBottom">';
					dynamic_sidebar( 'sp_menu_bottom' );
				echo '</div>';
			endif; ?>
		</div>
	</div>
	<div class="p-spMenu__overlay c-overlay" data-onclick="toggleMenu"></div>
</div>
