<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * メインビジュアル
 */
$slider_images = $variable;

// 設定
$SETTING       = SWELL_FUNC::get_setting();
$is_fix_text   = $SETTING['mv_fix_text'];
$mv_img_filter = $SETTING['mv_img_filter']

?>
<div class="p-mainVisual__inner swiper-container">
	<div class="swiper-wrapper">
	<?php
		$ct = 0;
		foreach ( $slider_images as $i => $value ) :
			++$i;
			++$ct;

			// スライダー画像
			$pc_img    = $SETTING[ 'slider' . $i . '_img' ];
			$pc_img_id = ( 1 === $ct ) ? attachment_url_to_postid( $pc_img ) : 0; // １枚目の時だけsrcにはmediumサイズ
			$pc_img_s  = ( $pc_img_id ) ? wp_get_attachment_image_url( $pc_img_id, 'medium' ) : \SWELL_Theme::$placeholder;

			$sp_img    = $SETTING[ 'slider' . $i . '_img_sp' ] ?: $pc_img;
			$sp_img_id = ( 1 === $ct ) ? attachment_url_to_postid( $sp_img ) : 0;
			$sp_img_s  = ( $sp_img_id ) ? wp_get_attachment_image_url( $sp_img_id, 'medium' ) : \SWELL_Theme::$placeholder;

			$img_alt = $SETTING[ 'slider' . $i . '_alt' ] ?: '';
	?>
		<div class="p-mainVisual__slide swiper-slide c-filterLayer -<?=esc_attr( $mv_img_filter )?>">
			<picture class="p-mainVisual__imgLayer c-filterLayer__img">
				<source media="(max-width: 959px)" srcset="<?=esc_attr( $sp_img_s )?>" data-srcset="<?=esc_attr( $sp_img )?>">
				<img src="<?=esc_url( $pc_img_s )?>" data-src="<?=esc_attr( $pc_img )?>" alt="<?=esc_attr( $img_alt )?>" class="p-mainVisual__img lazyload">
			</picture>
			<?php
				// 固定テキスト設定でなければ、各スライドにテキストを出力
				if ( ! $is_fix_text ) :
					$slide_title = $SETTING[ 'slider' . $i . '_title' ];
					$slide_text  = $SETTING[ 'slider' . $i . '_text' ];
					$slide_url   = $SETTING[ 'slider' . $i . '_url' ];
					$txtpos      = $SETTING[ 'slider' . $i . '_txtpos' ];
					$txtcol      = $SETTING[ 'slider' . $i . '_txtcol' ];
					$btn_text    = $SETTING[ 'slider' . $i . '_btn_text' ];
					$btntype     = $SETTING[ 'slider' . $i . '_btntype' ];
					$btncol      = $SETTING[ 'slider' . $i . '_btncol' ];
					$shadowcol   = $SETTING[ 'slider' . $i . '_shadowcol' ];
					$parts_id    = (int) $SETTING[ 'slider' . $i . '_parts_id' ];
					$text_style  = SWELL_FUNC::get_mv_text_style( $txtcol, $shadowcol );
			?>
				<div class="p-mainVisual__textLayer l-container u-ta-<?=esc_attr( $txtpos )?>" style="<?=esc_attr( $text_style )?>">
					<?php
						$slide_ttl = '';

						if ( '' !== $slide_title )
							$slide_ttl .= '<div class="p-mainVisual__slideTitle">' . $slide_title . '</div>';

						if ( '' !== $slide_text )
							$slide_ttl .= '<div class="p-mainVisual__slideText">' . nl2br( $slide_text ) . '</div>';

						echo wp_kses_post( $slide_ttl );

						// ブログパーツ
						if ( $parts_id ) echo do_shortcode( '[blog_parts id="' . $parts_id . '"]' );

						if ( '' !== $slide_url && '' !== $btn_text ) :
							// ボタンあり
							$btn_args = [
								'href'     => $slide_url,
								'text'     => $btn_text,
								'btn_type' => $btntype,
								'btn_col'  => $btncol,
							];

							\SWELL_Theme::pluggable_parts( 'mv_btn', $btn_args );

						elseif ( $slide_url ) :
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '<a href="' . esc_url( $slide_url ) . '" class="p-mainVisual__slideLink" ' . SWELL_FUNC::get_link_target( $slide_url ) . '></a>';
						endif;
					?>
					</div>
			<?php
				endif;
			?>
		</div><!-- / swiper-slide -->
		<?php endforeach; ?>
	</div><!-- / swiper-wrapper -->
	<?php if ( $SETTING['mv_on_pagination'] ) : ?>
		<div class="swiper-pagination"></div>
	<?php endif; ?>

	<?php if ( $SETTING['mv_on_nav'] ) : ?>
		<div class="swiper-button-prev" tabindex="0" role="button" aria-label="Previous slide">
			<svg x="0px" y="0px" viewBox="0 0 136 346" xml:space="preserve">
				<polyline points="123.2,334.2 12.2,173.2 123.8,11.8 " fill="none" stroke="#fff" stroke-width="12" stroke-miterlimit="10"></polyline>
			</svg>
		</div>
		<div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide">
			<svg x="0px" y="0px" viewBox="0 0 136 346" xml:space="preserve">
				<polyline class="st0" points="12.8,11.8 123.8,172.8 12.2,334.2" fill="none" stroke="#fff" stroke-width="12" stroke-miterlimit="10"></polyline>
			</svg>
		</div>
	<?php endif; ?>
	<?php
		// 固定テキスト設定の場合は外側にスライダー１のテキストを出力
		if ( $is_fix_text ) :
			$slide_title = $SETTING['slider1_title'];
			$slide_text  = $SETTING['slider1_text'];
			$slide_url   = $SETTING['slider1_url'];
			$txtpos      = $SETTING['slider1_txtpos'];
			$txtcol      = $SETTING['slider1_txtcol'];
			$btn_text    = $SETTING['slider1_btn_text'];
			$btntype     = $SETTING['slider1_btntype'];
			$btncol      = $SETTING['slider1_btncol'];
			$shadowcol   = $SETTING['slider1_shadowcol'];
			$text_style  = SWELL_FUNC::get_mv_text_style( $txtcol, $shadowcol );
	?>
		<div class="p-mainVisual__textLayer l-container u-ta-<?=esc_attr( $txtpos )?>" style="<?=esc_attr( $text_style )?>">
			<?php
				$slide_ttl = '';

				if ( '' !== $slide_title )
					$slide_ttl .= '<div class="p-mainVisual__slideTitle">' . $slide_title . '</div>';

				if ( '' !== $slide_text )
					$slide_ttl .= '<div class="p-mainVisual__slideText">' . nl2br( $slide_text ) . '</div>';

				echo wp_kses_post( $slide_ttl );

				if ( $btn_text && $slide_url ) :
					// ボタンあり
					$btn_args = [
						'href'     => $slide_url,
						'text'     => $btn_text,
						'btn_type' => $btntype,
						'btn_col'  => $btncol,
					];
					\SWELL_Theme::pluggable_parts( 'mv_btn', $btn_args );

				elseif ( $slide_url ) :
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<a href="' . esc_url( $slide_url ) . '" class="p-mainVisual__slideLink"' . SWELL_FUNC::get_link_target( $slide_url ) . '></a>';
				endif;
			?>
		</div>
		<?php
		endif;
		if ( $SETTING['mv_on_scroll'] ) \SWELL_Theme::pluggable_parts( 'scroll_arrow', ['color' => $SETTING['slider1_txtcol'] ] );
	?>
</div>
