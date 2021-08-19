<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;

require_once __DIR__ . '/pluggable_parts/header_parts.php';
require_once __DIR__ . '/pluggable_parts/page_parts.php';
require_once __DIR__ . '/pluggable_parts/list_parts.php';

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

/**
 * プロモーションバナー
 */
if ( ! function_exists( 'swl_parts__pr_banner' ) ) :
	function swl_parts__pr_banner( $args ) {
	?>
		<div class="c-prBanner">
			<a href="https://swell-theme.com" target="_blank" rel="noopener" class="c-prBanner__link">
			<img src="<?=esc_url( T_DIRE_URI )?>/assets/img/swell2_pr_banner.jpg" class="c-prBanner__img" alt="シンプル美と機能性を両立させた、国内最高峰のWordPressテーマ『SWELL』" width="900" height="750" loading="lazy">
			</a>
		</div>
	<?php
	}
endif;


/**
 * 関連記事テキストリンク
 */
if ( ! function_exists( 'swl_parts__blog_link' ) ) :
	function swl_parts__blog_link( $args ) {
		$url      = $args['url'] ?? '';
		$title    = $args['title'] ?? '';
		$rel      = $args['rel'] ?? '';
		$is_blank = $args['is_blank'] ?? '';

		if ( ! $url ) return;

		// target, rel
		$props = ( $is_blank ) ? ' target="_blank"' : '';
		$rel   = $rel ?: ( $is_blank ? 'noopener noreferrer' : '' );
		if ( $rel ) {
			$props .= ' rel="' . esc_attr( $rel ) . '"';
		}
		?>
			<a href="<?=esc_url( $url )?>" class="c-blogLink"<?=$props?>>
				<i class="c-blogLink__icon icon-link" role="presentation"></i>
				<span class="c-blogLink__text"><?=esc_html( $title )?></span>
			</a>
		<?php
	}
endif;


/**
 * 関連記事ブログカード
 */
if ( ! function_exists( 'swl_parts__blog_card' ) ) :
	function swl_parts__blog_card( $args ) {

		// 初期値とマージ
		$args = array_merge([
			'url'       => '',
			'site_name' => '',
			'caption'   => '',
			'title'     => '',
			'thumb'     => '',
			'excerpt'   => '',
			'is_blank'  => false,
			'add_class' => '',
			'rel'       => '',
			'noimg'     => false,
			'nodesc'    => false,
			'type'      => 'type1',
		], $args );

		// 変数化
		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		if ( ! $url ) return;

		$caption    = $caption ?: $site_name ?: 'あわせて読みたい';
		$card_class = 'p-blogCard';
		if ( $add_class ) {
			$card_class .= ' ' . $add_class;
		}

		// 画像を表示するか
		$card_thumb = '';
		if ( '' === $thumb || $noimg ) {
			$card_class .= ' -noimg';
		} else {
			$card_img   = '<img src="' . esc_url( $thumb ) . '" alt="" class="c-postThumb__img u-obf-cover -no-lb" width="320" height="180">';
			$card_thumb = '<div class="p-blogCard__thumb c-postThumb"><figure class="c-postThumb__figure">' .
			$card_img . '</figure></div>';
		}

		$card_excerpt = $nodesc ? '' : '<span class="p-blogCard__excerpt">' . esc_html( $excerpt ) . '</span>';

		// target, rel
		$props = ( $is_blank ) ? ' target="_blank"' : '';
		$rel   = $rel ?: ( $is_blank ? 'noopener noreferrer' : '' );
		if ( $rel ) {
			$props .= ' rel="' . esc_attr( $rel ) . '"';
		}

		?>
			<a href="<?=esc_url( $url )?>" class="<?=esc_attr( $card_class )?>" data-type="<?=esc_attr( $type )?>"<?=$props?>>
			<div class="p-blogCard__inner">
				<span class="p-blogCard__caption"><?=esc_html( $caption )?></span>
				<?=$card_thumb?>
				<div class="p-blogCard__body">
					<span class="p-blogCard__title"><?=esc_html( $title )?></span>
					<?=$card_excerpt?>
				</div>
			</div></a>
		<?php
	}
