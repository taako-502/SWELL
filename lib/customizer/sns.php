<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セクション追加
 */
$section = 'swell_section_sns';

$wp_customize->add_section( $section, [
	'title'    => 'SNS情報',
	'priority' => 9,
] );

// SNSリンク設定
Customizer::sub_title( $section, 'sns_link', [
	'label'     => 'SNSリンク設定',
] );

$sns_array = [
	'facebook'  => 'Facebook',
	'twitter'   => 'Twitter',
	'instagram' => 'Instagram',
	'room'      => '楽天ROOM',
	'line'      => 'LINE',
	'pinterest' => 'Pinterest',
	'github'    => 'Github',
	'youtube'   => 'YouTube',
	'amazon'    => 'Amazon欲しいものリスト',
	'feedly'    => 'Feedly',
	'rss'       => 'RSS',
	'contact'   => 'お問い合わせページ',
];
foreach ( $sns_array as $sns_key => $sns_name ) {
	Customizer::add( $section, $sns_key . '_url', [
		// 'label'       => '',
		'description' => $sns_name . 'ページURL',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	] );
}
