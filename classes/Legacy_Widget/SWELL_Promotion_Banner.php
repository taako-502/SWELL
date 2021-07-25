<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme\Legacy_Widget as Widget;

/**
 * プロモーションバナー
 */
class SWELL_Promotion_Banner extends WP_Widget {
	public function __construct() {
		parent::__construct(
			false,
			$name = '[SWELL] プロモーションバナー',
			['description' => 'SWELLプロモーションバナーを表示' ]
		);
	}


	// 設定
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, [
			'title' => '',
		] );
		Widget::text_field([
			'label'    => __( 'タイトル', 'swell' ),
			'id'       => $this->get_field_id( 'title' ),
			'name'     => $this->get_field_name( 'title' ),
			'value'    => $instance['title'],
			'help'     => '※空の場合は何も出力されません。',
		]);
	}


	// 保存
	public function update( $new_instance, $old_instance ) {
		$new_instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		return $new_instance;
	}


	// 出力
	public function widget( $args, $instance ) {
		$instance     = wp_parse_args( (array) $instance, [
			'title' => '',
		] );
		$widget_title = $instance['title'] ?: '';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];
		if ( $widget_title ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $widget_title ) . $args['after_title'];
		}
		\SWELL_Theme::pluggable_parts( 'pr_banner' );
		echo $args['after_widget'];
	}
}
