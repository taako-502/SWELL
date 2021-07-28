<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$SETTING        = SWELL_Theme::get_setting();
$phrase_pos     = $SETTING['phrase_pos'];
$show_icon_list = $SETTING['show_icon_list'];

if ( $show_icon_list || $phrase_pos === 'head_bar' ) :
?>
	<div class="l-header__bar">
		<div class="l-header__barInner l-container">
			<?php
				if ( $phrase_pos === 'head_bar' ) :
					echo '<div class="c-catchphrase">' . esc_html( SWELL_Theme::site_data( 'catchphrase' ) ) . '</div>';
				endif;

				if ( $show_icon_list ) :
					$sns_settings = SWELL_Theme::get_sns_settings();
					if ( $SETTING['search_pos'] === 'head_bar' ) :
						$sns_settings['search'] = 1;
					endif;
					if ( ! empty( $sns_settings ) ) :
						$list_data = [
							'list_data' => $sns_settings,
							'ul_class'  => '',
							'fz_class'  => 'u-fz-14',
						];
						SWELL_Theme::get_parts( 'parts/icon_list', $list_data );
					endif;
				endif;
			?>
		</div>
	</div>
<?php endif; ?>
