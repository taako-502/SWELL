<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING  = SWELL_Theme::get_setting();
$mv_type  = $SETTING['main_visual_type'];
$mv_class = 'p-mainVisual';

// スライド / １枚画像 / 動画 で処理を分ける
if ( 'slider' === $mv_type ) {
	$slider_images = SWELL_Theme::get_mv_slide_imgs();
	if ( count( $slider_images ) > 1 ) {

		$mv_class .= ' -type-slider -motion-' . $SETTING['mv_slide_animation'];

		$parts_name = 'parts/top/main_visual-slider';
		$variable   = $slider_images;

	} else {

		$mv_class  .= ' -type-single';
		$parts_name = 'parts/top/main_visual-single';
		$variable   = null;

	}
} elseif ( 'movie' === $mv_type ) {

	$mv_class  .= ' -type-movie';
	$parts_name = 'parts/top/main_visual-movie';
	$variable   = null;

}

// スライダーの高さ
$slide_size = $SETTING['mv_slide_size'];
$mv_class  .= ' -height-' . $slide_size;


// 余白ありかどうか
if ( $SETTING['mv_on_margin'] ) {
	$mv_class .= ' -margin-on';
}

?>
<div id="main_visual" class="<?=esc_attr( $mv_class )?>">
<?php
	SWELL_Theme::get_parts( $parts_name, $variable );
	do_action( 'swell_inner_main_visual' );
?>
</div>
