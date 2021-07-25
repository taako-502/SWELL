<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Others {


	/**
	 * ブログカードの設定
	 */
	public static function blogcard_settings( $page_name ) {
		$section_name = 'swell_section_blogcard';

		// セクションの追加
		add_settings_section(
			$section_name,
			'ブログカード',
			'',
			$page_name
		);

		$cb = ['\SWELL_THEME\Menu\Tab_Others', 'callback_for_blogcard' ];
		add_settings_field(
			'blocg_card_in', // フィールドID。何にも使わない
			'ブログカード（内部）',
			$cb,
			$page_name,
			$section_name,
			[
				'key'   => 'blog_card_type',
				'type'  => 'internal',
				'class' => 'tr-design',
			]
		);

		add_settings_field(
			'blocg_card_ex', // フィールドID。何にも使わない
			'ブログカード（外部）',
			$cb,
			$page_name,
			$section_name,
			[
				'key'   => 'blog_card_type_ex',
				'type'  => 'external',
				'class' => 'tr-design',
			]
		);
	}


	/**
	 * ブログカード設定用のコールバック
	 */
	public static function callback_for_blogcard( $args ) {

		$key = $args['key'];

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		$val  = \SWELL_FUNC::get_editor( $key );
		$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';

		$options = [
			'type1' => 'タイプ1',
			'type2' => 'タイプ2',
			'type3' => 'タイプ3',
		];
		?>
			<div class="swell-menu-blogcard">
				<div class="__settings">
					<select name="<?=$name?>" class="__blogcard">
						<?php
						foreach ( $options as $key => $text ) :
							$slected                       = selected( $key, $val, false );
							if ( $slected ) $isCustomColor = false;
							echo '<option value="' . $key . '"' . $slected . '>' . $text . '</option>';
						endforeach;
						?>
					</select>
				</div>
				<div class="__preview">
					<div class="__previewLabel">プレビュー</div>
					<div class="swell-block-postLink">
						<div class="p-blogCard -<?=$args['type']?>" data-type="<?=$val?>">
							<div class="p-blogCard__inner">
								<span class="p-blogCard__caption">
									<?php echo ( $args['type'] === 'internal' ) ? 'あわせて読みたい' : 'サイトのタイトル'; ?>
								</span>
								<div class="p-blogCard__thumb c-postThumb">
									<figure class="c-postThumb__figure">
										<span class="__thumb">サムネイル画像</span>
									</figure>
								</div>
								<div class="p-blogCard__body">
									<span class="p-blogCard__title">記事のタイトル</span>
									<span class="p-blogCard__excerpt">
										記事の抜粋文がここに入ります。記事の抜粋文がここに入ります。記事の抜粋文がここに入ります。
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}


	/**
	 * その他
	 */
	public static function blockquote_settings( $page_name ) {
		$section_name = 'swell_section_blockquote';

		// セクションの追加
		add_settings_section(
			$section_name,
			'引用',
			'', // function() {echo '<p>段落ブロック・グループブロックで利用できるボックススタイルのデザインを選択できます。</p>';},
			$page_name
		);

		add_settings_field(
			'blockquote_style', // フィールドID。何にも使わない
			'スタイル',
			['\SWELL_THEME\Menu\Tab_Others', 'callback_for_blockquote' ],
			$page_name,
			$section_name,
			[
				'item'    => 'blockquote',
				'key'     => 'blockquote_type',
				'class'   => 'tr-design',
				'choices' => [
					'simple'    => 'シンプル',
					'quotation' => 'クオーテーションマーク表示',
				],
			]
		);
	}


	/**
	 * その他のコールバック
	 */
	public static function callback_for_blockquote( $args ) {

		$key = $args['key'];

		// 使用するデータベース
		$db = \SWELL_Theme::DB_NAME_EDITORS;

		$val  = \SWELL_FUNC::get_editor( $key );
		$name = \SWELL_Theme::DB_NAME_EDITORS . '[' . $key . ']';

		$options = $args['choices'];

		$item = $args['item'];
		?>
			<div class="swell-menu-<?=$item?>">
				<div class="__settings">
					<select name="<?=$name?>" class="__<?=$item?>">
						<?php
						foreach ( $options as $key => $text ) :
							$slected                       = selected( $key, $val, false );
							if ( $slected ) $isCustomColor = false;
							echo '<option value="' . $key . '"' . $slected . '>' . $text . '</option>';
						endforeach;
						?>
					</select>
				</div>
				<div class="__preview">
					<div class="__previewLabel">プレビュー</div>
						<blockquote class="wp-block-quote __blockquote" data-type="<?=$val?>">
							<p>引用するテキストがここに入ります。</p>
							<cite>引用元</cite>
						</blockquote>
				</div>
			</div>
		<?php
	}

}
