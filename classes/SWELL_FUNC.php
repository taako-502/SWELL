<?php
use \SWELL_THEME\Parts\Post_List;
use \SWELL_Theme\Style;
use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

// @codingStandardsIgnoreStart


/**
 * SWELL_FUNC は 名前空間なしでどのファイルからでも簡単に呼び出せるように。
 */
class SWELL_FUNC {

	/**
	 * 外部からのインタンス呼び出し無効
	 */
	private function __construct() {}

	/**
	 * get_setting
	 */
	public static function get_setting( $key = null ) {
		return SWELL::get_setting( $key );
	}


	/**
	 * get_option
	 */
	public static function get_option( $key = null ) {
		return SWELL::get_option( $key );
	}


	/**
	 * get_editor
	 */
	public static function get_editor( $key = null ) {
		return SWELL::get_editor( $key );
	}


	/**
	 * HTMLタグに付与する属性値
	 */
	public static function root_attrs() {

		$attrs = 'data-loaded="false"'; // DOM読み込み御用
		$attrs .= ' data-scrolled="false"'; // スクロール制御用
		$attrs .= ' data-spmenu="closed"'; // SPメニュー制御用
		$attrs = apply_filters( 'swell_root_attrs', $attrs );
		echo $attrs;
	}

	/**
	 * #body_wrap に付与する属性値
	 */
	public static function body_attrs() {

		$attrs = 'data-barba="wrapper"';

		$attrs = apply_filters( 'swell_body_attrs', $attrs );
		echo $attrs;
	}

	/**
	 * #content に付与する属性値
	 */
	public static function content_attrs() {

		$attrs = '';

		if ( is_single() || is_page() || ( !is_front_page() && is_home() ) ) {
			$attrs .= ' data-postid="'. get_queried_object_id() .'"';
		}

		$attrs = apply_filters( 'swell_content_attrs', $attrs );

		echo trim( $attrs );
	}


	/**
	 * #lp-content に付与する属性値
	 */
	public static function lp_content_attrs() {

		$attrs = 'data-postid="'. get_queried_object_id() .'"';
		$attrs = apply_filters( 'swell_lp_content_attrs', $attrs );

		echo trim( $attrs );
	}


	/**
	 * フレーム設定を取得する
	 */
	public static function get_frame_class() {
		$content_frame = SWELL::get_setting('content_frame');
		$frame_scope   = SWELL::get_setting('frame_scope');

		$frame_class = '';
		if ( 'frame_off' === $content_frame ) {
			$frame_class = '-frame-off';
		} else {
			$is_page = is_page() && !is_front_page();

			if ( 'page' === $frame_scope && ! $is_page ) {
				$frame_class = '-frame-off';
			} elseif ( 'post' === $frame_scope && ! is_single() ) {
				$frame_class = '-frame-off';
			} elseif ( 'post_page' === $frame_scope && ! is_single() && ! $is_page ) {
				$frame_class = '-frame-off';
			} else {
				// フレーム オン
				$frame_class  = '-frame-on';
				$frame_class .=  ( $content_frame === 'frame_on_main' ) ? ' -frame-off-sidebar' : ' -frame-on-sidebar';

				// さらに「線で囲む」がオンの場合
				if ( SWELL::get_setting( 'on_frame_border' ) ) {
					$frame_class .= ' -frame-border';
				}
			}
		}

		return apply_filters( 'swell_frame_class', $frame_class );
	}


	/**
	 * ヘッダーのクラス
	 */
	public static function get_header_class() {
		$SETTING = SWELL_FUNC::get_setting();
		$header_layout = str_replace( '_', '-', $SETTING['header_layout'] );
		switch ( $header_layout ) {
			case 'parallel-top':
			case 'parallel-bottom':
				$header_class = '-parallel -'. $header_layout;
				break;
			case 'sidefix':
				$header_class = '-sidefix';
				break;
			default:
				$header_class = '-series -'. $header_layout;
				break;
		}

		// ヒーローヘッダーの時だけトップページに付与するクラス
		$header_transparent = str_replace( '_', '-', $SETTING['header_transparent'] ); //no | t-fff | t-000
		if ( SWELL::is_top() && $header_transparent !== 'no') {
			$header_class .= ' -transparent -'. $header_transparent;
		}

		return $header_class;
	}


