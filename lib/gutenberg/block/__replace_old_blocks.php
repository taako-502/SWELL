<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 過去ブロックの出力を置換する
 */
add_filter(
	'the_content',
	function( $content ) {

	/**
	 * スタイルブロックに swell-block-style を追加
	 */
	$content = preg_replace_callback(
		'/(loos\/style-block[^<]*<div)([^>]*)>/',
		function( $m ) {

		// divの中身
		$div_src = $m[2];
		if ( $div_src === '' ) {
				return $m[1] . ' class="swell-block-style">';
		} elseif ( strpos( $div_src, 'swell-block-style' ) === false ) {
				// var_dump($m[2]);
				$div_src = str_replace( 'class="', 'class="swell-block-style ', $div_src );
				return $m[1] . $div_src . '>';
		}

		return $m[0];
		},
		$content
	);

	/**
	 * ボタンブロックに swell-block-button を追加
	 */
	$content = preg_replace_callback(
		'/(loos\/button[^<]*<div)([^>]*)>/',
		function( $m ) {

		// divの中身
		$div_src = $m[2];
		if ( $div_src === '' ) {
				return $m[1] . ' class="swell-block-button">';
		} elseif ( strpos( $div_src, 'swell-block-button' ) === false ) {
				// var_dump($m[2]);
				$div_src = str_replace( 'class="', 'class="swell-block-button ', $div_src );
				return $m[1] . $div_src . '>';
		}

		return $m[0];
		},
		$content
	);

	return $content;

	},
	1
);
