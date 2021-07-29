<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$SETTING    = SWELL_Theme::get_setting();
$menu_label = $SETTING['menu_btn_label'] ?: '';
$btn2_icon  = $SETTING['custom_btn_icon'] ?: 'icon-search';
$btn2_label = $SETTING['custom_btn_label'] ?: '';
?>
<div class="l-header__customBtn sp_">
	<?php if ( $SETTING['search_pos_sp'] === 'header' ) : ?>
		<div class="c-iconBtn" data-onclick="toggleSearch" role="button" aria-label="検索ボタン">
			<i class="c-iconBtn__icon <?=esc_attr( $btn2_icon )?>"></i>
			<?php if ( $btn2_label !== '' ) : ?>
				<span class="c-iconBtn__label"><?=esc_html( $btn2_label )?></span>
			<?php endif; ?>
		</div>
	<?php elseif ( $SETTING['custom_btn_url'] !== '' ) : ?>
		<a href="<?=esc_url( $SETTING['custom_btn_url'] )?>" class="c-iconBtn">
			<i class="c-iconBtn__icon <?=esc_attr( $btn2_icon )?>"></i>
			<?php if ( $btn2_label !== '' ) : ?>
				<span class="c-iconBtn__label"><?=esc_html( $btn2_label )?></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
</div>
<div class="l-header__menuBtn sp_">
	<div class="c-iconBtn -menuBtn" data-onclick="toggleMenu" role="button" aria-label="メニューボタン">
		<i class="c-iconBtn__icon icon-menu-thin"></i>
		<?php if ( $menu_label ) : ?>
			<span class="c-iconBtn__label"><?=esc_html( $menu_label )?></span>
		<?php endif; ?>
	</div>
</div>
