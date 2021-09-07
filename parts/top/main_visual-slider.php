<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * メインビジュアル
 */
$slider_images = $variable;

// 設定
$SETTING       = SWELL_Theme::get_setting();
$is_fix_text   = $SETTING['mv_fix_text'];
$mv_img_filter = $SETTING['mv_img_filter'];

?>
<div class="p-mainVisual__inner swiper-container">
	<div class="swiper-wrapper">
	<?php
		$ct = 0;
		foreach ( $slider_images as $i => $value ) :
		++$i;
		++$ct;
	?>
		<div class="p-mainVisual__slide swiper-slide c-filterLayer -<?=esc_attr( $mv_img_filter )?>">
			<picture class="p-mainVisual__imgLayer c-filterLayer__img">
				<?php echo SWELL_Theme::get_mv_slide_img( $i ); // phpcs:ignore?>
			</picture>
	<?php
		// 固定テキスト設定でなければ、各スライドにテキストを出力
		if ( ! $is_fix_text ) :
			$slide_title = $SETTING[ "slider{$i}_title" ];
			$slide_text  = $SETTING[ "slider{$i}_text" ];
			$slide_url   = $SETTING[ "slider{$i}_url" ];
			$txtpos      = $SETTING[ "slider{$i}_txtpos" ];
			$txtcol      = $SETTING[ "slider{$i}_txtcol" ];
			$btn_text    = $SETTING[ "slider{$i}_btn_text" ];
			$btntype     = $SETTING[ "slider{$i}_btntype" ];
			$btncol      = $SETTING[ "slider{$i}_btncol" ];
			$shadowcol   = $SETTING[ "slider{$i}_shadowcol" ];
			$parts_id    = (int) $SETTING[ "slider{$i}_parts_id" ];
			$text_style  = SWELL_Theme::get_mv_text_style( $txtcol, $shadowcol );
			?>
				<div class="p-mainVisual__textLayer l-parent l-parent l-container u-ta-<?=esc_attr( $txtpos )?>" style="<?=esc_attr( $text_style )?>">
			<?php
				// キャッチコピー
				if ( '' !== $slide_title ) {
				echo '<div class="p-mainVisual__slideTitle">' . wp_kses( $slide_title, SWELL_Theme::$allowed_text_html ) . '</div>';
				}

				// サブコピー
				if ( '' !== $slide_text ) {
				echo '<div class="p-mainVisual__slideText">' . wp_kses( nl2br( $slide_text ), SWELL_Theme::$allowed_text_html ) . '</div>';
					}

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
						echo '<a href="' . esc_url( $slide_url ) . '" class="p-mainVisual__slideLink" ' . SWELL_Theme::get_link_target( $slide_url ) . '></a>';
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
		$text_style  = SWELL_Theme::get_mv_text_style( $txtcol, $shadowcol );
	?>
		<div class="p-mainVisual__textLayer l-parent l-container u-ta-<?=esc_attr( $txtpos )?>" style="<?=esc_attr( $text_style )?>">
	<?php
		// キャッチコピー
		if ( '' !== $slide_title ) {
			echo '<div class="p-mainVisual__slideTitle">' . wp_kses( $slide_title, SWELL_Theme::$allowed_text_html ) . '</div>';
			}

		// サブコピー
		if ( '' !== $slide_text ) {
			echo '<div class="p-mainVisual__slideText">' . wp_kses( nl2br( $slide_text ), SWELL_Theme::$allowed_text_html ) . '</div>';
			}

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
				echo '<a href="' . esc_url( $slide_url ) . '" class="p-mainVisual__slideLink"' . SWELL_Theme::get_link_target( $slide_url ) . '></a>';
				endif;
			?>
		</div>
		<?php
		endif;
		if ( $SETTING['mv_on_scroll'] ) \SWELL_Theme::pluggable_parts( 'scroll_arrow', ['color' => $SETTING['slider1_txtcol'] ] );
	?>
</div>
