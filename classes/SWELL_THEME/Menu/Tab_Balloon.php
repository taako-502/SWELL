<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Balloon {

	/**
	 * ふきだしカラー
	 */
	public static function balloon_settings( $page_name ) {

		$section_name = 'swell_section_balloon_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'ふきだしカラー',
			'',
			$page_name
		);

		$colors = [
			'gray'   => 'グレー',
			'green'  => 'グリーン',
			'blue'   => 'ブルー',
			'red'    => 'レッド',
			'yellow' => 'イエロー',
		];

		// 設定項目の追加
		foreach ( $colors as $col => $label ) {
			add_settings_field(
				'color_bln_' . $col, // フィールドID。何にも使わない
				'カラーセット【' . $label . '】',
				['\SWELL_THEME\Menu\Tab_Balloon', 'callback' ],
				$page_name,
				$section_name,
				[
					'color' => $col,
					'class' => 'tr-balloon -' . $col,
				]
			);
		}
	}


	/**
	 * ふきだしカラー設定用の専用コールバック
	 */
	public static function callback( $args ) {

		$color     = $args['color'];
		$bg_id     = 'color_bln_' . $color . '_bg';
		$border_id = 'color_bln_' . $color . '_border';

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		// 現在の値
		$val_bg     = \SWELL_FUNC::get_editor( $bg_id );
		$val_border = \SWELL_FUNC::get_editor( $border_id );

		$dflt_bg     = \SWELL_Theme::get_default_editor( $bg_id );
		$dflt_border = \SWELL_Theme::get_default_editor( $border_id );

		// フォーム要素のname属性に渡す値。
		$name_bg     = \SWELL_Theme::DB_NAME_EDITORS . '[' . $bg_id . ']';
		$name_border = \SWELL_Theme::DB_NAME_EDITORS . '[' . $border_id . ']';

		?>
		<div class="swell-menu-balloon">

			<!-- 設定フィールド -->
			<div class="__settings">
				<div class="swell-menu-balloon__item">
					<span class="__label">背景</span>
					<input type="text" class="colorpicker -bg"
						id="<?=$bg_id?>"
						name="<?=$name_bg?>"
						value="<?=$val_bg?>"
						data-default-color="<?=$dflt_bg?>"
					/>
				</div>
				<div class="swell-menu-balloon__item">
					<span class="__label">ボーダー</span>
					<input type="text" class="colorpicker -border"
						id="<?=$border_id?>"
						name="<?=$name_border?>"
						value="<?=$val_border?>"
						data-default-color="<?=$dflt_border?>"
					/>
				</div>
			</div>

			<!-- プレビュー -->
			<div class="__preview">
				<?php echo do_shortcode( '[ふきだし col="' . $color . '" border="on"]ふきだしです。[/ふきだし]' ); ?>
				<div class="__previewLabel">プレビュー</div>
			</div>
		</div>
	<?php

	}
}
