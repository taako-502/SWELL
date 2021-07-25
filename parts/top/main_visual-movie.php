<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING = SWELL_FUNC::get_setting();

// 動画
$pc_poster    = $SETTING['mv_video_poster'];
$pc_video_id  = $SETTING['mv_video'];
$pc_video_url = wp_get_attachment_url( $pc_video_id );

$sp_poster    = $SETTING['mv_video_poster_sp'] ?: $pc_poster;
$sp_video_id  = $SETTING['mv_video_sp'] ?: $SETTING['mv_video'];
$sp_video_url = wp_get_attachment_url( $sp_video_id );

// テキストやボタン
$slide_title = $SETTING['movie_title'];
$slide_text  = $SETTING['movie_text'];
$slide_url   = $SETTING['movie_url'];
$btn_text    = $SETTING['movie_btn_text'];
$txtpos      = $SETTING['movie_txtpos'];
$txtcol      = $SETTING['movie_txtcol'];
$text_style  = SWELL_FUNC::get_mv_text_style( $txtcol, $SETTING['movie_shadowcol'] );

// パーツID
$parts_id = (int) $SETTING['movie_parts_id'];

?>
<div class="p-mainVisual__inner c-filterLayer -<?=esc_attr( $SETTING['mv_img_filter'] )?>">
	<div class="p-mainVisual__imgLayer c-filterLayer__img">
		<video
			class="p-mainVisual__video"
			data-poster-pc="<?=esc_attr( $pc_poster )?>"
			data-poster-sp="<?=esc_attr( $sp_poster )?>"
			playsinline autoplay loop muted
		>
			<source data-src-sp="<?=esc_attr( $sp_video_url )?>" data-src-pc="<?=esc_attr( $pc_video_url )?>">
		</video>
	</div>
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

		// ボタン
		if ( '' !== $slide_url && '' !== $btn_text ) :
			$btn_args = [
				'href'     => $slide_url,
				'text'     => $btn_text,
				'btn_type' => $SETTING['movie_btntype'],
				'btn_col'  => $SETTING['movie_btncol'],
			];
			\SWELL_Theme::pluggable_parts( 'mv_btn', $btn_args );
		endif;
	?>
	</div>
	<?php if ( $SETTING['mv_on_scroll'] ) \SWELL_Theme::pluggable_parts( 'scroll_arrow', ['color' => $txtcol ] ); ?>
</div>
