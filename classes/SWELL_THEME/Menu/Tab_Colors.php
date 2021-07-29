<?php
namespace SWELL_THEME\Menu;

use SWELL as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Colors {

	/**
	 * カラーパレットの設定
	 */
	public static function palette_settings( $page_name ) {
		$section_name = 'swell_section_palette_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'カラーパレット設定',
			'',
			$page_name
		);

		add_settings_field(
			'color_palette_dark', // フィールドID。何にも使わない
			'カラーパレット【濃】',
			['\SWELL_THEME\Menu\Tab_Colors', 'callback_for_palette' ],
			$page_name,
			$section_name,
			[
				'keys' => [
					'deep01' => '濃い色1',
					'deep02' => '濃い色2',
					'deep03' => '濃い色3',
					'deep04' => '濃い色4',
				],
			]
		);
		add_settings_field(
			'color_palette_thin', // フィールドID。何にも使わない
			'カラーパレット【淡】',
			['\SWELL_THEME\Menu\Tab_Colors', 'callback_for_palette' ],
			$page_name,
			$section_name,
			[
				'keys' => [
					'pale01' => '淡い色1',
					'pale02' => '淡い色2',
					'pale03' => '淡い色3',
					'pale04' => '淡い色4',
				],
			]
		);
	}


	/**
	 * カラーパレット設定用の専用コールバック
	 */
	public static function callback_for_palette( $args ) {

		$keys = $args['keys'];

		// 使用するデータベース
		$db = SWELL::DB_NAME_EDITORS;

		foreach ( $keys as $key => $label ) :
			$key = 'color_' . $key;

			// 現在の値
			$val = SWELL::get_editor( $key );
			// デフォルト値
			$dflt = SWELL::get_default_editor( $key );
			// フォーム要素のname属性に渡す値。
			$name = SWELL::DB_NAME_EDITORS . '[' . $key . ']';
			?>
				<div class="swell-menu-palette">
					<span class="__label"><?=$label?></span>
					<input type="text" class="colorpicker"
						id="<?=$key?>"
						name="<?=$name?>"
						value="<?=$val?>"
						data-default-color="<?=$dflt?>"
					/>
				</div>
			<?php
		endforeach;
	}


	/**
	 * リスト
	 */
	public static function list_settings( $page_name ) {
		$section_name = 'swell_section_list_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'リスト設定',
			'',
			$page_name
		);

		$icons = [
			'check' => 'チェック',
			'num'   => '塗り番号',
			'good'  => 'マル',
			'bad'   => 'バツ',
		];
		foreach ( $icons as $key => $label ) {
			add_settings_field(
				'color_list_' . $key,
				$label,
				['\SWELL_THEME\Menu\Tab_Colors', 'callback_for_list' ],
				$page_name,
				$section_name,
				[
					'class' => 'tr-list',
					'key'   => $key,
					'type'  => 'big',
				]
			);
		}
	}


	/**
	 * リスト用コールバック
	 */
	public static function callback_for_list( $args ) {

		// 使用するデータベース
		$db = SWELL::DB_NAME_EDITORS;

		// key
		$key       = $args['key'];
		$color_key = 'color_list_' . $key;

		// 現在の値
		$color_val = SWELL::get_editor( $color_key );

		// デフォルト値
		$dflt_color = SWELL::get_default_editor( $color_key );

		// フォーム要素のname属性に渡す値。
		$name = SWELL::DB_NAME_EDITORS . '[' . $color_key . ']';

		$tag   = $key === 'num' ? 'ol' : 'ul';
		$class = $key === 'num' ? 'is-style-num_circle' : 'is-style-' . $key . '_list';
		?>
			<div class="swell-menu-list">
				<div class="__settings">
					<input type="text" class="colorpicker __list_color"
						id="<?=$color_key?>"
						name="<?=$name?>"
						value="<?=$color_val?>"
						data-default-color="<?=$dflt_color?>"
						data-key="<?=$color_key?>"
					/>
					<?php if ( $key === 'check' || $key === 'num' ) : ?>
						<p class="__description">※ 色の指定がない場合は<br>メインカラーが適用されます。</p>
					<?php endif; ?>
				</div>
				<div class="__preview">
					<<?=$tag?> class="<?=$class?>">
						<li>リスト</li>
						<li>リスト</li>
					</<?=$tag?>>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}


	/**
	 * キャプションブロック
	 */
	public static function capblock_settings( $page_name ) {
		$section_name = 'swell_section_capblock_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'キャプションブロック設定',
			'',
			$page_name
		);

		$sets = ['01', '02', '03' ];
		foreach ( $sets as $set ) {
			add_settings_field(
				'color_capblock_' . $set,
				'カラーセット' . $set,
				['\SWELL_THEME\Menu\Tab_Colors', 'callback_for_capblock' ],
				$page_name,
				$section_name,
				[
					'class' => 'tr-capbox',
					'key'   => 'cap_' . $set,
				]
			);
		}
	}


	/**
	 * キャプションブロック用コールバック
	 */
	public static function callback_for_capblock( $args ) {

		// 使用するデータベース
		$db = SWELL::DB_NAME_EDITORS;

		// key
		$key       = $args['key'];
		$key_dark  = 'color_' . $key;
		$key_light = 'color_' . $key . '_light';

		// 現在の値
		$val_dark  = SWELL::get_editor( $key_dark );
		$val_light = SWELL::get_editor( $key_light );

		// デフォルト値
		$dflt_dark  = SWELL::get_default_editor( $key_dark );
		$dflt_light = SWELL::get_default_editor( $key_light );

		// フォーム要素のname属性に渡す値。
		$name_dark  = SWELL::DB_NAME_EDITORS . '[' . $key_dark . ']';
		$name_light = SWELL::DB_NAME_EDITORS . '[' . $key_light . ']';
		?>
			<div class="swell-menu-capbox">
				<div class="__settings">
					<input type="text" class="colorpicker __dark"
						id="<?=$key_dark?>"
						name="<?=$name_dark?>"
						value="<?=$val_dark?>"
						data-default-color="<?=$dflt_dark?>"
					/>
					<input type="text" class="colorpicker __light"
						id="<?=$key_light?>"
						name="<?=$name_light?>"
						value="<?=$val_light?>"
						data-default-color="<?=$dflt_light?>"
					/>
				</div>
				<div class="__preview">
					<div class="swell-block-capbox cap_box" data-colset="<?=str_replace( 'cap_0', 'col', $key )?>">
						<div class="cap_box_ttl">キャプション</div>
						<div class="cap_box_content">
							<p>ここにコンテンツが入ります</p>
						</div>
					</div>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}


	/**
	 * FAQ
	 */
	public static function faq_settings( $page_name ) {
		$section_name = 'swell_section_faq_color';

		// セクションの追加
		add_settings_section(
			$section_name,
			'Q&A設定',
			'',
			$page_name
		);

		add_settings_field(
			'color_faq',
			'カラー',
			['\SWELL_THEME\Menu\Tab_Colors', 'callback_for_faq' ],
			$page_name,
			$section_name,
			[
				'class' => 'tr-faq',
				// 'key' => $key,
			]
		);
	}


	/**
	 * FAQ用コールバック
	 */
	public static function callback_for_faq( $args ) {

		// 使用するデータベース
		$db = SWELL::DB_NAME_EDITORS;

		// key
		// $key = $args['key'];
		$color_key_q = 'color_faq_q';
		$color_key_a = 'color_faq_a';

		// 現在の値
		$color_val_q = SWELL::get_editor( $color_key_q );
		$color_val_a = SWELL::get_editor( $color_key_a );

		// デフォルト値
		$dflt_color_q = SWELL::get_default_editor( $color_key_q );
		$dflt_color_a = SWELL::get_default_editor( $color_key_a );

		// フォーム要素のname属性に渡す値。
		$name_q = SWELL::DB_NAME_EDITORS . '[' . $color_key_q . ']';
		$name_a = SWELL::DB_NAME_EDITORS . '[' . $color_key_a . ']';

		?>
			<div class="swell-menu-faq">
				<div class="__settings">
					<div class="__q">
						<label for="<?=$color_key_q?>">Q : </label>
						<input type="text" class="colorpicker __faq_color"
							id="<?=$color_key_q?>"
							name="<?=$name_q?>"
							value="<?=$color_val_q?>"
							data-default-color="<?=$dflt_color_q?>"
							data-key="<?=$color_key_q?>"
						/>
					</div>
					<div class="__a">
						<label for="<?=$color_key_a?>">A : </label>
						<input type="text" class="colorpicker __faq_color"
							id="<?=$color_key_a?>"
							name="<?=$name_a?>"
							value="<?=$color_val_a?>"
							data-default-color="<?=$dflt_color_a?>"
							data-key="<?=$color_key_a?>"
						/>
					</div>
				</div>
				<div class="__preview">
					<div class="swell-block-faq" data-q="fill-custom" data-a="fill-custom">
						<div class="swell-block-faq__item">
							<dt class="faq_q">質問</dt>
							<dd class="faq_a"><p>回答</p></dd>
						</div>
					</div>
					<div class="swell-block-faq" data-q="col-custom" data-a="col-custom">
						<div class="swell-block-faq__item">
							<dt class="faq_q">質問</dt>
							<dd class="faq_a"><p>回答</p></dd>
						</div>
					</div>
					<div class="__previewLabel">プレビュー</div>
				</div>
			</div>
		<?php
	}

}
