<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Structure {

	/**
	 * 構造化データ
	 */
	public static function jsonld_settings( $page_name, $cb ) {

		$section_name = 'swell_section_json_ld';
		add_settings_section(
			$section_name,
			'JSON-LD',
			'',
			$page_name
		);

		$remove_settings = [
			'use_json_ld' => 'JSON-LDを自動生成する',
		];

		foreach ( $remove_settings as $key => $label ) {
			add_settings_field(
				$key,
				'', // $label,
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'checkbox',
					'label' => $label,
				]
			);
		}
	}
}