endif;


/**
 * MVボタン
 */
if ( ! function_exists( 'swl_parts__mv_btn' ) ) :
	function swl_parts__mv_btn( $args ) {
		$href     = $args['href'] ?? '';
		$text     = $args['text'] ?? '';
		$btn_col  = $args['btn_col'] ?? '';
		$btn_type = $args['btn_type'] ?? 'n';

		$props = '';
		if ( strpos( $href, '#' ) !== 0 && strpos( $href, SWELL::site_data( 'home' ) ) === false ) {
			$props .= ' rel="noopener" target="_blank"';
		}

		if ( 'n' === $btn_type ) {
			// -btn-n の メインカラー追従は CSS で
			if ( $btn_col ) {
				$props .= ' style="background-color:' . esc_attr( $btn_col ) . '"';
			}
		} elseif ( 'b' === $btn_type ) {
			$btn_col = $btn_col ?: SWELL::get_setting( 'color_main' );
			if ( $btn_col ) {
				$props .= ' style="color:' . esc_attr( $btn_col ) . '"';
			}
		}
	?>
		<div class="p-mainVisual__slideBtn c-mvBtn -btn-<?=esc_attr( $btn_type )?>">
			<a href="<?=esc_url( $href )?>" class="c-mvBtn__btn"<?=$props?>><?=esc_html( $text )?></a>
		</div>
	<?php
	}
endif;


/**
 * MV用スクロールアイコン
 */
if ( ! function_exists( 'swl_parts__scroll_arrow' ) ) :
	function swl_parts__scroll_arrow( $args ) {
		$color = $args['color'] ?? '';
	?>
		<div class="p-mainVisual__scroll" role="button" data-onclick="scrollToContent" style="color:<?=esc_attr( $color )?>">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 80 80" class="p-mainVisual__scrollArrow">
				<path d="M5.9,14.4l-2.9,5C3,19.5,3,19.6,3,19.8c0,0.1,0.1,0.2,0.2,0.3l36.4,21c0.1,0,0.2,0.1,0.3,0.1c0.1,0,0.2,0,0.3-0.1l36.4-21 c0.1-0.1,0.2-0.2,0.2-0.3c0-0.1,0-0.3-0.1-0.4l-2.9-5c-0.1-0.1-0.2-0.2-0.3-0.2c-0.1,0-0.3,0-0.4,0.1L40,33.5L6.7,14.2 c-0.1,0-0.2-0.1-0.3-0.1c0,0-0.1,0-0.1,0C6.1,14.2,6,14.3,5.9,14.4z"/>
				<path d="M5.9,39.1l-2.9,5c-0.1,0.3-0.1,0.6,0.2,0.7l36.4,21c0.1,0,0.2,0.1,0.3,0.1c0.1,0,0.2,0,0.3-0.1l36.4-21 c0.2-0.1,0.2-0.2,0.2-0.3s0-0.2-0.1-0.4l-2.9-5c-0.1-0.1-0.2-0.2-0.3-0.2l0,0c-0.1,0-0.3,0-0.4,0.1L40,58.1L6.7,38.9 c-0.1,0-0.2-0.1-0.3-0.1c0,0-0.1,0-0.1,0C6.1,38.8,6,38.9,5.9,39.1z"/>
			</svg>
			<span class="p-mainVisual__scrollLabel">Scroll</span>
		</div>
		<?php
	}
endif;


/**
 * ピックアップバナー
 */
