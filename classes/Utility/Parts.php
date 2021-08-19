<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * テンプレートパーツ取得メソッド
 */
trait Parts {

	/**
	 * 関数で呼び出すパーツ
	 */
	public static function pluggable_parts( $name, $args = [] ) {
		$func = 'swl_parts__' . $name;
		if ( function_exists( $func ) ) {
			$func( $args );
		}
	}

	/**
	 * pluggable_parts 取得版
	 */
	public static function get_pluggable_parts( $name, $args = [] ) {
		ob_start();
		self::pluggable_parts( $name, $args );
		return ob_get_clean();
	}


	/**
	 * テンプレート読み込み
	 * $path       : 読み込むファイルのパス
	 * $variable   : 引数として利用できるようにする変数
	 * $cache_key  : Transientキー
	 * $expiration : キャッシュ有効期限(default : 30日)
	 */
	public static function get_parts( $path, $variable = null, $cache_key = '', $expiration = null ) {
		// if ( $path === '' ) return 'not found '.$path;

		// まず子テーマ側から探す
		$include_path = S_DIRE . '/' . $path . '.php';
		// var_dump( $include_path);
		if ( ! file_exists( $include_path ) ) {

			// 小テーマにファイルがなければ 親テーマから探す
			$include_path = T_DIRE . '/' . $path . '.php';
			if ( ! file_exists( $include_path ) ) {

				// 親テーマにもファイルが無ければ
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					echo 'Include Error! : "' . esc_html( $path ) . '"';
				}
				return;

			}
		}

		if ( $cache_key !== '' && is_customize_preview() ) {
			// キャッシュキーありだけどカスタマイザーで表示中はキャッシュ削除
			delete_transient( 'swell_' . $cache_key ); // ~ 2.0.2を考慮
			delete_transient( 'swell_parts_' . $cache_key );

		} elseif ( $cache_key !== '' ) {
			// キャッシュキーの指定があれば、キャッシュを読み込む

			$data = get_transient( 'swell_parts_' . $cache_key ); // キャッシュの取得
			if ( empty( $data ) ) {

				ob_start();
				include $include_path;
				$data = ob_get_clean();
				$data = self::minify_html_code( $data );

				// キャッシュ保存期間
				$expiration = $expiration ?: 30 * DAY_IN_SECONDS;

				// キャッシュデータの生成
				set_transient( 'swell_parts_' . $cache_key, $data, $expiration );
			}

			// キャッシュデータを出力して return
			echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;

		}

		// 普通に読み込み
		include $include_path;
	}

}
