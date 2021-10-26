<?php
namespace SWELL_THEME\Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tab_Speed {

	/**
	 * キャッシュ
	 */
	public static function cache_settings( $page_name, $cb ) {

		$section_name   = 'swell_section_cache';
		$cache_settings = [
			'cache_style'       => ['動的なCSSをキャッシュする', '' ],
			'cache_header'      => ['ヘッダーをキャッシュする', '' ],
			'cache_sidebar'     => ['サイドバーをキャッシュする', '' ],
			'cache_bottom_menu' => ['下部固定メニューをキャッシュする', '' ],
			'cache_spmenu'      => ['スマホ開閉メニューをキャッシュする', '※ ウィジェットのページ分岐は効かなくなります' ],
			'cache_top'         => ['トップページコンテンツをキャッシュする', 'メインビジュアル・記事スライダー・ピックアップバナー・記事一覧リストがキャッシュされます' ],
			'cache_blogcard_in' => ['内部リンクのブログカードをキャッシュする', '' ],
			'cache_blogcard_ex' => ['外部リンクのブログカードをキャッシュする', '' ],
		];

		add_settings_section(
			$section_name,
			'キャッシュ機能',
			'',
			$page_name
		);

		foreach ( $cache_settings as $key => $data ) {
			add_settings_field(
				$key,
				'', // $data[0],
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'checkbox',
					'label' => $data[0],
					'desc'  => $data[1],
				]
			);
		}

		add_settings_field(
			'cache_card_time',
			'', // 'ブログカードのキャッシュ期間',
			$cb,
			$page_name,
			$section_name,
			[
				'id'         => 'cache_card_time',
				'type'       => 'input',
				'input_type' => 'number',
				'before'     => '<p class="u-mb-5"><b>ブログカードのキャッシュ期間</b></p>',
				'after'      => '日',
			]
		);
	}


	/**
	 * 遅延読み込み機能
	 */
	public static function lazyload_settings( $page_name, $cb ) {

		$section_name      = 'swell_section_lazyload';
		$lazyload_settings = [
			'ajax_after_post'      => ['記事下コンテンツを遅延読み込みさせる', '' ],
			'ajax_footer'          => ['フッターを遅延読み込みさせる', '※ ウィジェットのページ分岐は効かなくなります。' ],
		];

		add_settings_section(
			$section_name,
			'遅延読み込み機能',
			'',
			$page_name
		);

		foreach ( $lazyload_settings as $key => $data ) {
			if ( null === $data[1] ) {
				add_settings_field(
					$key,
					$data[0],
					'__return_false',
					$page_name,
					$section_name,
					[]
				);
			} else {
				add_settings_field(
					$key,
					'', // $data[0],
					$cb,
					$page_name,
					$section_name,
					[
						'id'    => $key,
						'type'  => 'checkbox',
						'label' => $data[0],
						'desc'  => $data[1],
					]
				);
			}
		}

		add_settings_field(
			'label_lazyload',
			'画像等のLazyload',
			'__return_false',
			$page_name,
			$section_name,
			[]
		);

		$note_text = __( 'Note : ', 'swell' );
		add_settings_field(
			'lazy_type',
			'',
			$cb,
			$page_name,
			$section_name,
			[
				'id'      => 'lazy_type',
				'type'    => 'radio',
				'choices' => [
					'none'      => '使用しない',
					'lazy'      => '<code>loading="lazy"</code>を使用する',
					'lazysizes' => 'スクリプト(lazysizes.js)を使って遅延読み込みさせる<br><small>' . $note_text . 'img, video, iframeタグに適用されます。',
				],
			]
		);

		add_settings_field(
			'label_lazyloadscript',
			'スクリプトの遅延読み込み',
			'__return_false',
			$page_name,
			$section_name,
			[]
		);

		add_settings_field(
			'use_delay_js',
			'',
			$cb,
			$page_name,
			$section_name,
			[
				'id'    => 'use_delay_js',
				'type'  => 'checkbox',
				'label' => '外部スクリプトを遅延読み込みさせる',
				// 'desc'  => 'twitter, facebook, instagram, pinterest用のスクリプトが遅延読み込みされます。',
			]
		);

		add_settings_field(
			'delay_js_time',
			'',
			$cb,
			$page_name,
			$section_name,
			[
				'id'         => 'delay_js_time',
				'type'       => 'input',
				'step'       => '100',
				'input_type' => 'number',
				'before'     => '<p class="u-mb-5"><b>何秒遅延させるか</b></p>',
				'after'      => '[m秒]',
			]
		);

	}

	/**
	 * ページ遷移高速化
	 */
	public static function pjax_settings( $page_name, $cb ) {

		$section_name = 'swell_section_pjax';

		add_settings_section(
			$section_name,
			'ページ遷移高速化',
			'',
			$page_name
		);

		add_settings_field(
			'use_pjax',
			'高速化の種類',
			$cb,
			$page_name,
			$section_name,
			[
				'id'      => 'use_pjax',
				'type'    => 'radio',
				// 'label' => $data[0],
				'desc'    => '※ Pjax機能についてはいくつか注意点がございます。<a href="https://swell-theme.com/function/5978/" target="_blank">こちらのページ</a>をご一読ください。',
				'choices' => [
					'off'      => '使用しない',
					'prefetch' => 'Prefetch',
					'pjax'     => 'Pjaxによる遷移（非推奨）',
				],
			]
		);

		add_settings_field(
			'pjax_prevent_pages',
			'Pjaxで遷移させないページのURL',
			$cb,
			$page_name,
			$section_name,
			[
				'id'   => 'pjax_prevent_pages',
				'type' => 'textarea',
				'desc' => '複数の場合は「,（+改行）」で区切ってください。また、「http(s)://」から指定しない場合は、その文字列を含む全ページが対象となります。',
			]
		);
		add_settings_field(
			'prefetch_prevent_keys',
			'Prefetchさせないページのキーワード',
			$cb,
			$page_name,
			$section_name,
			[
				'id'   => 'prefetch_prevent_keys',
				'type' => 'textarea',
				'desc' => '複数の場合は「,」で区切ってください。指定した文字列を含む全ページが対象となります。',
			]
		);

	}


	/**
	 * ページ遷移高速化
	 */
	public static function file_load_settings( $page_name, $cb ) {
		$section_name  = 'swell_section_file_load';
		$load_settings = [
			'load_style_inline' => [ 'SWELLのCSSをインラインで読み込む', '' ],
			'separate_style'    => [ 'コンテンツに合わせて必要なCSSだけを読み込む【β機能】', '※ 全てのCSSが最適化されて読み込まれるわけではありません。' ],
			'load_style_async'  => [ 'フッター付近のCSSを遅延読み込みさせる【β機能】', '※「SWELLのCSSをインラインで読み込む」がオンの時は効果がありません。' ],

		];

		add_settings_section(
			$section_name,
			'ファイルの読み込み',
			'',
			$page_name
		);

		foreach ( $load_settings as $key => $data ) {
			add_settings_field(
				$key,
				'', // $data[0],
				$cb,
				$page_name,
				$section_name,
				[
					'id'    => $key,
					'type'  => 'checkbox',
					'label' => $data[0],
					'desc'  => $data[1],
				]
			);
		}
	}
}
