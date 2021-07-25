<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;
class Tab_FontAwesome {

	/**
	 * Font Awesome
	 */
	public static function fa_settings( $page_name, $cb ) {

		$section_name = 'swell_section_fa';

		add_settings_section(
			$section_name,
			'Font Awesomeの読み込み',
			'',
			$page_name
		);

		add_settings_field(
			'load_font_awesome',
			'読み込み方',
			$cb,
			$page_name,
			$section_name,
			[
				'id'      => 'load_font_awesome',
				'type'    => 'radio',
				// 'label' => $data[0],
				// 'desc' => 'この機能についてはいくつか注意点がございます。<br><a href="https://swell-theme.com/function/5978/" target="_blank">こちらのページ</a>をご一読ください。',
				'choices' => [
					''    => '読み込まない',
					'css' => 'CSSで読み込む',
					'js'  => 'JSで読み込む',
				],
			]
		);
	}
}
