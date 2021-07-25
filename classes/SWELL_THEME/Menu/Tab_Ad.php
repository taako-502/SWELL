<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Ad {

	/**
	 * 広告
	 */
	public static function ad_settings( $page_name, $cb ) {

		$section_name = 'swell_section_ad';
		add_settings_section(
			$section_name,
			'広告コードの設定',
			'',
			$page_name
		);

		$ad_settings = [
			'sc_ad_code' => [
				'記事内広告 [ad]',
				'ここで入力したコードは、ショートコード<code>[ad]</code>で簡単に呼び出せるようになります。',
			],
			'before_h2_addcode' => [
				'目次広告',
				'目次の直前または直後に挿入する広告コード。（目次が非表示の場合は最初のH2タグの直前に表示されます。)<br>' .
				'目次の前後どちらに設置するかは、<a href="' . admin_url( 'customize.php' ) . '" target="_blank">カスタマイザー</a>の「投稿・固定ページ」>「目次」から設定できます。',
			],
			'auto_ad_code' => [
				'自動広告',
				'Google AdSenseの自動広告コード。ここで設定した自動広告は、ページごとに非表示にすることが可能です。',
			],
		];

		foreach ( $ad_settings as $key => $field ) {
			add_settings_field(
				$key,
				$field[0],
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'textarea',
					// 'label' => $field[0],
					'desc'  => $field[1],
					'class' => 'ad_code',
				]
			);
		}

		$section_name = 'swell_section_infeed';
		add_settings_section(
			$section_name,
			'インフィード広告の設定',
			function() {
				return '<p class="description">トップページの投稿リスト・カテゴリーページの投稿リストに表示するインフィード広告に関する設定</p>';
			},
			$page_name
		);

		$ad_settings = [
			'infeed_code_pc' => [
				'PC・Tabサイズ用',
				'',
			],
			'infeed_code_sp' => [
				'スマホサイズ用',
				'',
			],

		];
		foreach ( $ad_settings as $key => $field ) {
			add_settings_field(
				$key,
				$field[0],
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'textarea',
					// 'label' => $field[0],
					'desc'  => $field[1],
					'class' => 'ad_code',
				]
			);
		}

		add_settings_field(
			'infeed_interval',
			'インフィード広告の間隔',
			$cb,
			$page_name,
			$section_name,
			[
				'id'         => 'infeed_interval',
				'type'       => 'input',
				'input_type' => 'number',
				// 'label' =>'インフィード広告を表示する間隔',
				// 'desc' => '',
				'class'      => 'infeed_ad',
			]
		);

		// 'infeed_interval'    => 4,
	}

}
