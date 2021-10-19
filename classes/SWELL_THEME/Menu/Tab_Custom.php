<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Custom {

	/**
	 * カスタム書式セット
	 */
	public static function custom_format_set_settings( $page_name ) {

		$section_name = 'swell_section_custom_format_set';

		// セクションの追加
		add_settings_section(
			$section_name,
			__( 'カスタム書式セット', 'swell' ),
			'',
			$page_name
		);

		// 設定項目の追加
		for ( $i = 1; $i < 3; $i++ ) {
			$label = __( 'カスタム書式セット', 'swell' ) . ' - ' . $i;
			add_settings_field(
				'custom_format_set_' . $i, // フィールドID。何にも使わない
				$label,
				[__CLASS__, 'cb_format_set' ],
				$page_name,
				$section_name,
				[
					'class'              => 'tr-custom-format-set',
					'i'                  => $i,
					'base_colors'   => [
						[
							'name'  => 'メインカラー',
							'slug'  => 'main',
							'color' => 'var( --color_main )',
						],
						[
							'name'  => 'メインカラー(薄)',
							'slug'  => 'main_thin',
							'color' => 'var( --color_main_thin )',
						],
						[
							'name'  => 'グレー',
							'slug'  => 'gray',
							'color' => 'var( --color_gray )',
						],
						[
							'name'  => '白',
							'slug'  => 'white',
							'color' => '#fff',
						],
						[
							'name'  => '黒',
							'slug'  => 'black',
							'color' => '#000',
						],
					],
					'custom_colors' => [
						'deep01' => '濃い色1',
						'deep02' => '濃い色2',
						'deep03' => '濃い色3',
						'deep04' => '濃い色4',
						'pale01' => '淡い色1',
						'pale02' => '淡い色2',
						'pale03' => '淡い色3',
						'pale04' => '淡い色4',
					],
					'marker_colors'      => [
						'orange' => '橙色',
						'yellow' => '黄色',
						'green'  => '緑色',
						'blue'   => '青色',
					],
					'font_sizes'         => [
						'xs' => 'XS',
						's'  => 'S',
						'l'  => 'L',
						'xl' => 'XL',
					],
				]
			);
		}
	}

	public static function cb_format_set( $args ) {
		$i  = $args['i'];
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		$bold_key      = 'format_set_bold_' . $i;
		$italic_key    = 'format_set_italic_' . $i;
		$color_key     = 'format_set_color_' . $i;
		$bg_key        = 'format_set_bg_' . $i;
		$marker_key    = 'format_set_marker_' . $i;
		$font_size_key = 'format_set_font_size_' . $i;

		$bold_name      = $db . '[' . $bold_key . ']';
		$italic_name    = $db . '[' . $italic_key . ']';
		$color_name     = $db . '[' . $color_key . ']';
		$bg_name        = $db . '[' . $bg_key . ']';
		$marker_name    = $db . '[' . $marker_key . ']';
		$font_size_name = $db . '[' . $font_size_key . ']';

		$base_colors   = $args['base_colors'];
		$custom_colors = $args['custom_colors'];
		$marker_colors = $args['marker_colors'];
		$font_sizes    = $args['font_sizes'];

		$editor = \SWELL_Theme::get_editor();

		$enable_color     = $editor[ $color_key ] !== '' ? 1 : 0;
		$enable_bg        = $editor[ $bg_key ] !== '' ? 1 : 0;
		$enable_marker    = $editor[ $marker_key ] !== '' ? 1 : 0;
		$enable_font_size = $editor[ $font_size_key ] !== '' ? 1 : 0;

		?>
		<div class="swell-menu-set">
				<div class="__settings">
					<div class="__field">
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $bold_key )?>"
								name="<?=esc_attr( $bold_name )?>"
								value="1"
								class="__enable_bold"
								<?=checked( (string) $editor[ $bold_key ], '1', false )?>
							/>
							<label for="<?=esc_attr( $bold_key )?>">太字</label>
						</div>
					</div>
					<div class="__field">
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $italic_key )?>"
								name="<?=esc_attr( $italic_name )?>"
								value="1"
								<?=checked( (string) $editor[ $italic_key ], '1', false )?>
							/>
							<label for="<?=esc_attr( $italic_key )?>">斜体</label>
						</div>
					</div>
					<div class="__field" data-is-enable=<?=esc_attr( $enable_color );?>>
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $color_key )?>"
								class="__toggle"
								<?=checked( (string) $enable_color, '1', false )?>
							/>
							<label for="<?=esc_attr( $color_key )?>">文字色</label>
						</div>
						<div class="__choices">
							<?php
							foreach ( $base_colors as $color ) :
								?>
								<label for="<?=esc_attr( $color_key . '_' . $color['slug'] )?>" class="__label">
									<input
										type="radio"
										id="<?=esc_attr( $color_key . '_' . $color['slug'] )?>"
										name="<?=esc_attr( $color_name )?>"
										value="<?=esc_attr( $color['slug'] );?>"
										class="__color u-none"
										<?=checked( (string) $editor[ $color_key ], $color['slug'], false )?>
									/>
									<span style="background:<?=esc_attr( $color['color'] );?>"><?=esc_html( $color['name'] )?></span>
								</label>
							<?php endforeach; ?>
							<?php
							foreach ( $custom_colors as $slug => $name ) :
								$val = \SWELL_Theme::get_editor( 'color_' . $slug );
								?>
								<label for="<?=esc_attr( $color_key . '_' . $slug )?>" class="__label">
									<input
										type="radio"
										id="<?=esc_attr( $color_key . '_' . $slug )?>"
										name="<?=esc_attr( $color_name )?>"
										value="<?=esc_attr( $slug );?>"
										class="__color u-none"
										<?=checked( (string) $editor[ $color_key ], $slug, false )?>
									/>
									<span style="background:<?=esc_attr( $val );?>"><?=esc_html( $name )?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="__field" data-is-enable=<?=esc_attr( $enable_bg );?>>
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $bg_key )?>"
								class="__toggle"
								<?=checked( (string) $enable_bg, '1', false )?>
							/>
							<label for="<?=esc_attr( $bg_key )?>">背景色</label>
						</div>
						<div class="__choices">
							<?php
							foreach ( $base_colors as $color ) :
								?>
								<label for="<?=esc_attr( $bg_key . '_' . $color['slug'] )?>" class="__label">
									<input
										type="radio"
										id="<?=esc_attr( $bg_key . '_' . $color['slug'] )?>"
										name="<?=esc_attr( $bg_name )?>"
										value="<?=esc_attr( $color['slug'] );?>"
										class="__bg u-none"
										<?=checked( (string) $editor[ $bg_key ], $color['slug'], false )?>
									/>
									<span style="background:<?=esc_attr( $color['color'] );?>"><?=esc_html( $color['name'] )?></span>
								</label>
							<?php endforeach; ?>
							<?php
							foreach ( $custom_colors as $slug => $name ) :
								$val = \SWELL_Theme::get_editor( 'color_' . $slug );
								?>
								<label for="<?=esc_attr( $bg_key . '_' . $slug )?>" class="__label">
									<input
										type="radio"
										id="<?=esc_attr( $bg_key . '_' . $slug )?>"
										name="<?=esc_attr( $bg_name )?>"
										value="<?=esc_attr( $slug );?>"
										class="__bg u-none"
										<?=checked( (string) $editor[ $bg_key ], $slug, false )?>
									/>
									<span style="background:<?=esc_attr( $val );?>"><?=esc_html( $name )?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="__field" data-is-enable=<?=esc_attr( $enable_marker );?>>
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $marker_key )?>"
								class="__toggle"
								<?=checked( (string) $enable_marker, '1', false )?>
							/>
							<label for="<?=esc_attr( $marker_key )?>">マーカー</label>
						</div>
						<div class="__choices">
							<?php
							foreach ( $marker_colors as $slug => $name ) :
								$val = \SWELL_Theme::get_editor( 'color_mark_' . $slug );
								?>
								<label for="<?=esc_attr( $marker_key . '_' . $slug )?>" class="__label">
									<input
										type="radio"
										id="<?=esc_attr( $marker_key . '_' . $slug )?>"
										name="<?=esc_attr( $marker_name )?>"
										value="<?=esc_attr( $slug );?>"
										class="__color u-none"
										<?=checked( (string) $editor[ $marker_key ], $slug, false )?>
									/>
									<span style="background:<?=esc_attr( $val );?>"><?=esc_html( $name )?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="__field" data-is-enable=<?=esc_attr( $enable_font_size );?>>
						<div class="__ttl">
							<input
								type="checkbox"
								id="<?=esc_attr( $font_size_key )?>"
								class="__toggle"
								<?=checked( (string) $enable_font_size, '1', false )?>
							/>
							<label for="<?=esc_attr( $font_size_key )?>">フォントサイズ</label>
						</div>
						<div class="__choices">
							<?php foreach ( $font_sizes as $slug => $name ) : ?>
								<p class="__fontsize">
									<input
										type="radio"
										id="<?=esc_attr( $font_size_key )?>"
										name="<?=esc_attr( $font_size_name )?>"
										value="<?=esc_attr( $slug )?>"
										<?=checked( (string) $editor[ $font_size_key ], $slug, false )?>
									/>
									<label for="<?=esc_attr( $font_size_key . '_' . $slug )?>"><?=esc_html( $name )?></label>
								</p>
							<?php endforeach; ?>
						</div>
					</div>
					<!-- <input type="text" class="colorpicker"/> -->
				</div>
				<div class="__preview">
					<span>ここにテキストが入ります。</span>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php

		if ( 2 === $i ) {
			echo '<br><small>※ チェックが一つも入っていない場合はエディター上で表示されません</small>';
		}
	}

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
				[__CLASS__, 'cb_format' ],
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
			[__CLASS__, 'cb_format_css' ],
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
