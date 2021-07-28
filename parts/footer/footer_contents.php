<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="l-footer__inner">
	<?php
		$SETTING = SWELL_Theme::get_setting(); // セッティング情報取得
		SWELL_Theme::get_parts( 'parts/footer/foot_widget' );
	?>
		<div class="l-footer__foot">
			<div class="l-container">
				<?php
					if ( $SETTING['show_foot_icon_list'] ) :
						$sns_settings = SWELL_Theme::get_sns_settings();
						if ( ! empty( $sns_settings ) ) :
							$list_data = [
								// 'ul_class' => '',
								'list_data' => $sns_settings,
								'fz_class'  => 'u-fz-14',
							];
							SWELL_Theme::get_parts( 'parts/icon_list', $list_data );
						endif;
					endif;
				?>
			<?php
				wp_nav_menu([
					'container'       => false,
					'fallback_cb'     => '',
					'theme_location'  => 'footer_menu',
					'items_wrap'      => '<ul class="l-footer__nav">%3$s</ul>',
					'link_before'     => '',
					'link_after'      => '',
				]);
			?>
			<p class="copyright">
				<span lang="en">&copy;</span>
				<?=wp_kses( $SETTING['copyright'], SWELL_Theme::$allowed_text_html )?>
			</p>
			<?php do_action( 'swell_after_copyright' ); ?>
		</div>
	</div>
</div>
