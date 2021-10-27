<?php
namespace SWELL_THEME\Menu;

use \SWELL_THEME\Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Output_Field extends Admin_Menu {

	/**
	 * 設定フィールドの表示
	 */
	public static function settings_field_cb( $args ) {
		$default = [
			'db'         => 'options',
			'name'       => '', // 直接DB名指定する時に使う
			'id'         => '',
			'label'      => '',
			'desc'       => '',
			'type'       => '',
			'input_type' => 'text',
			'choices'    => '',
			'rows'       => 8,
			'before'     => '',
			'after'      => '',
		];
		$args    = array_merge( $default, $args );

		// 使用するデータベース
		if ( $args['db'] === 'editors' ) {
			$db   = \SWELL_Theme::DB_NAME_EDITORS;
			$data = \SWELL_Theme::$editors;
		} else {
			$db   = \SWELL_Theme::DB_NAME_OPTIONS;
			$data = \SWELL_Theme::$options;
		}

		$field_id = $args['id'];
		if ( ! isset( $data[ $field_id ] ) ) {
			echo 'Not found : ' . esc_attr( $field_id );
			return;
		}

		// フォーム要素のname属性に渡す値。（name が直接指定されていなければ配列で保存。）
		$name = $args['name'] ?: $db . '[' . $field_id . ']';

		// 現在の値
		$val = $data[ $field_id ];

		// typeに合わせて処理を分岐
		if ( $args['type'] === 'input' ) {

			self::input( $field_id, $args );

		} elseif ( $args['type'] === 'radio' ) {

			self::radio( $field_id, $args['choices'] );

		} elseif ( $args['type'] === 'checkbox' ) {

			self::checkbox( $field_id, $args['label'] );

		} elseif ( $args['type'] === 'textarea' ) {

			self::textarea( $field_id, $args );

		} elseif ( $args['type'] === 'color' ) {

			self::color( $field_id, $name, $val, $args['rows'], $args['after'] );

		} elseif ( $args['type'] === 'select' ) {

			self::select( $field_id, $args );

		}

		// description （共通）
		if ( $args['desc'] ) {
			echo '<p class="description">' . wp_kses_post( $args['desc'] ) . '</p>';
		}

	}



	/**
	 * input : "text"|"number"|"email"|...]
	 */
	public static function input( $field_id, $args ) {

		$type        = $args['input_type'] ?? 'text';
		$before_text = $args['before'] ?? '';
		$after_text  = $args['after'] ?? '';

		$name  = \SWELL_Theme::DB_NAME_OPTIONS . '[' . $field_id . ']';
		$value = \SWELL_Theme::$options[ $field_id ];

		$props = 'id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"';

		if ( 'number' === $type ) {
			$step   = $args['step'] ?? '1';
			$props .= ' step="' . esc_attr( $step ) . '"';
		}

		echo wp_kses_post( $before_text );
		echo '<input ' . $props . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wp_kses_post( $after_text );

	}


	/**
	 * checkbox
	 */
	public static function checkbox( $field_id, $label ) {

		$name  = \SWELL_Theme::DB_NAME_OPTIONS . '[' . $field_id . ']';
		$value = \SWELL_Theme::$options[ $field_id ];

		echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="">';
		echo '<input type="checkbox" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( (string) $value, '1', false ) . ' />';
		echo '<label for="' . esc_attr( $field_id ) . '">' . wp_kses_post( $label ) . '</label>';
	}


	/**
	 * radio
	 */
	public static function radio( $field_id, $choices ) {

		$name = \SWELL_Theme::DB_NAME_OPTIONS . '[' . $field_id . ']';

		echo '<fieldset>';
		foreach ( $choices as $key => $label ) {
			$radio_id = $field_id . '_' . $key;
			$value    = \SWELL_Theme::$options[ $field_id ];
			$checked  = checked( $value, $key, false );
			$attr     = 'id="' . esc_attr( $radio_id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $key ) . '" ' . $checked . '"';

			echo '<div class="swell_radio_wrapper">' .
					'<label for="' . esc_attr( $radio_id ) . '">' .
						'<input type="radio" ' . $attr . ' >' . // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span>' . wp_kses_post( $label ) . '</span>' .
					'</label>' .
				'</div>';
		}
		echo '</fieldset>';

	}


	/**
	 * select
	 */
	public static function select( $field_id, $args ) {

		$choices = $args['choices'] ?: [];
		$before  = $args['before'] ?? '';
		$after   = $args['after'] ?? '';

		$name  = \SWELL_Theme::DB_NAME_OPTIONS . '[' . $field_id . ']';
		$value = \SWELL_Theme::$options[ $field_id ];

		echo wp_kses_post( $before );
		echo '<select id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '">';
		foreach ( $choices as $val => $label ) {
			echo '<option value="' . esc_attr( $val ) . '"' . selected( $value, $val, false ) . '>' . wp_kses_post( $label ) . '</option>';
		}
		echo '</select>';
		echo wp_kses_post( $after );
	}

	/**
	 * color
	 */
	public static function color( $field_id, $name, $val, $label = '' ) {
		echo '<div>';
		if ( $label ) {
			echo '<label for="' . esc_attr( $field_id ) . '">' . wp_kses_post( $label ) . '</label>';
		}
		echo '<input type="text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" class="colorpicker" value="' . esc_attr( $val ) . '">';
		echo '</div>';
	}


	/**
	 * textarea
	 */
	public static function textarea( $field_id, $args ) {

		$placeholder = $args['placeholder'] ?? '';
		$rows        = $args['rows'] ?? 8;
		$before      = $args['before'] ?? '';
		$after       = $args['after'] ?? '';

		$name  = \SWELL_Theme::DB_NAME_OPTIONS . '[' . $field_id . ']';
		$value = \SWELL_Theme::$options[ $field_id ];

		echo wp_kses_post( $before );
		echo '<textarea id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" class="regular-text" rows="' . esc_attr( $rows ) . '" placeholder="' . esc_attr( $placeholder ) . '">' . esc_textarea( $value ) . '</textarea>';
		echo wp_kses_post( $after );

	}
}