if ( ! function_exists( 'swl_parts__pickup_banner' ) ) :
	function swl_parts__pickup_banner( $args ) {
		$item       = $args['item'] ?? null;
		$menu_count = $args['menu_count'] ?? 1;
		if ( ! $item ) return;

		// aタグの属性値
		$a_props = 'href="' . $item->url . '"';
		if ( $item->target === '_blank' ) {
			$a_props .= ' target="_blank" rel="noopener noreferrer"';
		}

		// レイアウトに合わせてsizes取得
		$sizes = SWELL::get_pickup_banner_sizes( $menu_count );

		$lazy_type = apply_filters( 'swell_pickup_banner_lazy_type', 'lazy' );
		$img_class = 'c-bannerLink__img'; // u-obf-cover

		// 説明欄に直接画像URLがある場合
		$img_url = $item->description;
		if ( $img_url ) {
			$img_id = SWELL::get_imgid_from_url( $img_url );
			$thumb  = SWELL::get_image( $img_id, [
				'class'   => $img_class,
				'sizes'   => $sizes,
				'loading' => $lazy_type,
			]);

		} elseif ( $item->type === 'post_type' ) {

			$post_id = $item->object_id;
			$thumb   = SWELL::get_thumbnail( [
				'post_id'   => $post_id,
				'sizes'     => $sizes,
				'class'     => $img_class,
				'lazy_type' => $lazy_type,
			] );

		} elseif ( $item->type === 'taxonomy' ) {
			$thumb = SWELL::get_thumbnail( [
				'term_id'   => $item->object_id,
				'sizes'     => $sizes,
				'class'     => $img_class,
				'lazy_type' => $lazy_type,
			] );
		}

		// 画像なければ NO IMAGE
		if ( ! $thumb ) {
			$thumb = '<img src="' . esc_url( SWELL::get_noimg( 'url' ) ) . '" alt="" class="' . esc_attr( $img_class ) . '" loading="lazy">';
		}

	?>
		<a <?=$a_props?> class="c-bannerLink">
			<figure class="c-bannerLink__figure"><?=$thumb?></figure>
			<span class="c-bannerLink__label"><?=esc_html( $item->title )?></span>
		</a>
	<?php
	}
endif;


/**
 * IEアラート
 */
if ( ! function_exists( 'swl_parts__ie_alert' ) ) :
	function swl_parts__ie_alert() {
?>
<style>
.ie-alertbox {
	position: fixed;
	right: 0;
	bottom: 0;
	z-index: 9999;
	display:-ms-flexbox;
	display: flex;
	-ms-flex-pack: center;
	justify-content: center;
	-ms-flex-align: center;
	align-items: center;

	box-sizing: border-box;
	width: 100%;
	max-width: 100%;
	height: 100%;
	max-height: 100%;
	padding: 2em;
	background: rgba(0, 0, 0, .7);
}

.__inner {
	position: relative;
	z-index: 1;
	display:-ms-flexbox;
	display: flex;
	-ms-flex-direction: row;
	flex-direction: column;
	-ms-flex-pack: center;
	justify-content: center;
	width: 100%;
	max-width: 1000px;
	height: 100%;
	max-height: 600px;
	text-align: center;
	background: rgba(185, 43, 0, .5);
	color: #fff;
	font-family: Meiryo, sans-serif;
	line-height: 1.6;
	font-size: 16px;
	box-sizing: border-box;
}

.ie-alertbox .__text a {
	text-decoration: underline;
	color: #87ceff;
}
</style>
		<div class="ie-alertbox">
			<div class="__inner">
				<div class="__title u-mb-10 u-fz-xl">
					<b class="icon-alert"> お使いのブラウザーは安全ではありません！</b>
				</div>
				<div class="__text">
					ご利用中の <b>Internet Explorer</b> はとても古いブラウザです。
					<br>当サイトをIEで閲覧することはできません。
					<br><br>
					開発元のMicrosoftも<a href="https://www.microsoft.com/ja-jp/edge">Microsoft Edge</a>への移行を強く推奨しており、<br>
				　　2022年 6月 には正式にサポートが終了します。
					<br><br>
					Edgeもしくはその他のモダンブラウザ（<a href="https://www.google.co.jp/chrome/index.html">Google Chrome</a> など）をご利用ください。
				</div>
			</div>
		</div>
		<?php
	}
endif;
