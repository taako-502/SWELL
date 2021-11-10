<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Others {


	/**
	 * wp_nonce_fieldを設置する
	 */
	public static function set_nonce_field( $key = '' ) {
		wp_nonce_field( self::$nonce['action'] . $key, self::$nonce['name'] . $key );
	}


	/**
	 * NONCE チェック
	 */
	public static function check_nonce( $key = '' ) {

		$nonce_name   = self::$nonce['name'] . $key;
		$nonce_action = self::$nonce['action'] . $key;

		if ( ! isset( $_POST[ $nonce_name ] ) ) {
			return false;
		}

		return wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action );
	}


	/**
	 * 編集権限のチェック
	 */
	public static function check_user_can_edit( $post_id ) {

		// 現在のユーザーに投稿の編集権限があるかのチェック （投稿 : 'edit_post' / 固定ページ & LP : 'edit_page')
		$check_can = ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) ? 'edit_post' : 'edit_page';
		if ( ! current_user_can( $check_can, $post_id ) ) {
			return false;
		}

		return true;
	}


	/**
	 * カラーコードをrgbaに変換
	 * $brightness : -1 ~ 1
	 */
	public static function get_rgba( $color_code, $alpha = 1, $brightness = 0 ) {

		$color_code = str_replace( '#', '', $color_code );

		$rgba_code          = [];
		$rgba_code['red']   = hexdec( substr( $color_code, 0, 2 ) );
		$rgba_code['green'] = hexdec( substr( $color_code, 2, 2 ) );
		$rgba_code['blue']  = hexdec( substr( $color_code, 4, 2 ) );

		if ( 0 !== $brightness ) {
			foreach ( $rgba_code as $key => $val ) {
				$val               = (int) $val;
				$result            = $val + ( $val * $brightness );
				$rgba_code[ $key ] = max( 0, min( 255, round( $result ) ) );
			}
		}

		$rgba_code['alpha'] = $alpha;

		return 'rgba(' . implode( ', ', $rgba_code ) . ' )';
	}


	/**
	 * HTMLソースのminify化
	 */
	public static function minify_html_code( $src ) {
		$search  = [
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',       // shorten multiple whitespace sequences
			'/<!--[\s\S]*?-->/s', // コメントを削除
		];
		$replace = [
			'>',
			'<',
			'\\1',
			'',
		];
		return preg_replace( $search, $replace, $src );
	}


	/**
	 * ファイル読み込み
	 */
	public static function get_file_contents( $file ) {
		if ( file_exists( $file ) ) {
			$file_content = file_get_contents( $file );
			return $file_content;
		}
		return '';
	}


	/**
	 * 画像にlazyloadを適用
	 */
	public static function set_lazyload( $image, $lazy_type, $placeholder = '' ) {

		if ( $lazy_type === 'eager' ) {

			$image = str_replace( ' src="', ' loading="eager" src="', $image );

		} elseif ( $lazy_type === 'lazy' || self::is_rest() || self::is_iframe() ) {

			$image = str_replace( ' src="', ' loading="lazy" src="', $image );

		} elseif ( $lazy_type === 'lazysizes' ) {

			$noscript = '<noscript>' . $image . '</noscript>';

			$placeholder = $placeholder ?: self::$placeholder;
			$image       = str_replace( ' src="', ' src="' . esc_url( $placeholder, ['http', 'https', 'data' ] ) . '" data-src="', $image );
			$image       = str_replace( ' srcset="', ' data-srcset="', $image );
			$image       = str_replace( ' class="', ' class="lazyload ', $image );

			$image = preg_replace_callback( '/<img([^>]*)>/', function( $matches ) {
				$props = rtrim( $matches[1], '/' );
				$props = self::set_aspectratio( $props );
				return '<img' . $props . '>';
			}, $image );

			$image .= $noscript;
		}

		return $image;
	}


	/**
	 * width,height から aspectratio を指定
	 */
	public static function set_aspectratio( $props, $src = '' ) {

		// width , height指定を取得
		preg_match( '/\swidth="([0-9]*)"/', $props, $width_matches );
		preg_match( '/\sheight="([0-9]*)"/', $props, $height_matches );
		$width  = ( $width_matches ) ? $width_matches[1] : '';
		$height = ( $height_matches ) ? $height_matches[1] : '';

		if ( $width && $height ) {
			// widthもheightもある時
			$props .= ' data-aspectratio="' . $width . '/' . $height . '"';
		} elseif ( $width ) {
			// widthしかない時
			$img_size = self::get_file_size( $src );
			if ( $img_size ) {
				$props .= ' data-aspectratio="' . $img_size['width'] . '/' . $img_size['height'] . '"';
			}
		} else {
			// widthすら指定されていない時
			$img_size = self::get_file_size( $src );
			if ( $img_size ) {
				$props .= ' width="' . $img_size['width'] . '" data-aspectratio="' . $img_size['width'] . '/' . $img_size['height'] . '"';
			}
		}
		return $props;
	}


	/**
	 * ヘッダーメニューが空の時のデフォルト呼び出し関数
	 */
	public static function default_head_menu() {
		$args      = [
			'post_type'      => 'page',
			'no_found_rows'  => true,
			'posts_per_page' => 5,
		];
		$the_query = new \WP_Query( $args );
		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
			?>
				<li class="menu-item">
					<a href="<?=esc_url( get_permalink() )?>">
						<span><?php the_title(); ?></span>
					</a>
				</li>
			<?php
			endwhile;
		endif;
		wp_reset_postdata();
	}


	/**
	 * 記事のビュー数を更新させる
	 */
	public static function set_post_views( $post_id ) {
		if ( ! $post_id ) return;

		$count = (int) self::get_post_views( $post_id );
		++$count;
		update_post_meta( $post_id, SWELL_CT_KEY, $count );
	}


	/**
	 * 記事のビュー数を取得
	 */
	public static function get_post_views( $post_id ) {
		return get_post_meta( $post_id, SWELL_CT_KEY, true ) ?: '0';
	}


	/**
	 * キャッシュクリア
	 */
	public static function clear_cache( $cache_keys = [] ) {

		// キーの指定がなければ全キャッシュキーを取得
		if ( $cache_keys === [] ) {
			foreach ( self::$cache_keys as $keys ) {
				$cache_keys = array_merge( $cache_keys, $keys );
			}
		}
		foreach ( $cache_keys as $key ) {
			delete_transient( 'swell_parts_' . $key );
		}
	}


	/**
	 * DBクエリ回してパーツキャッシュをクリア
	 */
	public static function clear_all_parts_cache_by_wpdb() {
		global $wpdb;
		$option_table = $wpdb->prefix . 'options';
		// @codingStandardsIgnoreStart
		$wpdb->query(
			"DELETE FROM $option_table WHERE (`option_name` LIKE '%_transient_swell_parts_%') OR (`option_name` LIKE '%_transient_timeout_swell_parts_%')"
		);
		// @codingStandardsIgnoreEnd
	}


	/**
	 * ブログカードのキャッシュクリア
	 */
	public static function clear_card_cache() {
		global $wpdb;
		$option_table = $wpdb->prefix . 'options';
		// @codingStandardsIgnoreStart
		$wpdb->query(
			"DELETE FROM $option_table WHERE (`option_name` LIKE '%_transient_swell_card_%') OR (`option_name` LIKE '%_transient_timeout_swell_card_%')"
		);
		// @codingStandardsIgnoreEnd
	}


	/**
	 * update path取得
	 */
	public static function is_change_update_dir() {

		// 未認証でパスが取得できていない場合
		if ( ! self::$update_dir_path ) return false;

		$dir_ver = '';

		// キャッシュ
		$cached_ver = get_transient( 'swl_update_dir_ver' );

		if ( false !== $cached_ver ) {
			$dir_ver = $cached_ver;
		} else {
			$response = wp_remote_post(
				'https://users.swell-theme.com/?swlr-api=get_dir_ver',
				[
					'timeout'     => 3,
					'redirection' => 5,
					'sslverify'   => false,
					'headers'     => [ 'Content-Type: application/json' ],
					'body'        => [],
				]
			);
			if ( ! is_wp_error( $response ) ) {
				$dir_ver = json_decode( $response['body'], true );
				set_transient( 'swl_update_dir_ver', $dir_ver, 60 );
			};
		}

		// /v1-hoge → /v2-hoge などに変わってるかどうか
		if ( false === strpos( self::$update_dir_path, "swell-theme/{$dir_ver}" ) ) {
			return true;
		}

		return false;
	}

	/**
	 * ユーザー照合
	 */
	public static function check_swlr_licence( $email = '' ) {

		$email = $email ?: get_option( 'sweller_email' );

		// キャッシュをチェック
		$cached_data = get_transient( 'swlr_user_status' );
		if ( false !== $cached_data ) {
			self::$licence_status  = $cached_data['status'] ?? '';
			self::$update_dir_path = $cached_data['path'] ?? '';

			// waitingの時間から３分経過している場合
			if ( 'waiting' === self::$licence_status && ! get_transient( 'swlr_is_waiting_activate' ) ) {
				self::$licence_status = '';
			}
		} elseif ( $email ) {

			// API接続
			$response = wp_remote_post(
				'https://users.swell-theme.com/?swlr-api=activation',
				[
					'timeout'     => 3,
					'redirection' => 5,
					'sslverify'   => false,
					'headers'     => [ 'Content-Type: application/json' ],
					'body'        => [
						'email'  => $email,
						'domain' => str_replace( [ 'http://', 'https://' ], '', home_url() ),
					],
				]
			);

			if ( ! is_wp_error( $response ) ) {
				$response_data = json_decode( $response['body'], true );

				// echo '<pre style="margin-left: 100px;">';
				// var_dump( $response_data );
				// echo '</pre>';

				self::$licence_status  = $response_data['status'] ?? '';
				self::$update_dir_path = $response_data['path'] ?? '';

				// ワンタイム認証待ちの時
				if ( 'waiting' === self::$licence_status ) {
					// まだ有効期間中は上書きしない
					if ( ! get_transient( 'swlr_is_waiting_activate' ) ) {
						set_transient( 'swlr_is_waiting_activate', 1, 3 * MINUTE_IN_SECONDS );
					}
				}
				set_transient( 'swlr_user_status', $response_data, 30 * DAY_IN_SECONDS );
			}
		} else {
			self::$licence_status  = '';
			self::$update_dir_path = '';
		}

		// self::$update_dir_path セットした上でチェック
		if ( self::is_change_update_dir() ) {
			delete_transient( 'swlr_user_status' );
		}
	}

	/**
	 * ユーザー照合
	 */
	public static function get_update_json_path() {
		$dir_path  = 'ok' === self::$licence_status ? self::$update_dir_path : 'https://loos.co.jp/products/swell/';
		$file_name = apply_filters( 'swell_update_json_name', 'update.json' );
		return $dir_path . $file_name;
	}

}
