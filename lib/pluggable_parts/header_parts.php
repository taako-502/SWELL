<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;

/**
 * ヘッダーロゴ
 */
if ( ! function_exists( 'swl_parts__head_logo' ) ) :
	function swl_parts__head_logo( $args ) {

		$use_top_logo = $args['use_top_logo'] ?? false;
		$logo         = apply_filters( 'swell_head_logo', SWELL::site_data( 'logo' ) );
		$logo_top     = apply_filters( 'swell_head_logo_top', SWELL::site_data( 'logo_top' ) );
		$site_title   = SWELL::site_data( 'title' );

		if ( $use_top_logo ) {
			// ヘッダーオーバーレイがオンの時
			echo '<img src="' . esc_url( $logo_top ) . '" alt="' . esc_attr( $site_title ) . '" class="c-headLogo__img -top">' .
				'<img src="' . esc_url( $logo ) . '" alt="" class="c-headLogo__img -common" role="presentation">';
		} else {
			// 通常時
			echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( $site_title ) . '" class="c-headLogo__img">';
		}
	}
endif;


/**
 * グローバルナビ
 */
if ( ! function_exists( 'swl_parts__gnav' ) ) :
	function swl_parts__gnav( $args ) {
		$use_search = $args['use_search'] ?? false;
	?>
		<ul class="c-gnav">
			<?php
				wp_nav_menu([
					'container'       => '',
					'fallback_cb'     => ['SWELL_FUNC', 'default_head_menu' ],
					'theme_location'  => 'header_menu',
					'items_wrap'      => '%3$s',
					'link_before'     => '<span class="ttl">',
					'link_after'      => '</span>',
				]);
			?>
			<?php if ( $use_search ) : ?>
				<li class="menu-item c-gnav__s">
					<a href="javascript:void(0);" class="c-gnav__sBtn" data-onclick="toggleSearch" role="button">
						<i class="icon-search"></i>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	<?php
	}
endif;
