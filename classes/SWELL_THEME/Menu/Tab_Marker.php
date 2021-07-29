<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Marker {

	/**
	 * マーカー
	 */
	public static function marker_settings( $page_name ) {
		$section_name = 'swell_section_marker_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'マーカー設定',
			'',
			$page_name
		);

		add_settings_field(
			'marker_type',
			'スタイル',
			[__CLASS__, 'callback_for_marker_type' ],
			$page_name,
			$section_name,
			[
				'class' => 'marker_type',
			]
		);
		add_settings_field(
			'marker_color',
			'カラー',
			[__CLASS__, 'callback_for_marker_color' ],
			$page_name,
			$section_name,
			[
				'class'  => 'marker_color',
				'colors' => [
					'orange',
					'yellow',
					'green',
					'blue',
				],
			]
		);
	}


	/**
	 * マーカー設定用の専用コールバック
	 */
	public static function callback_for_marker_type( $args ) {

		$type_key  = 'marker_type';
		$type_val  = \SWELL_Theme::get_editor( $type_key );
		$type_name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $type_key . ']';

		$marker_types = [
			'thin'        => '細線',
			'bold'        => '太線',
			'stripe'      => 'ストライプ',
			'thin-stripe' => '細ストライプ',
		];

		?>
			<div class="swell-menu-marker">
				<div class="__settings">
					<select name="<?=$type_name?>" class="__type">
						<?php
						foreach ( $marker_types as $key => $text ) :
							$slected                       = selected( $key, $type_val, false );
							if ( $slected ) $isCustomColor = false;
							echo '<option value="' . $key . '"' . $slected . '>' . $text . '</option>';
						endforeach;
						?>
					</select>
				</div>
			</div>
		<?php
	}
	public static function callback_for_marker_color( $args ) {
		$colors = $args['colors'];
		?>
			<div class="swell-menu-marker">
				<div class="__settings">
					<?php
					foreach ( $colors as $color ) :
						$key  = 'color_mark_' . $color;
						$val  = \SWELL_Theme::get_editor( $key );
						$dflt = \SWELL_Theme::get_default_editor( $key );
						$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';
					?>
						<input type="text" class="colorpicker __<?=$color?>"
							id="<?=$key?>"
							name="<?=$name?>"
							value="<?=$val?>"
							data-default-color="<?=$dflt?>"
						/>
					<?php endforeach; ?>
				</div>
				<div class="__preview">
					<div class="">
						<span class="swl-marker mark_orange">橙色マーカー</span>
						&nbsp;
						<span class="swl-marker mark_yellow">黄色マーカー</span>
						&nbsp;
						<span class="swl-marker mark_green">緑色マーカー</span>
						&nbsp;
						<span class="swl-marker mark_blue">青色マーカー</span>
					</div>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}

}
