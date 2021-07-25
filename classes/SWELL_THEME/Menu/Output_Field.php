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
			'input_type' => '',
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
			echo 'Not found : ' . $field_id;
			return;
		}

		// フォーム要素のname属性に渡す値。（name が直接指定されていなければ配列で保存。）
		$name = $args['name'] ?: $db . '[' . $field_id . ']';

		// 現在の値
		$val = $data[ $field_id ];

		// typeに合わせて処理を分岐
		if ( $args['type'] === 'input' ) {

			self::input( $field_id, $args['input_type'], $args['before'], $args['after'] );

		} elseif ( $args['type'] === 'radio' ) {

			self::radio( $field_id, $args['choices'] );

		} elseif ( $args['type'] === 'checkbox' ) {

			self::checkbox( $field_id, $args['label'] );

		} elseif ( $args['type'] === 'textarea' ) {

			self::textarea( $field_id, $args['rows'], $args['after'] );

		} elseif ( $args['type'] === 'color' ) {

			self::color( $field_id, $name, $val, $args['rows'], $args['after'] );

		}

		// description （共通）
		if ( $args['desc'] ) {
			echo '<p class="description">', $args['desc'], '</p>';
		}

	}



	/**
	 * input : "text"|"number"|"email"|...]
	 */
	public static function input( $field_id, $type, $before_text, $after_text ) {

		echo $before_text, '<input id="',$field_id,'" name="', \SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]" ,'" type="', $type, '" value="', \SWELL_Theme::$options[ $field_id ] ,'" />', $after_text;

	}


	/**
	 * checkbox
	 */
	public static function checkbox( $field_id, $label ) {

		$checked = checked( (string) \SWELL_Theme::$options[ $field_id ], '1', false );
		echo '<input type="hidden" name="', \SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]", '" value="">';
		echo '<input type="checkbox" id="', $field_id, '" name="', \SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]", '" value="1" ', $checked, ' />';
		echo '<label for="', $field_id, '">', $label, '</label>';
	}


	/**
	 * radio
	 */
	public static function radio( $field_id, $choices ) {

		echo '<fieldset>';
		foreach ( $choices as $key => $label ) {
			$radio_id = $field_id . '_' . $key;
			$checked  = checked( \SWELL_Theme::$options[ $field_id ], $key, false );
			$attr     = 'id="' . $radio_id . '" name="' . \SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]" . '" value="' . $key . '" ' . $checked . '"';

			echo '<div class="swell_radio_wrapper">' .
					'<label for="', $radio_id, '">' .
						'<input type="radio" ' . $attr . ' >' .
						'<span>' . $label . '</span>' .
					'</label>' .
				'</div>';
		}
		echo '</fieldset>';

	}


	/**
	 * select
	 */
	public static function select( $field_id, $options ) {

	}

	/**
	 * color
	 */
	public static function color( $field_id, $name, $val, $label = '' ) {
		echo '<div>';
		if ( $label ) {
			echo '<label for="' . $field_id . '">' . $label . '</label>';
		}
		echo '<input type="text" id="' . $field_id . '" name="' . $name . '" class="colorpicker" value="' . $val . '">';
		echo '</div>';
	}


	/**
	 * textarea
	 */
	public static function textarea( $field_id, $rows, $after = '' ) {

		if ( $after ) {

			echo '<div class="hcb_col2_wrap">',
				'<div class="hcb_col_item"><textarea id="' . $field_id . '" name="' . \SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]" . '" type="text" class="regular-text" rows="' . $rows . '" >' . \SWELL_Theme::$options[ $field_id ] . '</textarea></div>',
				'<div class="hcb_col_item">' . $after . '</div>',
				'</div>';
		} else {
			echo '<textarea id="',$field_id,'" name="',\SWELL_Theme::DB_NAME_OPTIONS . "[$field_id]" ,'" type="text" class="regular-text" rows="', $rows, '" >', \SWELL_Theme::$options[ $field_id ] ,'</textarea>';
		}
	}
}