	/**
	 * アイキャッチ画像を表示するかどうか
	 */
	public static function is_show_thumb( $post_id = null ) {
		$post_id = $post_id ?: get_the_ID();

		if ( ! $post_id ) return false;

		$setting_key = ( is_single() ) ? 'show_post_thumb' : 'show_page_thumb';

		$is_show_thumb = get_post_meta( $post_id, 'swell_meta_show_thumb', true );
		if ( $is_show_thumb === 'show' ) {

			$is_show_thumb = true;

		} elseif( $is_show_thumb === 'hide') {

			$is_show_thumb = false;

		} else {

			$is_show_thumb = SWELL::get_setting( $setting_key );

		}

		//アイキャッチを表示しない場合の追加条件
		if ( (int) get_query_var('page') !== 0 ) {
			$is_show_thumb = false;
		}

		return $is_show_thumb;
	}


	/**
	 * ページタイトルをコンテンツ上部に表示するかどうか
	 */
	public static function is_show_ttltop() {

		if ( SWELL::is_top() ) return false;

		if ( is_single() ) {
			$title_pos = SWELL::get_setting( 'post_title_pos' );
		} elseif ( is_page() || is_home() ) {
			$title_pos = SWELL::get_setting( 'page_title_pos' );
		} elseif ( SWELL::is_term() ) {
			$title_pos = SWELL::get_setting( 'term_title_pos' );
		} else {
			$title_pos = '';
		}

		$is_show_ttltop = ( $title_pos === 'top' ) ? true : false;
		return apply_filters( 'swell_is_show_ttltop', $is_show_ttltop );

	}


	/**
	 * 目次機能を使うかどうか
	 */
	public static function is_show_index() {

		$is_show_index = false;

		if ( ! is_singular( 'lp' ) && is_single() ) {
			$is_show_index = SWELL::get_setting( 'show_index' );
		} elseif( ! is_front_page() && is_page() ) {
			$is_show_index = SWELL::get_setting( 'show_index_page' );
		}

		return apply_filters('swell_is_show_index', $is_show_index );
	}


	/**
	 * 目次広告を表示するかどうか
	 */
	public static function is_show_toc_ad( $in_shortcode = false ) {
		$is_show_toc_ad = false;
		$the_id         = get_queried_object_id();

		if ( ! is_singular( 'lp' ) && is_single() ) {
			$is_show_toc_ad = $is_show_toc_ad || SWELL::get_setting( 'show_toc_ad_alone_post' ) || self::is_show_index();
		} elseif( ! is_front_page() && is_page() ) {
			$is_show_toc_ad = $is_show_toc_ad || SWELL::get_setting( 'show_toc_ad_alone_page' ) || self::is_show_index();
		}

		return apply_filters('swell_is_show_toc_ad', $is_show_toc_ad, $in_shortcode );
	}


	/**
	 * 各ページでサイドバーを使用するかどうか
	 */
	public static function is_show_sidebar() {

		if ( SWELL::is_top() ) {

			$is_show_sidebar = SWELL::get_setting('show_sidebar_top');

		} elseif ( is_singular( 'lp' ) ) {

			$is_show_sidebar = false;

		} elseif ( is_page() || is_home() ) {

			$is_show_sidebar = SWELL::get_setting('show_sidebar_page');

		} elseif ( is_single() ) {

			$is_show_sidebar = SWELL::get_setting('show_sidebar_post');

		} elseif ( is_archive() ) {

			$is_show_sidebar = SWELL::get_setting('show_sidebar_archive');

		} elseif( is_search() ) {

			$is_show_sidebar = SWELL::get_setting('show_sidebar_archive');

		} else {

			$is_show_sidebar = false;

		}

		return apply_filters( 'swell_is_show_sidebar', $is_show_sidebar );

	}


