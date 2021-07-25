<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Border {

	/**
	 * ボーダー
	 */
	public static function border_settings( $page_name ) {

		$section_name = 'swell_section_border_set';

		add_settings_section(
			$section_name,
			'ボーダーセットの登録',
			'',
			$page_name
		);

		for ( $i = 1; $i < 5; $i++ ) {
			add_settings_field(
				'border_set_0' . $i, // フィールドID。何にも使わない
				'ボーダーセット 0' . $i,
				['\SWELL_THEME\Menu\Tab_Border', 'callback' ],
				$page_name,
				$section_name,
				[
					'num' => '0' . $i,
				]
			);
		}
	}


	/**
	 * ボーダー設定専用
	 */
	public static function callback( $args ) {

		$num = $args['num'];

		$field_id = 'border' . $num;
		$name     = \SWELL_Theme::DB_NAME_EDITORS . "[$field_id]";
		$val      = \SWELL_FUNC::get_editor( $field_id );

		$borderData = explode( ' ', $val );
		$style      = $borderData[0];
		$width      = absint( $borderData[1] );
		$color      = $borderData[2];

		$border_styles = [
			'solid',
			'double',
			'groove',
			'ridge',
			'inset',
			'outset',
			'dashed',
			'dotted',
		];

		$border_colors = [
			'var(--color_main)'   => 'メインカラー',
			'var(--color_border)' => 'グレー',
			'var(--color_gray)'   => '薄いグレー',
		];
		// カスタムカラーかどうか
		$isCustomColor = true;
	?>

		<div class="swell-menu-border">
			<div class="__settings">
				<div class="__item">
					<select name="" class="__style">
						<?php
						foreach ( $border_styles as $s ) :
							$slected = selected( $s, $style, false );
							echo '<option value="' . $s . '"' . $slected . '>' . $s . '</option>';
						endforeach;
						?>
					</select>
				</div>
				<div class="__item">
					<input type="number" class="__width" id="<?=$field_id?>" min="1" size="4" name="" value="<?=$width?>">px
				</div>
				<div class="__item">
					<select name="" class="__color">
						<?php
						foreach ( $border_colors as $key => $text ) :
							$slected                       = selected( $key, $color, false );
							if ( $slected ) $isCustomColor = false;
							echo '<option value="' . $key . '"' . $slected . '>' . $text . '</option>';
						endforeach;
						?>
						<?php $slected = $isCustomColor ? ' selected' : ''; ?>
						<option value="custom"<?=$slected?>>カスタム</option>
					</select>
					<div class="-customColor<?php echo $isCustomColor ? '' : ' u-none'; ?>">
						<input type="text" class="colorpicker __customColor" name="" value="<?=$color?>" />
					</div>
				</div>
			</div>
			<div class="__preview">
				<div class="__previewLabel">プレビュー</div>
				<div class="__previwBox" style="border:<?=$val?>">
					<span>コンテンツがここに入ります。</span>
				</div>
			</div>
			<input type="hidden" id="<?=$field_id?>" size="40" name="<?=$name?>" value="<?=$val?>" class="__hidden">
		</div>
	<?php
	}

}
