<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$is_loop = SWELL_Theme::get_setting( 'sp_head_nav_loop' );
?>
<div class="l-header__spNav swiper-container" data-loop="<?php echo $is_loop ? '1' : '0'; ?>">
	<ul class="p-spHeadMenu swiper-wrapper">
		<?php
			wp_nav_menu(
				[
					'container'       => '',
					'fallback_cb'     => '',
					'theme_location'  => 'sp_head_menu',
					'items_wrap'      => '%3$s',
					'link_before'     => '<span>',
					'link_after'      => '</span>',
					'depth'           => 1,
				]
			);
		?>
	</ul>
</div>