	/**
	 * コメントを使用するかどうか
	 */
	public static function is_show_comments( $post_id = 0 ) {

		$post_id = $post_id ?: get_queried_object_id();

		$is_show_comments = false;

		if ( is_single() ) {

			$show_comments = SWELL::get_setting('show_comments');
			$comments_meta = get_post_meta( $post_id, 'swell_meta_show_comments', true );
			$comments_open = comments_open( $post_id ) && ! post_password_required( $post_id );

			$is_show_comments = ( $comments_meta !== 'hide' && ( $comments_meta === 'show' || $show_comments ) );
			$is_show_comments = $is_show_comments && $comments_open;

		} elseif ( is_page() ) {
			$is_show_comments = comments_open( $post_id ) && ! post_password_required( $post_id );
		}

		return apply_filters( 'swell_is_show_comments', $is_show_comments );
	}


	/**
	 * ピックアップバナーを使用するかどうか
	 */
	public static function is_show_pickup_banner() {

		if ( is_paged() ) return false;

		$is_show_banners = false;

		if ( SWELL::is_top() ) {
			$is_show_banners = true;
		} else {
			$is_show_banners = SWELL::get_setting('pickbnr_show_under');
		}

		return apply_filters( 'swell_is_show_pickup_banner', $is_show_banners );
	}


	/**
	 * アイキャッチ画像を取得
	 */
	public static function get_thumbnail( $the_id, $args, $is_term = false ) {
		if ( $is_term ) {
			$args['term_id'] = $the_id;
			return SWELL::get_thumbnail( $args );
		}
		$args['post_id'] = $the_id;
		return SWELL::get_thumbnail( $args );
		
	}


	/**
	 * アーカイブページのデータを取得
	 * ['type'] : cayegory | tag | tax | etc...
	 * ['title']
	 */
	public static function get_archive_data() {
		
		if ( ! is_archive() ) return false;

		$data = [
			'type' => '',
			'title' => 'title'
		];

		if ( is_date() ) {
			//日付アーカイブなら

			$qv_day      = get_query_var('day');
			$qv_monthnum = get_query_var('monthnum');
			$qv_year     = get_query_var('year');

			if ( $qv_day !== 0 ) {
				$ymd_name = $qv_year.'年'.$qv_monthnum.'月'.$qv_day.'日';
			} elseif ($qv_monthnum != 0) {
				$ymd_name = $qv_year.'年'.$qv_monthnum.'月';
			} else {
				$ymd_name = $qv_year.'年';
			}
			if ( is_post_type_archive() ) {
				//さらに、投稿タイプの日付アーカイブだった場合
				$data['title'] = $ymd_name."(".post_type_archive_title('',false).")";
			}
			$data['title'] = $ymd_name;
			$data['type']  = 'date';

		} elseif ( is_post_type_archive() ) {
			//投稿タイプのアーカイブページなら

			$data['title'] = post_type_archive_title('',false);
			$data['type']  = 'pt_archive';

		} elseif ( is_author() ) {
			//投稿者アーカイブ

			$data['title'] = get_queried_object()->display_name;
			$data['type']  = 'author';

		} elseif ( is_category() ) {

			$data['title'] = single_term_title( '', false );
			$data['type']  = 'category';

		} elseif ( is_tag() ) {

			$data['title'] = single_term_title( '', false );
			$data['type']  = 'tag';

		} elseif ( is_tax() ) {

			$data['title'] = single_term_title( '', false );
			$data['type']  = 'tax';

		} else {

			$data['title'] = single_term_title( '', false );
			$data['type']  = '';

		}
		return $data;
	}


	/**
	 * 記事のビュー数を更新させる
	 */
	public static function set_post_views( $post_id = null ) {

		if ( ! $post_id ) return;

		$count = (int) get_post_meta( $post_id, SWELL_CT_KEY, true );
		$count = $count + 1;
		update_post_meta( $post_id, SWELL_CT_KEY, $count );
	}


