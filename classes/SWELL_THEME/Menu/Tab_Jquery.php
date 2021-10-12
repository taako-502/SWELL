<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;
class Tab_Jquery {

	/**
	 * jQuery
	 */
	public static function jquery_settings( $page_name, $cb ) {

		$section_name  = 'swell_section_jquery';
		$load_settings = [
			'jquery_to_foot'   => ['jQueryをwp_footerで登録する', '' ],
			'remove_jqmigrate' => ['jquery-migrateを読み込まない', '' ],
			'load_jquery'      => ['jQueryを強制的に読み込む', '※ jQueryに依存したスクリプトを読み込んでいなくても、jQueryを読み込むことができます。' ],
		];

		add_settings_section(
			$section_name,
			'jQueryの読み込み',
			'',
			$page_name
		);

		foreach ( $load_settings as $key => $data ) {
			add_settings_field(
				$key,
				'', // $data[0],
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'checkbox',
					'label' => $data[0],
					'desc'  => $data[1],
				]
			);
		}
	}
}
