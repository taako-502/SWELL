<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Remove {

	/**
	 * 機能停止
	 */
	public static function remove_settings( $page_name, $cb ) {

		$section_name = 'swell_section_remove_swl';
		add_settings_section(
			$section_name,
			'SWELLの機能',
			function() {},
			$page_name
		);

		$remove_settings = [
			'remove_page_fade'   => '「ページ表示時のアニメーション」を停止',
			'remove_url2card'    => '「URLの自動カード化」を停止',
			'remove_delete_empp' => '「空のpタグを自動削除する機能」を停止',
			'remove_luminous'    => '「投稿画像をクリックで拡大表示する機能」を停止',
			'remove_patterns'    => '「SWELLが用意しているブロックパターン」を非表示にする',
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

		add_settings_field(
			'label_swell_custom_post_type',
			'カスタム投稿タイプ<br><small>※ すでに設定済みのデータはデータベースに残り、ブロックからも引き続き呼び出すことが可能です。</small>',
			'__return_false',
			$page_name,
			$section_name,
			[]
		);
		$remove_settings = [
			'remove_lp'         => '「LP」を停止',
			'remove_blog_parts' => '「ブログパーツ」を停止',
			'remove_ad_tag'     => '「広告タグ管理」を停止',
			'remove_balloon'    => '「ふきだし管理」を停止',
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

		$section_name = 'swell_section_remove_core';
		add_settings_section(
			$section_name,
			'WordPressの機能',
			'',
			$page_name
		);

		$remove_settings = [
			'remove_wpver'           => 'WordPressのバージョン情報を出力しない',
			'remove_rel_link'        => '<code>rel="prev/next"</code>を出力しない',
			'remove_wlwmanifest'     => 'Windows Live Writeの連携停止',
			'remove_rsd_link'        => 'EditURI(RSD Link)を停止する',
			'remove_emoji'           => '絵文字用のスクリプトの読み込みをしない',
			'remove_self_pingbacks'  => 'セルフピンバックを停止する',
			'remove_sitemap'         => 'コアのサイトマップ機能を停止する ',
			'remove_media_inf_scrll' => 'メディアライブラリの無限スクロールを停止する ',
			'remove_robots_image'    => '<code>meta="robots" content="max-image-preview:large"</code>を出力しない',
			'remove_rest_link'       => 'REST API用のlinkタグを出力しない',
			'remove_img_srcset'      => '画像のsrcsetを出力しない',
			'remove_wptexturize'     => '記号の自動変換を停止する(wptexturize無効化)',
			'remove_feed_link'       => 'RSSフィードを停止する',

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
