<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;

/**
 * ヘッダーロゴ
 */
if ( ! function_exists( 'swl_parts__head_logo' ) ) :
	function swl_parts__head_logo() {

		$logo_id     = SWELL::site_data( 'logo_id' );
		$logo_top_id = SWELL::site_data( 'logo_top_id' ) ?: $logo_id;

		// トップページのヒーロヘッダーを利用するかどうか。
		$use_overlay_header = ( SWELL::is_top() && ! is_paged() && SWELL::get_setting( 'header_transparent' ) !== 'no' );

		// 後方互換用。ロゴURLが直接指定されている場合。
		if ( has_filter( 'swell_head_logo' ) ) {
			$logo     = apply_filters( 'swell_head_logo', SWELL::site_data( 'logo_url' ) );
			$logo_top = apply_filters( 'swell_head_logo_top', wp_get_attachment_url( $logo_top_id ) );

			if ( $use_overlay_header ) {
				echo '<img src="' . esc_url( $logo_top ) . '" alt="' . esc_attr( SWELL::site_data( 'title' ) ) . '" class="c-headLogo__img -top">' .
					'<img src="' . esc_url( $logo ) . '" alt="" class="c-headLogo__img -common" aria-hidden="true">';
			} else {
				// 通常時
				echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( SWELL::site_data( 'title' ) ) . '" class="c-headLogo__img">';
			}

			return;
		}

		$logo_sizes = apply_filters( 'swell_head_logo_sizes', '(max-width: 999px) 50vw, 800px' );

		if ( ! $use_overlay_header ) {
			// 通常時

			$return = wp_get_attachment_image( $logo_id, 'full', false, [
				'class'   => 'c-headLogo__img',
				'sizes'   => $logo_sizes,
				'alt'     => SWELL::site_data( 'title' ),
				'loading' => 'eager',
			] );

		} else {
			// ヘッダーオーバーレイ有効時
			$logo_top = wp_get_attachment_image( $logo_top_id, 'full', false, [
				'class'   => 'c-headLogo__img -top',
				'sizes'   => $logo_sizes,
				'alt'     => SWELL::site_data( 'title' ),
				'loading' => 'eager',
			] );

			$common_logo = wp_get_attachment_image( $logo_id, 'full', false, [
				'class'   => 'c-headLogo__img -common',
				'sizes'   => $logo_sizes,
				'alt'     => '',
				'loading' => 'lazy',
			] );
			$common_logo = str_replace( '<img ', '<img aria-hidden="true" ', $common_logo );

			$return = $logo_top . $common_logo;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $return;
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
					'fallback_cb'     => ['SWELL_Theme', 'default_head_menu' ],
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
