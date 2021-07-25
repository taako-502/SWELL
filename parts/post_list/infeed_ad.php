<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING = SWELL_FUNC::get_setting();

$infeed = ( IS_MOBILE ) ? $SETTING['infeed_code_sp'] : $SETTING['infeed_code_pc'];
if ( ! empty( $infeed ) ) {
	echo '<li class="p-postList__item c-infeedAd">' . do_shortcode( $infeed ) . '</li>';
}
