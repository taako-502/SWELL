<?php
namespace SWELL_Theme;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

class TOC {

	/**
	 * CSS変数をまとめておく
	 */
	public static $toc_data = [];



	/**
	 * :rootスタイル生成
	 */
	// public static function add_root( $name, $val, $media_query = 'all' ) {
	// 	self::$root_styles[ $media_query ] .= $name . ':' . $val . ';';
	// }


	/**
	 * 目次の生成
	 * https://blog-and-destroy.com/2013
	 */
	public static function create_toc_data( $content ) {

		// すでに作成済みのとき
		if ( ! empty( self::$toc_data ) ) return;

		// 属性を持たないh2・h3要素を正規表現で表すパターン
		$pattern = '/^<(h[2-3])(.*?)>(.*?)<\/h[2-3]>/im';
		// 本文の中から、すべてのh2・h3要素を検索
		preg_match_all( $pattern, $content, $htags, PREG_SET_ORDER );

		foreach ( $htags as $htag_data ) {
			$html  = $htag_data[0];
			$tag   = $htag_data[1];
			$props = $htag_data[2];
			$text  = $htag_data[3]; // strip_tags ?

			echo '<pre style="margin-left: 100px;">';
			var_dump( $html );
			echo '</pre>';

			// class = 'has-swl-deep-02-color has-text-color'
			// $pattern = '/<h[2-3](.*?)>(.*?)<\/h[2-3]>/i';

			// 目次に出力したくないクラスの配列
			$ignores = apply_filters( 'swell_toc_ignore_classes', [] );

			// htagのclassを取得
			$class = SWELL::get_prop_in_html( $props, 'class' );

			if ( $class ) {

				$classes = explode( ' ', $class );

				// classを持っていればスキップ対象かどうかチェック
				if ( ! empty( $classes ) ) {
					$is_skip = false;
					foreach ( $classes as $class_name ) {
						if ( in_array( $class_name, $ignores, true ) ) {
							$is_skip = true;
						}
					}

					if ( $is_skip ) continue;
				}
			}

			// id チェック
			$id = SWELL::get_prop_in_html( $props, 'id' );

			// 目次データ追加
			self::$toc_data[] = [
				'html'    => $html,
				'tag'     => $tag,
				'id'      => $id,
				'class'   => $class,
				'text'    => $text,
			];
		}

		return self::$toc_data;

	}


	/**
	 * 目次の生成
	 */
	public static function set_toc( $content ) {
		$toc_data = self::$toc_data;
		if ( ! $toc_data ) {
			$toc_data = self::create_toc_data( $content );
			echo '<pre style="margin-left: 100px;">';
			var_dump( $toc_data );
			echo '</pre>';
		}

		foreach ( $toc_data as $data ) {
			// $content = str_replace( $content );
		}

	}
}
