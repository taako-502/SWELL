<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Get {

	/**
	 * $site_data の値を取得
	 */
	public static function site_data( $key ) {
		if ( ! isset( self::$site_data[ $key ] ) ) {
			return '';
		}
		return self::$site_data[ $key ];
	}


	/**
	 * $noimg の値を取得
	 */
	public static function get_noimg( $key ) {
		if ( ! isset( self::$noimg[ $key ] ) ) {
			return '';
		}
		return self::$noimg[ $key ];
	}


	/**
	 * フレーム設定を取得する
	 */
	public static function get_frame_class() {
		$content_frame = self::get_setting( 'content_frame' );
		$frame_scope   = self::get_setting( 'frame_scope' );

		$frame_class = '';
		if ( 'frame_off' === $content_frame ) {
			$frame_class = '-frame-off';
		} else {
			$is_page = is_page() && ! is_front_page();

			if ( 'page' === $frame_scope && ! $is_page ) {
				$frame_class = '-frame-off';
			} elseif ( 'post' === $frame_scope && ! is_single() ) {
				$frame_class = '-frame-off';
			} elseif ( 'post_page' === $frame_scope && ! is_single() && ! $is_page ) {
				$frame_class = '-frame-off';
			} else {
				// フレーム オン
				$frame_class  = '-frame-on';
				$frame_class .= ( 'frame_on_main' === $content_frame ) ? ' -frame-off-sidebar' : ' -frame-on-sidebar';

				// さらに「線で囲む」がオンの場合
				if ( self::get_setting( 'on_frame_border' ) ) {
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
		// キャッシュ取得
		$cached_class = wp_cache_get( 'header_class', 'swell' );
		if ( $cached_class ) return $cached_class;

		$header_layout = str_replace( '_', '-', self::get_setting( 'header_layout' ) );
		switch ( $header_layout ) {
			case 'parallel-top':
			case 'parallel-bottom':
				$header_class = '-parallel -' . $header_layout;
				break;
			case 'sidefix':
				$header_class = '-sidefix';
				break;
			default:
				$header_class = '-series -' . $header_layout;
				break;
		}

		// ヒーローヘッダーの時だけトップページに付与するクラス
		$header_transparent = str_replace( '_', '-', self::get_setting( 'header_transparent' ) ); // no | t-fff | t-000
		if ( self::is_top() && $header_transparent !== 'no' ) {
			$header_class .= ' -transparent -' . $header_transparent;
		}

		wp_cache_set( 'header_class', $header_class, 'swell' );
		return $header_class;
	}


	/**
	 * キャプションデータ
	 */
	public static function get_cap_colors_data() {
		return [
			'col1' => [
				'label' => 'カラーセット1',
				'value' => self::get_editor( 'color_cap_01' ),
			],
			'col2' => [
				'label' => 'カラーセット2',
				'value' => self::get_editor( 'color_cap_02' ),
			],
			'col3' => [
				'label' => 'カラーセット3',
				'value' => self::get_editor( 'color_cap_03' ),
			],
		];
	}

	/**
	 * ふきだしデータ
	 */
	public static function get_balloon_data() {
		$return_data = [];
		$args        = [
			'post_type'      => 'speech_balloon',
			'no_found_rows'  => true,
			'posts_per_page' => -1,
		];
		$the_query   = new \WP_Query( $args );

		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$balloon_id         = get_the_ID();
			$balloon_title      = get_the_title();
			$balloon_icon       = get_post_meta( $balloon_id, 'balloon_icon', true );
			$balloon_name       = get_post_meta( $balloon_id, 'balloon_icon_name', true ) ?: get_the_title();
			$balloon_col        = get_post_meta( $balloon_id, 'balloon_col', true );
			$balloon_type       = get_post_meta( $balloon_id, 'balloon_type', true );
			$balloon_align      = get_post_meta( $balloon_id, 'balloon_align', true );
			$balloon_border     = get_post_meta( $balloon_id, 'balloon_border', true );
			$balloon_icon_shape = get_post_meta( $balloon_id, 'balloon_icon_shape', true );

			$return_data[ $balloon_id ] = [
				'title'  => $balloon_title,
				'icon'   => $balloon_icon,
				'name'   => $balloon_name,
				'col'    => $balloon_col,
				'type'   => $balloon_type,
				'align'  => $balloon_align,
				'border' => $balloon_border,
				'shape'  => $balloon_icon_shape,
			];
		endwhile;
		wp_reset_postdata();

		return $return_data;
	}


	/**
	 * ブログパーツデータ
	 */
	public static function get_blog_parts_data() {
		$return_data = [];
		$args        = [
			'post_type'              => 'blog_parts',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'posts_per_page'         => -1,
		];
		$the_query   = new \WP_Query( $args );

		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$parts_id    = get_the_ID();
			$parts_title = get_the_title();
			$use_terms   = get_the_terms( $parts_id, 'parts_use' );

			$term = $use_terms ? $use_terms[0]->slug : '';

			$return_data[ $parts_id ] = [
				'title' => $parts_title,
				'term'  => $term,
			];
		endwhile;
		wp_reset_postdata();

		return $return_data;
	}


	/**
	 * 広告タグのデータ
	 */
	public static function get_ad_tag_data() {
		$return_data = [];
		$args        = [
			'post_type'              => 'ad_tag',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'posts_per_page'         => -1,
			// 'update_post_meta_cache' => false,
		];

		$the_query = new \WP_Query( $args );
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$adTag_id    = get_the_ID();
			$adTag_title = get_the_title();
			$adTag_type  = get_post_meta( $adTag_id, 'ad_type', true );

			$return_data[ $adTag_id ] = [
				'title' => $adTag_title,
				'type'  => $adTag_type,
			];
		endwhile;
		wp_reset_postdata();

		return $return_data;
	}


	/**
	 * JSON-LD 生成
	 */
	public static function get_json_ld_data() {
		$json_lds = \SWELL_Theme\Json_Ld::genrate();
		return apply_filters( 'swell_json_ld_data', $json_lds );
	}


	/**
	 * 抜粋文を取得
	 */
	public static function get_excerpt( $post_data, $length = null ) {
		$length = null === $length ? (int) self::$excerpt_length : (int) $length;

		// 抜粋非表示の時
		if ( $length === 0 ) {
			return '';
		}

		// 抜粋文 作成
		if ( ! empty( $post_data->post_excerpt ) ) {

			// 「抜粋」の入力内容を優先
			$excerpt = strip_tags( $post_data->post_excerpt, '<br><i>' );

		} elseif ( ! empty( $post_data->post_password ) ) {

			// パスワード保護の記事の場合
			$excerpt = 'この記事はパスワードで保護されています';

		} else {
			// 通常
			$excerpt = strip_shortcodes( $post_data->post_content );
			$excerpt = preg_replace( '/<h2([^>]*)>([^<]*)<\/h2>/', '【$2】', $excerpt );
			$excerpt = wp_strip_all_tags( $excerpt, true );
			// $excerpt = mb_substr( $excerpt, 0, $length )." ... ";
			if ( mb_strwidth( $excerpt, 'UTF-8' ) > $length * 2 ) {
				$excerpt = mb_strimwidth( $excerpt, 0, $length * 2, '...', 'UTF-8' );
			}
		}
		return $excerpt;
	}


	/**
	 * 著者情報を取得
	 */
	public static function get_author_icon_data( $author_id ) {
		if ( ! $author_id ) return null;

		$cache_key = "post_author_icon_{$author_id}";

		// キャッシュ取得
		$cached_data = wp_cache_get( $cache_key, 'swell' );
		if ( $cached_data ) return $cached_data;

		$author_data = get_userdata( $author_id );
		if ( false === $author_data ) {
			return null;
		}

		$data = [
			'name'   => $author_data->display_name,
			'avatar' => get_avatar( $author_id, 100, '', '' ),
			'url'    => get_author_posts_url( $author_id ),
		];

		wp_cache_set( $cache_key, $data, 'swell' );
		return $return;
	}


	/**
	 * アイキャッチ画像を取得
	 * memo : image_downsize( $img_id, 'medium' );
	 */
	public static function get_thumbnail( $args ) {
		$post_id          = $args['post_id'] ?? 0;
		$term_id          = $args['term_id'] ?? 0;
		$size             = $args['size'] ?? 'full';
		$sizes            = $args['sizes'] ?? '(min-width: 960px) 960px, 100vw';
		$class            = $args['class'] ?? '';
		$placeholder      = $args['placeholder'] ?? ''; // 後方互換用
		$placeholder_size = $args['placeholder_size'] ?? '';
		$use_lazyload     = $args['use_lazyload'] ?? false;
		$use_noimg        = $args['use_noimg'] ?? true;
		$echo             = $args['echo'] ?? false;

		$attachment_args = [
			'class' => $class . ' -no-lb',
			'title' => '',
			// 'alt' => '',
		];

		$thumb            = '';
		$is_default_noimg = false;

		if ( $term_id ) {
			// タームページのサムネイルを取得したい時

			$img_url = get_term_meta( $term_id, 'swell_term_meta_image', 1 );
			$img_id  = attachment_url_to_postid( $img_url ) ?: 0;
			$thumb   = wp_get_attachment_image( $img_id, $size, false, $attachment_args );
			if ( $placeholder_size ) {
				$placeholder = wp_get_attachment_image_url( $img_id, $placeholder_size ) ?: '';
			}
		} elseif ( has_post_thumbnail( $post_id ) ) {
			// アイキャッチ画像の設定がある場合はそれを取得

			$thumb = get_the_post_thumbnail( $post_id, $size, $attachment_args );
			if ( $placeholder_size ) {
				$placeholder = get_the_post_thumbnail_url( $post_id, $placeholder_size ) ?: '';
			}
		} elseif ( $use_noimg ) {
			$noimg_id = self::get_noimg( 'id' );

			// NO-IMG設定があればそのIDから指定されたサイズの画像を取得
			if ( $noimg_id ) {
				$thumb = wp_get_attachment_image( $noimg_id, $size, false, $attachment_args );
				if ( $placeholder_size ) {
					$placeholder = wp_get_attachment_image_url( $noimg_id, $placeholder_size ) ?: '';
				}
			} else {
				$thumb            = '<img src="' . esc_url( self::get_noimg( 'url' ) ) . '" class="' . esc_attr( $class ) . '">';
				$is_default_noimg = true;
			}
		} else {
			return '';
		}

		// ソース置換
		if ( $is_default_noimg ) {
			$thumb = str_replace( ' title=""', '', $thumb );
			if ( $sizes ) {
				$thumb = preg_replace( '/ sizes="([^"]*)"/', ' sizes="' . $sizes . '"', $thumb );
			}
		}

		// lazyload準備
		if ( $use_lazyload && ! self::is_rest() && ! self::is_iframe() ) {
			$placeholder = $placeholder ?: self::$placeholder;
			$thumb       = str_replace( ' src="', ' src="' . esc_url( $placeholder ) . '" data-src="', $thumb );
			$thumb       = str_replace( ' srcset="', ' data-srcset="', $thumb );
			$thumb       = str_replace( ' class="', ' class="lazyload ', $thumb );

			$thumb = preg_replace_callback( '/<img([^>]*)>/', function( $matches ) {
				$props = rtrim( $matches[1], '/' );
				$props = self::set_aspectratio( $props );
				return '<img' . $props . '>';
			}, $thumb );
		}

		if ( $echo ) {
			echo $thumb; // phpcs:ignore
		}
		return $thumb;
	}


	/**
	 * アーカイブページのデータを取得
	 */
	public static function get_archive_data() {
		if ( ! is_archive() ) return false;

		$data = [
			'type'  => '',
			'title' => 'title',
		];

		if ( is_date() ) {
			// 日付アーカイブなら

			$qv_day      = get_query_var( 'day' );
			$qv_monthnum = get_query_var( 'monthnum' );
			$qv_year     = get_query_var( 'year' );

			if ( $qv_day !== 0 ) {
				$ymd_name = $qv_year . '年' . $qv_monthnum . '月' . $qv_day . '日';
			} elseif ( $qv_monthnum !== 0 ) {
				$ymd_name = $qv_year . '年' . $qv_monthnum . '月';
			} else {
				$ymd_name = $qv_year . '年';
			}
			if ( is_post_type_archive() ) {
				// さらに、投稿タイプの日付アーカイブだった場合
				$data['title'] = $ymd_name . '(' . post_type_archive_title( '', false ) . ')';
			}
			$data['title'] = $ymd_name;
			$data['type']  = 'date';

		} elseif ( is_post_type_archive() ) {
			// 投稿タイプのアーカイブページなら

			$data['title'] = post_type_archive_title( '', false );
			$data['type']  = 'pt_archive';

		} elseif ( is_author() ) {
			// 投稿者アーカイブ

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
	 * 内部リンクのブログカード化
	 */
	public static function get_internal_blog_card( $post_id, $card_args = [], $is_text = false ) {

		// $caption = '', $is_blank = false, $rel = '', $noimg = false;

		$card_data = '';
		// キャッシュがあるか調べる
		if ( self::is_use( 'card_cache__in' ) ) {
			$cache_key = 'swell_card_id' . $post_id;
			$card_data = get_transient( $cache_key );
		}

		// キャッシュがなければ
		if ( ! $card_data ) {

			$post_data = get_post( $post_id );
			$title     = get_the_title( $post_id );
			$url       = get_permalink( $post_id );
			$excerpt   = self::get_excerpt( $post_data, 80 );

			if ( mb_strwidth( $title, 'UTF-8' ) > 100 ) {
				$title = mb_strimwidth( $title, 0, 100, '...', 'UTF-8' );
			}
			if ( has_post_thumbnail( $post_id ) ) {
				// アイキャッチ画像のIDを取得
				$thumb_id   = get_post_thumbnail_id( $post_id );
				$thumb_data = wp_get_attachment_image_src( $thumb_id, 'medium' );
				$thumb      = $thumb_data[0];
			} else {
				$thumb = self::get_noimg( 'small' );
			}

			$card_data = [
				'url'     => $url,
				'title'   => $title,
				'thumb'   => $thumb,
				'excerpt' => $excerpt,
			];

			if ( self::is_use( 'card_cache__in' ) ) {
				$day = self::get_setting( 'cache_card_time' ) ?: 30;
				set_transient( $cache_key, $card_data, DAY_IN_SECONDS * intval( $day ) );
			}
		}

		$card_data              = array_merge( $card_data, $card_args );
		$card_data['add_class'] = '-internal';
		$card_data['type']      = self::get_editor( 'blog_card_type' ) ?: 'type1';

		if ( $is_text ) {
			return self::get_pluggable_parts( 'blog_link', $card_data );
		}
		return self::get_pluggable_parts( 'blog_card', $card_data );
	}


	/**
	 * 外部サイトのブログカード
	 */
	public static function get_external_blog_card( $url, $card_args = [], $is_text = false ) {

		$card_data = '';

		// キャッシュがあるか調べる
		if ( self::is_use( 'card_cache__ex' ) ) {
			$url_hash  = md5( $url );
			$cache_key = 'swell_card_' . $url_hash;
			$card_data = get_transient( $cache_key );

			if ( ! isset( $card_data['site_name'] ) ) {
				// キャプション不具合修正時のコード変更に対応
				delete_transient( $cache_key );
				$card_data = '';
			}
		}

		if ( ! $card_data ) {

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
			$title_decoded = utf8_decode( $title );  // utf8でのデコード
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
				$title = mb_strimwidth( $title, 0, 100 ) . '...';
			}
			if ( mb_strwidth( $description, 'UTF-8' ) > 160 ) {
				$description = mb_strimwidth( $description, 0, 160 ) . '...';
			}
			if ( mb_strwidth( $site_name, 'UTF-8' ) > 32 ) {
				$site_name = mb_strimwidth( $site_name, 0, 32 ) . '...';
			}

			$card_data = [
				'url'       => $url,
				'site_name' => $site_name,
				'title'     => $title,
				'thumb'     => $thumb_url,
				'excerpt'   => $description,
			];

			if ( self::is_use( 'card_cache__ex' ) ) {
				$day = self::get_setting( 'cache_card_time' ) ?: 30;
				set_transient( $cache_key, $card_data, DAY_IN_SECONDS * intval( $day ) );
			}
		}

		$card_data              = array_merge( $card_data, $card_args );
		$card_data['add_class'] = '-external';
		$card_data['is_blank']  = true;
		$card_data['type']      = self::get_editor( 'blog_card_type_ex' ) ?: 'type3';

		if ( $is_text ) {
			return self::get_pluggable_parts( 'blog_link', $card_data );
		}
		return self::get_pluggable_parts( 'blog_card', $card_data );
	}


	/**
	 * 著者情報を取得
	 */
	public static function get_author_data( $author_id ) {
		if ( ! $author_id ) return [];

		$return_data = [];
		$author_data = get_userdata( $author_id );

		$return_data['name']        = $author_data->display_name;
		$return_data['description'] = $author_data->description;
		$return_data['position']    = get_the_author_meta( 'position', $author_id );

		$sns_list              = [];
		$sns_list['home']      = $author_data->user_url ?: '';
		$sns_list['home2']     = get_the_author_meta( 'site2', $author_id ) ?: '';
		$sns_list['facebook']  = get_the_author_meta( 'facebook_url', $author_id ) ?: '';
		$sns_list['twitter']   = get_the_author_meta( 'twitter_url', $author_id ) ?: '';
		$sns_list['instagram'] = get_the_author_meta( 'instagram_url', $author_id ) ?: '';
		$sns_list['room']      = get_the_author_meta( 'room_url', $author_id ) ?: '';
		$sns_list['pinterest'] = get_the_author_meta( 'pinterest_url', $author_id ) ?: '';
		$sns_list['github']    = get_the_author_meta( 'github_url', $author_id ) ?: '';
		$sns_list['youtube']   = get_the_author_meta( 'youtube_url', $author_id ) ?: '';
		$sns_list['amazon']    = get_the_author_meta( 'amazon_url', $author_id ) ?: '';

		// 空の要素を排除
		$return_data['sns_list'] = array_filter( $sns_list );

		return $return_data;
	}


	/**
	 * カスタマイザーのSNS設定情報を取得
	 *
	 * @return $key => $url の配列データ
	 */
	public static function get_sns_settings() {
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

		$sns_data = [];
		foreach ( $sns_settings as $key ) {
			$url = self::get_setting( $key . '_url' );
			if ( $url ) {
				$sns_data[ $key ] = $url;
			}
		}
		return $sns_data;
	}


	/**
	 * ブログパーツのコンテンツを取得
	 */
	public static function get_blog_parts_content( $args ) {
		$q_args = [
			'post_type'              => 'blog_parts',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'posts_per_page'         => 1,
		];

		$parts_id    = isset( $args['id'] ) ? (int) $args['id'] : 0;
		$parts_title = isset( $args['title'] ) ? $args['title'] : '';

		if ( $parts_id ) {
			$q_args['p'] = $parts_id;
		} elseif ( $parts_title ) {
			$q_args['title'] = $parts_title;
		} else {
			return '';
		}

		$the_query  = new \WP_Query( $q_args );
		$parts_data = $the_query->posts;
		wp_reset_postdata();

		if ( empty( $parts_data ) ) {
			return '';
		}

		$parts_data = $parts_data[0]; // 一つしかないはず
		$parts_id   = $parts_data->ID;
		$content    = $parts_data->post_content;

		// 無限ループ回避
		if ( false !== strpos( $content, 'blog_parts id="' . $parts_id ) || false !== strpos( $content, '"partsID":"' . $parts_id ) ) {
			return 'ブログパーツ内で自信を呼び出すことはできません。';
		}

		return $content;
	}


	/**
	 * メインビジュアルのスタイルを生成する
	 */
	public static function get_mv_text_style( $text_color, $shadow_color ) {
		$return = 'color:' . $text_color . ';';
		if ( '' !== $shadow_color ) {
			$return .= 'text-shadow:1px 1px 0px ' . self::get_rgba( $shadow_color, .2 );
		}
		return $return;
	}


	/**
	 * リンク先URLからtarget属性を判定する
	 */
	public static function get_link_target( $url ) {
		// スムースリンクさせたいリンクは _blank にしない
		if ( strpos( $url, '#' ) !== 0 && strpos( $url, self::site_data( 'home' ) ) === false ) {
			return ' rel="noopener" target="_blank"';
		}
		return '';
	}


	/**
	 * ファイルURLからサイズを取得
	 */
	public static function get_file_size( $file_url ) {

		// ファイル名にサイズがあればそれを返す
		preg_match( '/-([0-9]*)x([0-9]*)\./', $file_url, $matches );
		if ( ! empty( $matches ) ) {
			return [
				'width'  => $matches[1],
				'height' => $matches[2],
			];
		}

		return false;

		// memo: attachment_url_to_postidは処理が重いので停止

		// $file_id   = attachment_url_to_postid( $file_url );
		// $file_data = wp_get_attachment_metadata( $file_id );
		// if ( ! empty( $file_data ) ) {
		// 	return [
		// 		'width'  => $file_data['width'],
		// 		'height' => $file_data['height'],
		// 	];
		// }

		// return false;
	}


}
