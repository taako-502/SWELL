<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Iconbox {

	/**
	 * アイコンボックス設定
	 */
	public static function small_settings( $page_name ) {
		$section_name = 'swell_section_iconbox_small';

		// セクションの追加
		add_settings_section(
			$section_name,
			'アイコンボックス（小）',
			'',
			$page_name
		);

		add_settings_field(
			'iconbox_small_style',
			'スタイル',
			['\SWELL_THEME\Menu\Tab_Iconbox', 'callback_for_iconbox_type' ],
			$page_name,
			$section_name,
			[
				'key'     => 'iconbox_s_type',
				'type'    => 'small',
				'class'   => 'tr-iconbox',
				'choices' => [
					'fill-flat'   => '塗り（フラット）',
					'fill-solid'  => '塗り（浮き出し）',
					'border-flat' => 'ボーダー',
				],
			]
		);

		$icons = [
			'good'     => 'グッド',
			'bad'      => 'バッド',
			'info'     => 'インフォ',
			'announce' => 'アナウンス',
			'pen'      => 'ペン',
			'book'     => '本',
		];
		foreach ( $icons as $key => $label ) {
			add_settings_field(
				'color_iconbox_small_' . $key,
				$label,
				['\SWELL_THEME\Menu\Tab_Iconbox', 'callback_for_iconbox_small_color' ],
				$page_name,
				$section_name,
				[
					'class'     => 'tr-iconbox',
					'icon_name' => $key,
					'type'      => 'small',
				]
			);
		}
	}


	/**
	 * アイコンボックスの設定
	 */
	public static function big_settings( $page_name ) {
		$section_name = 'swell_section_iconbox_big';

		// セクションの追加
		add_settings_section(
			$section_name,
			'アイコンボックス（大）',
			'',
			$page_name
		);

		add_settings_field(
			'iconbox_big_style',
			'スタイル',
			['\SWELL_THEME\Menu\Tab_Iconbox', 'callback_for_iconbox_type' ],
			$page_name,
			$section_name,
			[
				'key'     => 'iconbox_type',
				'type'    => 'big',
				'class'   => 'tr-iconbox',
				'choices' => [
					'flat'   => 'フラット',
					'solid'  => '立体',
				],
			]
		);

		$icons = [
			'point'   => 'ポイント',
			'check'   => 'チェック',
			'batsu'   => 'バツ',
			'hatena'  => 'はてな',
			'caution' => 'アラート',
			'memo'    => 'メモ',
		];
		foreach ( $icons as $key => $label ) {
			add_settings_field(
				'color_iconbox_big_' . $key,
				$label,
				['\SWELL_THEME\Menu\Tab_Iconbox', 'callback_for_iconbox_big_color' ],
				$page_name,
				$section_name,
				[
					'class'     => 'tr-iconbox',
					'icon_name' => $key,
					'type'      => 'big',
				]
			);
		}
	}


	/**
	 * アイコンボックス設定用のコールバック
	 */
	public static function callback_for_iconbox_type( $args ) {

		$key = $args['key'];

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		$val  = \SWELL_FUNC::get_editor( $key );
		$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';

		$options = $args['choices'];

		$type = $args['type'];
		?>
			<div class="swell-menu-iconbox">
				<div class="__settings">
					<select name="<?=$name?>" class="__icon_<?=$type?>_type">
						<?php
						foreach ( $options as $key => $text ) :
							$slected                       = selected( $key, $val, false );
							if ( $slected ) $isCustomColor = false;
							echo '<option value="' . $key . '"' . $slected . '>' . $text . '</option>';
						endforeach;
						?>
					</select>
				</div>
			</div>
		<?php
	}


	/**
	 * アイコンボックス（小）設定用の専用コールバック
	 */
	public static function callback_for_iconbox_small_color( $args ) {

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		// key
		$icon_name      = $args['icon_name'];
		$color_key_icon = 'color_icon_' . $icon_name;
		$color_key_bg   = 'color_icon_' . $icon_name . '_bg';

		// 現在の値
		$color_val_icon = \SWELL_FUNC::get_editor( $color_key_icon );
		$color_val_bg   = \SWELL_FUNC::get_editor( $color_key_bg );

		// デフォルト値
		$dflt_col_icon = \SWELL_Theme::get_default_editor( $color_key_icon );
		$dflt_col_bg   = \SWELL_Theme::get_default_editor( $color_key_bg );

		// フォーム要素のname属性に渡す値。
		$name_icon = \SWELL_Theme::DB_NAME_EDITORS . '[' . $color_key_icon . ']';
		$name_bg   = \SWELL_Theme::DB_NAME_EDITORS . '[' . $color_key_bg . ']';

		$iconbox_class = ( $args['type'] === 'small' ) ? 'is-style-icon_' . $icon_name : 'is-style-big_icon_' . $icon_name;
		?>
			<div class="swell-menu-iconbox">
				<div class="__settings">
					<input type="text" class="colorpicker __icon_color"
						id="<?=$color_key_icon?>"
						name="<?=$name_icon?>"
						value="<?=$color_val_icon?>"
						data-default-color="<?=$dflt_col_icon?>"
						data-key="<?=$color_key_icon?>"
					/>
					<input type="text" class="colorpicker __icon_color"
						id="<?=$color_key_bg?>"
						name="<?=$name_bg?>"
						value="<?=$color_val_bg?>"
						data-default-color="<?=$dflt_col_bg?>"
						data-key="<?=$color_key_bg?>"
					/>
				</div>
				<div class="__preview">
					<div class="<?=$iconbox_class?> __iconbox-small">
						<p>ここにコンテンツが入ります</p>
					</div>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}


	/**
	 * アイコンボックス（大）設定用の専用コールバック
	 */
	public static function callback_for_iconbox_big_color( $args ) {

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		// key
		$icon_name      = $args['icon_name'];
		$color_key_icon = 'color_icon_' . $icon_name;

		// 現在の値
		$color_val_icon = \SWELL_FUNC::get_editor( $color_key_icon );
		// デフォルト値
		$dflt_col_icon = \SWELL_Theme::get_default_editor( $color_key_icon );

		// フォーム要素のname属性に渡す値。
		$name_dark = \SWELL_Theme::DB_NAME_EDITORS . '[' . $color_key_icon . ']';

		$iconbox_class = ( $args['type'] === 'small' ) ? 'is-style-icon_' . $icon_name : 'is-style-big_icon_' . $icon_name;
		?>
			<div class="swell-menu-iconbox">
				<div class="__settings">
					<input type="text" class="colorpicker __icon_color"
						id="<?=$color_key_icon?>"
						name="<?=$name_dark?>"
						value="<?=$color_val_icon?>"
						data-default-color="<?=$dflt_col_icon?>"
						data-key="<?=$color_key_icon?>"
					/>
				</div>
				<div class="__preview">
					<div class="<?=$iconbox_class?> __iconbox-big">
						<p>ここにコンテンツが入ります</p>
					</div>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}
}