	/**
	 * 記事のビュー数を取得
	 */
	public static function get_post_views( $post_id = null ) {

		$post_id = $post_id ?: get_the_ID();

		$count = (int) get_post_meta( $post_id, SWELL_CT_KEY, true );

		return $count;
	}


	/**
	 * ヘッダーメニューのデフォルト呼び出し関数
	 *  - ヘッダーメニューが設定されていなければ、常に固定ページ一覧を呼び出す
	 */
	public static function default_head_menu() {

		$current_url = get_permalink();
		$args = [
			'post_type' => 'page',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'posts_per_page' => 5
		];
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) :
			//echo '<ul id="header_menu" class="header_menu">';
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$link = get_permalink();
				// $li_class = ( $current_url === $link ) ? 'current_page_item' : '';
			?>
				<li class="menu-item">
					<a href="<?=get_permalink()?>">
						<span><?=get_the_title()?></span>
					</a>
				</li>
			<?php 
			endwhile;
			//echo '</ul>';
		endif;
		wp_reset_postdata();

	}


	/**
	 * ファイル読み込み
	 */
	public static function get_file_contents( $file ) {

		if ( file_exists( $file ) ) {
			$file_content = file_get_contents( $file );
			return $file_content;
		}
		return false;

		// $creds = request_filesystem_credentials('', '', false, false, null);
		// if ( file_exists( $file ) && WP_Filesystem( $creds ) ) {
		// 	global $wp_filesystem;
		// 	$file_content = $wp_filesystem->get_contents( $file );
		// 	return $file_content;
		// }
	}


	/**
	 * テンプレート読み込み
	 * $path : 読み込むファイルのパス
	 * $variable : 引数として利用できるようにする変数
	 * $cache_key : Transientキー
	 * $expiration : キャッシュ有効期限(default : 30日)
	 */
	public static function get_parts( $path = '', $variable = null, $cache_key = '', $expiration = null ) {
		// if ( $path === '' ) return 'not found '.$path;

		//まず子テーマ側から探す
		$include_path = S_DIRE.'/'.$path.'.php';
		// var_dump( $include_path);
		if ( ! file_exists( $include_path ) ) {

			//小テーマにファイルがなければ 親テーマから探す
			$include_path = T_DIRE.'/'.$path.'.php';
			if ( ! file_exists( $include_path ) ) {

				//親テーマにもファイルが無ければ
				echo 'Include Error! : "'. $path .'"';
				return;

			}
		}

		if ( $cache_key !== '' && is_customize_preview() ) {
			// キャッシュキーありだけどカスタマイザーで表示中はキャッシュ削除
			delete_transient( 'swell_'. $cache_key ); // ~ 2.0.2を考慮
			delete_transient( 'swell_parts_'. $cache_key );

		} elseif ( $cache_key !== '' ) {
			// キャッシュキーの指定があれば、キャッシュを読み込む
			// echo 'cache : ' . $cache_key;

			$data = get_transient( 'swell_parts_'. $cache_key ); //キャッシュの取得
			if ( empty( $data ) ) {

				ob_start();
				include $include_path;
				$data = ob_get_clean();
				$data = SWELL::minify_html_code( $data );

				// キャッシュ保存期間
				$expiration = $expiration ?: 30 * DAY_IN_SECONDS;

				//キャッシュデータの生成
				set_transient( 'swell_parts_'. $cache_key, $data, $expiration );
				//  echo $cache_key .' : 新キャッシュ';
			}

			// キャッシュデータを出力して return
			echo $data;
			return;

		}

		// 普通に読み込み
		include $include_path;
		return;
	}


	/**
	 * 内部リンクのブログカード化
	 */
	public static function get_internal_blog_card( $post_id, $card_args = [], $is_text = false ) {
		
		//$caption = '', $is_blank = false, $rel = '', $noimg = false;

		$card_data = '';
		// キャッシュがあるか調べる
		if ( SWELL::is_use( 'card_cache__in' ) ) {
			$cache_key = 'swell_card_id'. $post_id;
			$card_data = get_transient( $cache_key );
		}
		
		// キャッシュがなければ
		if ( !$card_data ) {

			$post_data = get_post( $post_id );
			$title     = get_the_title( $post_id );
			$url       = get_permalink( $post_id );
			$excerpt   = SWELL::get_excerpt( $post_data, 80 );
			
			if ( mb_strwidth( $title, 'UTF-8' ) > 100 ) {
				$title = mb_strimwidth($title, 0, 100, '...', 'UTF-8');
			}
			if ( has_post_thumbnail( $post_id ) ) {
				// アイキャッチ画像のIDを取得
				$thumb_id   = get_post_thumbnail_id( $post_id );
				$thumb_data = wp_get_attachment_image_src( $thumb_id, 'medium' );
				$thumb  = $thumb_data[0];
			} else {
				$thumb = SWELL::get_noimg( 'small' );
			}

			$card_data = [
				'url' => $url,
				'title' => $title,
				'thumb' => $thumb,
				'excerpt' => $excerpt,
			];

			if ( SWELL::is_use( 'card_cache__in' ) ) {
				$day = \SWELL_FUNC::get_setting('cache_card_time') ?: 30;
				set_transient( $cache_key, $card_data, DAY_IN_SECONDS * intval( $day ) );
			}
		}


		$card_data              = array_merge( $card_data, $card_args );
		$card_data['add_class'] = '-internal';
		$card_data['type']      = \SWELL_FUNC::get_editor('blog_card_type') ?: 'type1';

		if ( $is_text ) {
			return SWELL::get_pluggable_parts( 'blog_link', $card_data );
		}
		return SWELL::get_pluggable_parts( 'blog_card', $card_data );
	}


	/**
	 * 外部サイトのブログカード
	 */
	public static function get_external_blog_card( $url, $card_args = [], $is_text = false ) {

		$card_data = '';

		// キャッシュがあるか調べる
		if ( SWELL::is_use( 'card_cache__ex' ) ) {
			$url_hash = md5( $url );
			$cache_key = 'swell_card_'. $url_hash;
			$card_data = get_transient( $cache_key );

			if ( !isset( $card_data['site_name'] ) ) {
				// キャプション不具合修正時のコード変更に対応
				delete_transient( $cache_key );
				$card_data = '';
			}
		}
		
		if ( !$card_data ) {

			// Get_OGP_InWP の読み込み
			require_once T_DIRE . '/classes/plugins/get_ogp_inwp.php';

			$ogps = \Get_OGP_InWP::get( $url );
			if ( empty( $ogps ) ) return $url;

			// 必要なデータを抽出
			$card_data = \Get_OGP_InWP::extract_card_data( $ogps );

			$title       = $card_data['title'];
			$description = $card_data['description'];
			$site_name   = $card_data['site_name'];
			$thumb_url   = $card_data['thumbnail'];
			// $icon        = $card_data['icon'];

			/**
			 * はてなブログの文字化け対策
			 */
			$title_decoded = utf8_decode( $title );  //utf8でのデコード
			if ( mb_detect_encoding( $title_decoded ) === 'UTF-8' ) {
				$title = $title_decoded; // 文字化け解消

				$description_decoded = utf8_decode( $description );
				if ( mb_detect_encoding( $description_decoded ) === 'UTF-8' ) {
					$description = $description_decoded;
				}

				$site_name_decoded = utf8_decode( $site_name );
				if ( mb_detect_encoding( $site_name_decoded ) === 'UTF-8' ) {
					$site_name = $site_name_decoded;
				}
			}

			// 文字数で切り取り
			if ( mb_strwidth( $title, 'UTF-8' ) > 100 ) {
				$title = mb_strimwidth($title, 0, 100 ).'...';
			}
			if ( mb_strwidth( $description,'UTF-8' ) > 160 ) {
				$description = mb_strimwidth($description, 0, 160 ).'...';
			}
			if ( mb_strwidth( $site_name,'UTF-8' ) > 32 ) {
				$site_name = mb_strimwidth( $site_name, 0, 32 )."...";
			}

			$card_data = [
				'url'       => $url,
				'site_name' => $site_name,
				'title'     => $title,
				'thumb'     => $thumb_url,
				'excerpt'   => $description,
			];

			if ( SWELL::is_use( 'card_cache__ex' ) ) {
				$day = \SWELL_FUNC::get_setting('cache_card_time') ?: 30;
				set_transient( $cache_key, $card_data, DAY_IN_SECONDS * intval( $day ) );
			}
		}

		$card_data              = array_merge( $card_data, $card_args );
		$card_data['add_class'] = '-external';
		$card_data['is_blank']  = true;
		$card_data['type']      = \SWELL_FUNC::get_editor( 'blog_card_type_ex' ) ?: 'type3';

		if ( $is_text ) {
			return SWELL::get_pluggable_parts( 'blog_link', $card_data );
		}
		return SWELL::get_pluggable_parts( 'blog_card', $card_data );
	}


	/**
	 * キャッシュクリア
	 */
	public static function clear_cache( $cache_keys = [], $prefix = 'swell_parts' ) {
		
		if ( $cache_keys === [] ) {
			// キーの指定がなければ全キャッシュキーを取得
			foreach ( SWELL::$cache_keys as $keys ) {
				$cache_keys = array_merge( $cache_keys, $keys );
			}
		}

		foreach ( $cache_keys as $key ) {
			delete_transient( $prefix . '_' . $key );
		}
	}


	/**
	 * ブログカードのキャッシュクリア
	 */
	public static function clear_card_cache() {
		global $wpdb;
		$option_table = $wpdb->prefix. 'options';
		$wpdb->query(
			"DELETE FROM $option_table WHERE (`option_name` LIKE '%_transient_swell_card_%') OR (`option_name` LIKE '%_transient_timeout_swell_card_%')"
		);
	}


	/**
	 * IEでCSS変数を置換する
	 */
	public static function replace_css_var_on_IE( $style = null ) {
		$SETTING = SWELL::get_setting();
		$EDITOR = SWELL::get_editor();

		$color_main = $SETTING['color_main'];
		$color_content_bg = ( $SETTING['content_frame'] === 'frame_off' ) ? $SETTING['color_bg'] : '#fff';
		$ps_space = ( $SETTING['ps_no_space'] ) ? '0' : '8px';

		$btn_red = $EDITOR['color_btn_red'];
		$btn_blue = $EDITOR['color_btn_blue'];
		$btn_green = $EDITOR['color_btn_green'];
		
		$css_variables = [
			'var(--color_htag)' => $SETTING['color_htag'] ?: $color_main,
			'var(--color_gnav_bg)' => $SETTING['color_gnav_bg'] ?: $SETTING['color_main'],
			'var(--color_gray)' => 'rgba(200,200,200,.15)',
			'var(--color_border)' => 'rgba(200,200,200,.5)',
			'var(--color_btn_blue_dark)' => SWELL::get_rgba($btn_blue, 1, -.25),
			'var(--color_btn_red_dark)' => SWELL::get_rgba($btn_red, 1, -.25),
			'var(--color_btn_green_dark)' => SWELL::get_rgba($btn_green, 1, -.25),
			'var(--color_main_thin)' => SWELL::get_rgba($color_main, 0.05, 0.25),
			'var(--color_main_dark)' => SWELL::get_rgba($color_main, 1, -.25),
			'var(--container_size)' => (int) $SETTING['container_size'] + 96 .'px',
			'var(--article_size)' => (int) $SETTING['article_size'] + 64 .'px',
			'var(--logo_size_sp)' => $SETTING['logo_size_sp'] .'px' ,
			'var(--logo_size_pc)' => $SETTING['logo_size_pc'] .'px' ,
			'var(--logo_size_pcfix)' => $SETTING['logo_size_pcfix'] .'px' ,
			'var(--card_posts_thumb_ratio)' => SWELL::$thumb_ratios[ $SETTING['card_posts_thumb_ratio'] ]['value'],
			'var(--list_posts_thumb_ratio)' => SWELL::$thumb_ratios[ $SETTING['list_posts_thumb_ratio'] ]['value'],
			'var(--big_posts_thumb_ratio)' => SWELL::$thumb_ratios[ $SETTING['big_posts_thumb_ratio'] ]['value'],
			'var(--thumb_posts_thumb_ratio)' => SWELL::$thumb_ratios[ $SETTING['thumb_posts_thumb_ratio'] ]['value'],
			'var(--color_content_bg)' => $color_content_bg,
			'var(--mv_btn_radius)' => $SETTING['mv_btn_radius'] .'px',
			'var(--ps_space)' => $ps_space,
			'var(--color_list_check)' => $EDITOR['color_list_check'] ?: $color_main,
			'var(--color_list_num)' => $EDITOR['color_list_num'] ?: $color_main,
		];
		foreach ( $css_variables as $key => $value ) {
			$style = str_replace( $key, $value, $style );
		}

		// 残りはそのまま$SETTINGの値を受け渡す
		$style = preg_replace_callback('/var\(--([^\)]*)\)/', function( $m ) {
			$key = $m[1];
			$return = SWELL_FUNC::get_setting($key) ?: SWELL_FUNC::get_editor($key) ?: $m[0];
			return $return;
		}, $style);

		return $style;
	
	}


	/**
	 * メインビジュアルのスタイルを生成する
	 */
	public static function get_mv_text_style( $text_color, $shadow_color ) {
		$return = 'color:'. $text_color .';';
		if ( $shadow_color !== '' ) {
			$return .= 'text-shadow:1px 1px 0px '. SWELL::get_rgba( $shadow_color, .2 );
		}
		return $return;
	}


	/**
	 * 著者情報を取得
	 *  return : name / position / description / sns_list
	 */
	public static function get_author_data( $author_id ) {

		if ( ! $author_id ) return [];

		$return_data = [];
		$author_data = get_userdata( $author_id );
		
		$return_data['name'] = $author_data->display_name;
		$return_data['description'] = $author_data->description;
		$return_data['position']  = get_the_author_meta( 'position', $author_id );

		$sns_list  = [];
		$sns_list['home']      = $author_data->user_url ?: '';
		$sns_list['home2']     = get_the_author_meta( 'site2', $author_id) ?: '';
		$sns_list['facebook']  = get_the_author_meta( 'facebook_url', $author_id) ?: '';
		$sns_list['twitter']   = get_the_author_meta( 'twitter_url', $author_id) ?: '';
		$sns_list['instagram'] = get_the_author_meta( 'instagram_url', $author_id) ?: '';
		$sns_list['room']      = get_the_author_meta( 'room_url', $author_id) ?: '';
		$sns_list['pinterest'] = get_the_author_meta( 'pinterest_url', $author_id) ?: '';
		$sns_list['github']    = get_the_author_meta( 'github_url', $author_id) ?: '';
		$sns_list['youtube']   = get_the_author_meta( 'youtube_url', $author_id) ?: '';
		$sns_list['amazon']    = get_the_author_meta( 'amazon_url', $author_id) ?: '';
		
		// 空の要素を排除
		$return_data['sns_list'] = array_filter( $sns_list );

		return $return_data;
	}

	/**
	 * カスタマイザーのSNS設定情報を取得
	 * @return $key => $url の配列データ
	 */
	public static function get_sns_settings(){
		$SETTING = SWELL::get_setting();
		$sns_data = [];
		$sns_settings = [
			'facebook',
			'twitter',
			'instagram',
			'room',
			'line',
			'pinterest',
			'github',
			'youtube',
			'amazon',
			'feedly',
			'rss',
			'contact',
		];
		foreach ( $sns_settings as $key ) {
			if ( $url = $SETTING[ $key.'_url' ] ) {
			   $sns_data[$key] = $url;
			}
		}
		return $sns_data;
	}


	/**
	 * リンク先URLからtarget属性を判定
	 */
	public static function get_link_target( $url ) {
		// スムースリンクさせたいリンクは _blank にしない
		if ( strpos( $url, '#' ) !== 0 && strpos( $url, SWELL::site_data( 'home' ) ) === false ) {
			return ' rel="noopener" target="_blank"';
		}
		return '';
	}


	/**
	 * ファイルURLからサイズを取得
	 */
	public static function get_file_size( $file_url ) {
		
		// ファイル名にサイズがあればそれを返す
		preg_match('/-([0-9]*)x([0-9]*)\./', $file_url, $matches );
		if ( ! empty( $matches ) ) {
			 return [
				'width' => $matches[1],
				'height' => $matches[2],
			];
		}
		
		// フルサイズの時
		$file_id = attachment_url_to_postid( $file_url );
		$file_data = wp_get_attachment_metadata( $file_id );
		if ( ! empty( $file_data ) ) {
			return [
				'width' => $file_data['width'],
				'height' => $file_data['height'],
			];
		}
		
		// サイズが取得できなかった場合
		return false;
	}


	/**
	 * AJAXのNonceチェック
	 */
	public static function check_ajax_nonce( $request_key = 'nonce' , $nonce_key = 'swell-ajax-nonce' ) {
		if ( !isset( $_POST[$request_key] ) ) return false;

		$nonce = $_POST[$request_key];

		if ( wp_verify_nonce( $nonce, $nonce_key ) ) {
			return true;
		}

		return false;
	}


	/**
	 * カテゴリーを出力
	 */
	public static function get_the_term_links( $post_id = '', $tax = '' ) {

		if ( $tax === 'cat' ) {
			$terms = get_the_category( $post_id );
			$link_class = 'c-categoryList__link hov-flash-up';
		} elseif ( $tax === 'tag' ) {
			$terms = get_the_tags( $post_id );
			$link_class = 'c-tagList__link hov-flash-up';
		} else {
			$terms = null;
			$link_class = '';
		}

		if ( empty( $terms ) ) return '';

		$thelist = '';
		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term );
			$data_id = 'data-'. $tax .'-id="'. $term->term_id .'"';
			$thelist .= '<a class="'. $link_class .'" href="' . esc_url( $term_link ) . '" '. $data_id .'>'. $term->name . '</a>';
		}

		return apply_filters( 'swell_get_the_term_links', $thelist, $post_id, $tax );
	}


	/**
	 * ブログパーツのコンテンツを取得
	 */
	public static function get_blog_parts_content( $args ) {
		$q_args = [
			'post_type'      => 'blog_parts',
			'no_found_rows'  => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'posts_per_page' => 1,
		];

		$parts_id    = isset( $args['id'] ) ? (int) $args['id'] : 0;
		$parts_title = isset( $args['title'] ) ? $args['title'] : '';

		if ( $parts_id ) {
			$q_args['p'] = $parts_id;
		} elseif( $parts_title ) {
			$q_args['title'] = $parts_title;
		} else {
			return '';
		}

		// ブロックでセットしたクラスを受け取れるように
		// $blog_parts_class = 'post_content';
		// if ( isset( $args['class'] ) && $args['class'] ) $blog_parts_class .= ' ' . $args['class'];
		// echo '<div class="p-blogParts ' . esc_attr( $blog_parts_class ) . '">';

		$the_query = new WP_Query( $q_args );
		$content = '';

		if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) :
				$the_query->the_post();
				$content = get_the_content(); // 'the_content'フックを通させない

				// 無限ループ回避
				if ( false !== strpos( $content, 'blog_parts id="' . $parts_id ) || false !== strpos( $content, '"partsID":"' . $parts_id ) ) {
					$content = 'ブログパーツ内で自信を呼び出すことはできません。';
				}

				// echo do_blocks( do_shortcode( $content ) ); // ショートコードの展開 & ブロックのパース
		endwhile;
		endif;
		wp_reset_postdata();

		return $content;
	}

}
