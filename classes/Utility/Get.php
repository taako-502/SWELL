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
	public static function get_author_data( $author_id, $get_link = false ) {
		if ( ! $author_id ) return null;

		$author_data = get_userdata( $author_id );
		if ( false === $author_data ) {
			return null;
		}

		$return = [
			'name'   => $author_data->display_name,
			'avatar' => get_avatar( $author_id, 100, '', '' ),
		];

		if ( $get_link ) {
			$return['url'] = get_author_posts_url( $author_id );
		}

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
			} elseif ( $qv_monthnum != 0 ) {
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
}
