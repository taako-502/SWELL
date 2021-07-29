<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Btn {

	/**
	 * ボタン
	 */
	public static function btn_settings( $page_name ) {

		$section_name = 'swell_section_btns';

		add_settings_section(
			$section_name,
			'SWELLボタンの設定',
			'',
			$page_name
		);

		add_settings_field(
			'swell_btn_color', // フィールドID
			'カラー設定',
			['\SWELL_THEME\Menu\Tab_Btn', 'callback_for_colors' ],
			$page_name,
			$section_name,
			[
				'class'  => 'btn_colors',
				'colors' => [
					'red'   => '赤',
					'blue'  => '青',
					'green' => '緑',
				],
			]
		);

		add_settings_field(
			'swell_btn_radius', // フィールドID
			'ボタンの丸み',
			['\SWELL_THEME\Menu\Tab_Btn', 'callback_for_radius' ],
			$page_name,
			$section_name,
			[
				'class' => 'btn_radius',
			]
		);

		add_settings_field(
			'swell_btn_preview', // フィールドID
			'',
			['\SWELL_THEME\Menu\Tab_Btn', 'callback_for_preview' ],
			$page_name,
			$section_name,
			[
				'class' => 'btn_preview',
			]
		);
	}


	/**
	 * コールバック
	 */
	public static function callback_for_gradation( $args ) {
		$key   = 'is_btn_gradation';
		$val   = \SWELL_Theme::get_editor( $key );
		$name  = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';
		$label = 'ボタンのグラデーションをオンにする';

		$checked = checked( (string) $val, '1', false );
		echo '<input type="hidden" name="' . $name . '" value="">' .
			'<input type="checkbox" id="' . $key . '" name="' . $name . '" value="1" ' . $checked . ' class="__gradation"/>' .
			'<label for="' . $key . '">' . $label . '</label>';
		echo '<p class="description">※ ノーマルボタン・キラッとボタンでのみ有効</p>';
	}

	public static function callback_for_radius( $args ) {
		$btn_styles = [
			'normal' => 'ノーマル',
			'solid'  => '立体',
			'shiny'  => 'キラッと',
			'line'   => 'アウトライン',
		];
	?>
		<div class="swell-menu-btn">
			<div class="__settings">
				<?php
				foreach ( $btn_styles as $style => $btn_name ) :
					$key = 'btn_radius_' . $style;

					$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';
					$val  = \SWELL_Theme::get_editor( $key );

					$choices = [
						'0px'  => '丸みなし',
						'4px'  => '少し丸める',
						'80px' => '丸める',
					];
				?>
				<div class="__field">
					<div class="__btnName"><?=$btn_name?></div>
					<?php
					foreach ( $choices as $px => $label ) :
						$checked = checked( $val, $px, false );
					?>
						<label for="<?=$key . '_' . $px?>" class="__radioLabel">
							<input type="radio" id="<?=$key . '_' . $px?>" name="<?=$name?>" value="<?=$px?>"<?=$checked?> class="u-none">
							<span class="__btn __radius_<?=$px?>"><?=$label?></span>
						</label>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php
	}

	public static function callback_for_colors( $args ) {

		$db     = \SWELL_Theme::DB_NAME_EDITORS;
		$colors = $args['colors'];

		$is_gradation = \SWELL_Theme::get_editor( 'is_btn_gradation' );
	?>

		<div class="swell-menu-btn" data-is-grad="<?=$is_gradation?>">
			<div class="__settings">
				<?php
				foreach ( $colors as $color => $color_name ) :
					$color2 = $color . '2';

					$key  = 'color_btn_' . $color;
					$val  = \SWELL_Theme::get_editor( $key );
					$dflt = \SWELL_Theme::get_default_editor( $key );
					$name = $db . '[' . $key . ']';

					$key2  = 'color_btn_' . $color2;
					$val2  = \SWELL_Theme::get_editor( $key2 );
					$dflt2 = \SWELL_Theme::get_default_editor( $key2 );
					$name2 = $db . '[' . $key2 . ']';
				?>
				<div class="__field -btnColor">
					<span class="__colorName"><?=$color_name?> : </span>
					<input type="text" class="colorpicker __<?=$color?>"
						id="<?=$key?>"
						name="<?=$name?>"
						value="<?=$val?>"
						data-default-color="<?=$dflt?>"
					/>
					<input type="text" class="colorpicker __<?=$color2?> -for-gradation"
						id="<?=$key2?>"
						name="<?=$name2?>"
						value="<?=$val2?>"
						data-default-color="<?=$dflt2?>"
					/>
					</div>
				<?php endforeach; ?>

				<?php
				// グラデーション設定
				$key   = 'is_btn_gradation';
				$val   = $is_gradation;
				$name  = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';
				$label = 'ボタンのグラデーションをオンにする';

				$checked = checked( (string) $val, '1', false );

			?>
			<div class="__field -gradation  u-mt-10">
				<input type="hidden" name="<?=$name?>" value="">
					<input type="checkbox" id="<?=$key?>" name="<?=$name ?>" value="1" <?=$checked?> class="__gradation"/>
					<label for="<?=$key?>"><?=$label?></label>
				<p class="description">※ ノーマルボタン・キラッとボタンでのみ有効</p>
			</div>
			</div>
		</div>
	<?php
	}

	public static function callback_for_preview( $args ) {
		$is_gradation = \SWELL_Theme::get_editor( 'is_btn_gradation' );

		?>
			<div class="swell-menu-btn -preview" data-is-grad="<?=$is_gradation?>">
				<div class="__preview">
					<div class="__previewLabel">プレビュー（ノーマルボタン）</div>
					<div class="__prevRow">
						<div class="swell-block-button red_ is-style-btn_normal -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button blue_ is-style-btn_normal -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button green_ is-style-btn_normal -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
					</div>
				</div>
				<div class="__preview">
					<div class="__previewLabel">プレビュー（立体ボタン）</div>
					<div class="__prevRow">
						<div class="swell-block-button red_ is-style-btn_solid -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button blue_ is-style-btn_solid -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button green_ is-style-btn_solid -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
					</div>
				</div>
				<div class="__preview">
					<div class="__previewLabel">プレビュー（キラッとボタン）</div>
					<div class="__prevRow">
						<div class="swell-block-button red_ is-style-btn_shiny -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button blue_ is-style-btn_shiny -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button green_ is-style-btn_shiny -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
					</div>
				</div>
				<div class="__preview">
					<div class="__previewLabel">プレビュー（アウトラインボタン）</div>
					<div class="__prevRow">
						<div class="swell-block-button red_ is-style-btn_line -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button blue_ is-style-btn_line -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
						<div class="swell-block-button green_ is-style-btn_line -size-s">
							<a class="swell-block-button__link" href="javascript:void(0)">BUTTON</a>
						</div>
					</div>
				</div>
			</div>
		<?php
	}


}
