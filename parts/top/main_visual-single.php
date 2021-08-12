<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * メインビジュアル (画像１枚の時)
 */
$SETTING       = SWELL_Theme::get_setting();
$use_lazysizes = SWELL_Theme::is_use( 'lazysizes' );

// altテキスト
$img_alt = $SETTING['slider1_alt'] ?: '';

// PC画像
$picture_img = '';
$pc_img      = $SETTING['slider1_img'] ?: '';
if ( $use_lazysizes ) {
	$pc_img_id = attachment_url_to_postid( $pc_img );
	$pc_img_s  = ( $pc_img_id ) ? wp_get_attachment_image_url( $pc_img_id, 'medium' ) : \SWELL_Theme::$placeholder;

	$picture_img = '<img src="' . esc_url( $pc_img_s ) . '" data-src="' . esc_attr( $pc_img ) . '" alt="' . esc_attr( $img_alt ) . '" class="p-mainVisual__img lazyload">';
} else {
	$picture_img = '<img src="' . esc_attr( $pc_img ) . '" alt="' . esc_attr( $img_alt ) . '" class="p-mainVisual__img" loading="lazy">';
}


// SP画像
$source = '';
$sp_img = $SETTING['slider1_img_sp'] ?: '';
if ( $sp_img ) {
	if ( $use_lazysizes ) {
		$sp_img_id = attachment_url_to_postid( $sp_img );
		$sp_img_s  = ( $sp_img_id ) ? wp_get_attachment_image_url( $sp_img_id, 'medium' ) : \SWELL_Theme::$placeholder;
		$source    = '<source media="(max-width: 959px)" srcset="' . esc_attr( $sp_img_s ) . '" data-srcset="' . esc_attr( $sp_img ) . '">';
	} else {
		$source = '<source media="(max-width: 959px)" srcset="' . esc_attr( $sp_img ) . '">';
	}
}


// テキストやボタン
$slide_title = $SETTING['slider1_title'];
$slide_text  = $SETTING['slider1_text'];
$btn_text    = $SETTING['slider1_btn_text'];
$slide_url   = $SETTING['slider1_url'];
$txtpos      = $SETTING['slider1_txtpos'];
$text_style  = SWELL_Theme::get_mv_text_style( $SETTING['slider1_txtcol'], $SETTING['slider1_shadowcol'] );

// パーツID
$parts_id = (int) $SETTING['slider1_parts_id'];

?>
<div class="p-mainVisual__inner">

	<div class="p-mainVisual__slide c-filterLayer -<?=esc_attr( $SETTING['mv_img_filter'] )?>">
		<picture class="p-mainVisual__imgLayer c-filterLayer__img">
		<?php echo $source . $picture_img; //phpcs:ignore ?>
		</picture>
		<div class="p-mainVisual__textLayer l-container u-ta-<?=esc_attr( $txtpos )?>" style="<?=esc_attr( $text_style )?>">
		<?php
			$slide_ttl = '';

			// キャッチコピー
			if ( '' !== $slide_title )
				$slide_ttl .= '<div class="p-mainVisual__slideTitle">' . $slide_title . '</div>';

			// サブコピー
			if ( '' !== $slide_text )
				$slide_ttl .= '<div class="p-mainVisual__slideText">' . nl2br( $slide_text ) . '</div>';

			echo wp_kses_post( $slide_ttl );

			// ブログパーツ
			if ( $parts_id ) echo do_shortcode( '[blog_parts id="' . $parts_id . '"]' );

			// ボタン or スライド全体をリンク
			if ( '' !== $slide_url && '' !== $btn_text ) :
			$btn_args = [
				'href'     => $slide_url,
				'text'     => $btn_text,
				'btn_type' => $SETTING['slider1_btntype'],
				'btn_col'  => $SETTING['slider1_btncol'],
			];
			\SWELL_Theme::pluggable_parts( 'mv_btn', $btn_args );

			elseif ( $slide_url ) :
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<a href="' . esc_url( $slide_url ) . '" class="p-mainVisual__slideLink"' . SWELL_Theme::get_link_target( $slide_url ) . '></a>';
			endif;
		?>
		</div>
	</div>
	<?php if ( $SETTING['mv_on_scroll'] ) \SWELL_Theme::pluggable_parts( 'scroll_arrow', ['color' => $SETTING['slider1_txtcol'] ] ); ?>
</div>
