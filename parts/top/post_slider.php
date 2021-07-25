<?php
use \SWELL_THEME\Parts\Post_List;
if ( ! defined( 'ABSPATH' ) ) exit;
$SETTING = SWELL_FUNC::get_setting();

$q_args = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'no_found_rows'       => true,
	'posts_per_page'      => 8,
	'ignore_sticky_posts' => true,
];

// 除外タグ
$exc_tag = explode( ',', $SETTING['exc_tag_id'] );
if ( ! empty( $exc_tag ) ) {
	$q_args['tag__not_in'] = $exc_tag;
}

// 並び順
$the_orderby = $SETTING['ps_orderby'];
if ( 'meta_value_num' === $the_orderby ) {
	$q_args['orderby']  = 'meta_value_num';
	$q_args['meta_key'] = SWELL_CT_KEY;
	$q_args['order']    = 'DESC';
} else {
	$q_args['orderby'] = $the_orderby;
}

if ( 'tag' === $SETTING['ps_pickup_type'] ) {
	// タグを指定
	$pickup_tag = $SETTING['pickup_tag'];
	if ( ! empty( $pickup_tag ) ) {
		$q_args['tag'] = $pickup_tag;
	}
} else {
	// カテゴリーを指定
	$pickup_cat = $SETTING['pickup_cat'];
	if ( ! empty( $pickup_cat ) ) {
		$q_args['cat'] = (int) $pickup_cat;
	}
}

// ピックアップタイトル
$pickup_title = $SETTING['pickup_title'];

// スライダークラス
$slider_class = ( 'normal' === $SETTING['ps_style'] ) ? '-ps-style-normal' : '-ps-style-img';

// 表示枚数
$slide_num_pc = $SETTING['ps_num'];
$slide_num_sp = $SETTING['ps_num_sp'];

$slider_class .= ' -num-pc-' . str_replace( '.', '_', $slide_num_pc );
$slider_class .= ' -num-sp-' . str_replace( '.', '_', $slide_num_sp );

// floatにして計算
$slide_num_pc = (float) $slide_num_pc;
$slide_num_sp = (float) $slide_num_sp;

if ( $slide_num_pc <= 2 ) {
	$slider_class .= ' -fz-pc-l';
};
if ( $slide_num_sp >= 2 ) {
	$slider_class .= ' -fz-sp-s';
};

// スタイダーインナークラス
$inner_class = ( 'wide' === $SETTING['pickup_pad_lr'] ) ? ' l-container' : '';

// サムネイルサイズ
$pc_size     = round( 100 / $slide_num_pc, 1 ) . 'vw';
$sp_size     = round( 100 / $slide_num_sp, 1 ) . 'vw';
$thumb_sizes = '(min-width: 960px) ' . $pc_size . ', ' . $sp_size;
?>
<div id="post_slider" class="p-postSlider <?=esc_attr( $slider_class )?>">
	<div class="p-postSlider__inner<?=esc_attr( $inner_class )?>">
		<?php if ( $pickup_title ) : ?>
			<div class="p-postSlider__title">
				<?=wp_kses_post( $SETTING['pickup_title'] )?>
			</div>
		<?php endif; ?>
		<div class="p-postSlider__swiper swiper-container">
			<?php
				SWELL_FUNC::get_parts(
					'parts/post_list/loop_by_slider',
					[
						'query_args'  => $q_args,
						'thumb_sizes' => $thumb_sizes,
					]
				);
			?>
			<?php if ( $SETTING['ps_on_pagination'] ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ( $SETTING['ps_on_nav'] ) : ?>
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
		</div>
	</div>
</div>
