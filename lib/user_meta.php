<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * ユーザープロフィールに項目追加
 */
if ( ! function_exists( 'LOOS_add_user_meta' ) ) :
function LOOS_add_user_meta( $prof_items ) {
	// 項目の追加
	$prof_items['site2']         = 'サイト2';
	$prof_items['position']      = '役職・肩書き';
	$prof_items['facebook_url']  = 'Facebook URL';
	$prof_items['twitter_url']   = 'Twitter URL';
	$prof_items['instagram_url'] = 'Instagram URL';
	$prof_items['room_url']      = '楽天ROOM URL';
	$prof_items['pinterest_url'] = 'Pinterest URL';
	$prof_items['github_url']    = 'Github URL';
	$prof_items['youtube_url']   = 'Youtube URL';
	$prof_items['amazon_url']    = 'Amazon欲しいものリストURL';

	return $prof_items;
}
endif;
add_filter( 'user_contactmethods', 'LOOS_add_user_meta' );
