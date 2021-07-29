<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Custom {

	/**
	 * カスタム書式
	 */
	public static function custom_format_settings( $page_name ) {

		$section_name = 'swell_section_custom_format';

		// セクションの追加
		add_settings_section(
			$section_name,
			__( 'カスタム書式', 'swell' ),
			'',
			$page_name
		);

		// 設定項目の追加
		for ( $i = 1; $i < 3; $i++ ) {
			$label = __( 'カスタム書式', 'swell' ) . ' - ' . $i;
			add_settings_field(
				'custom_format_' . $i, // フィールドID。何にも使わない
				$label,
				['\SWELL_THEME\Menu\Tab_Custom', 'cb_format' ],
				$page_name,
				$section_name,
				[
					'class' => 'tr-custom-format',
					'i'     => $i,
				]
			);
		}
	}

	public static function cb_format( $args ) {
		$i    = $args['i'];
		$db   = \SWELL_Theme::DB_NAME_EDITORS;
		$key  = 'format_title_' . $i;
		$name = $db . '[' . $key . ']';
		$val  = \SWELL_Theme::get_editor( $key );
		?>
			<div class="__settings">
				<div class="__tr">
					<span>クラス名 : </span><code>swl-format-<?=esc_html( $i )?></code>
				</div>
				<div class="__tr">
					<span>表示名 : </span><input type="text" name="<?=esc_attr( $name )?>" value="<?=esc_attr( $val )?>">
				</div>
			</div>
		<?php

		if ( 2 === $i ) {
			echo '<br><small>※ 表示名が空の場合はエディター上で表示されません。</small>';
		}
	}


	/**
	 * カスタム書式用CSSエディター
	 */
	public static function custom_format_css_editor( $page_name ) {

		$section_name = 'swell_section_custom_format_css';

		// セクションの追加
		add_settings_section(
			$section_name,
			'カスタム書式用CSS',
			'',
			$page_name
		);

		// 設定項目の追加
		add_settings_field(
			'custom_format_css', // フィールドID。何にも使わない
			'',
			['\SWELL_THEME\Menu\Tab_Custom', 'cb_format_css' ],
			$page_name,
			$section_name,
			[
				'class' => 'tr-custom-format-css',
			]
		);
	}

	public static function cb_format_css( $args ) {
		$key  = 'custom_format_css';
		$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';
		$val  = \SWELL_Theme::get_editor( $key );
		?>
			<p class="description u-mb-10">ここに書いたCSSは、フロント側とエディター側の両方で読み込まれます。</p>
			<div class="__settings -codemirror">
				<textarea id="<?=esc_attr( $key )?>" cols="60" rows="30" name="<?=esc_attr( $name )?>" id="<?=esc_attr( $name )?>" class="swell-css-editor" ><?php echo esc_textarea( $val ); ?></textarea>
			</div>
		<?php
	}
}
