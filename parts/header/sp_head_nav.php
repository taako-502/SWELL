<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$is_loop = $variable ?: false;
$sp_nav_class = 'l-header__spNav swiper-container';
if ( ! $is_loop ) $sp_nav_class .= ' -loop-off';
?>
<div class="<?=$sp_nav_class?>">
	<ul class="p-spHeadMenu swiper-wrapper">
		<?php wp_nav_menu(
			[
				'container'       => '',
				'fallback_cb'     => '',
				'theme_location'  => 'sp_head_menu',
				'items_wrap'      => '%3$s',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'depth' => 1,
			]
		); ?>
	</ul>
</div>
