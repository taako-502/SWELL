<?php
namespace SWELL_Theme\Data;

trait Default_Settings {

	// $default_options
	public static function set_default_options() {
		return [
			// CORE機能の停止
			'remove_wpver'           => '1',
			'remove_wpver'           => '1',
			'remove_rel_link'        => '1',
			'remove_wlwmanifest'     => '1',
			'remove_rsd_link'        => '1',
			'remove_emoji'           => '1',
			'remove_rest_link'       => '',
			'remove_robots_image'    => '',
			'remove_sitemap'         => '1',
			'remove_media_inf_scrll' => '',
			'remove_img_srcset'      => '',
			'remove_wptexturize'     => '',
			'remove_self_pingbacks'  => '',
			'remove_feed_link'       => '',

			// SWELL機能の停止
			'remove_url2card'        => '',
			'remove_delete_empp'     => '',
			'remove_page_fade'       => '',
			'remove_luminous'        => '',
			'remove_patterns'        => '',
			'remove_lp'              => '',
			'remove_blog_parts'      => '',
			'remove_ad_tag'          => '',
			'remove_balloon'         => '',

			'load_style_inline'      => '',
			'jquery_to_foot'         => '1',
			'remove_jqmigrate'       => '1',
			'load_jquery'            => '',
			'load_font_awesome'      => '',

			// キャッシュ機能
			'cache_style'            => '1',
			'cache_header'           => '',
			'cache_sidebar'          => '',
			'cache_top'              => '',
			'cache_spmenu'           => '',
			'cache_bottom_menu'      => '',

			// ブログカード
			'cache_blogcard_in'      => '',
			'cache_blogcard_ex'      => '1',
			'cache_card_time'        => '30',

			// 遅延読み込み機能
			// 'use_ajax'           => '',
			'ajax_after_post'        => '',
			'ajax_footer'            => '',
			'use_lazyload'           => 'swell',

			// Pjax
			'use_pjax'               => 'off',
			'pjax_prevent_pages'     => '',
			'prefetch_prevent_keys'  => '',

			// JSON-LD
			'use_json_ld'            => '1',

			// 広告設定
			'sc_ad_code'             => '',
			'auto_ad_code'           => '',
			'before_h2_addcode'      => '',
			'infeed_code_pc'         => '',
			'infeed_code_sp'         => '',
			'infeed_interval'        => 4,
		];
	}

	public static function set_default_editor_options() {
		return [
			// カラーパレット
			'color_deep01'            => '#e44141',
			'color_deep02'            => '#3d79d5',
			'color_deep03'            => '#63a84d',
			'color_deep04'            => '#f09f4d',
			'color_pale01'            => '#fff2f0',
			'color_pale02'            => '#f3f8fd',
			'color_pale03'            => '#f1f9ee',
			'color_pale04'            => '#fdf9ee',

			// マーカー
			'marker_type'             => 'thin',
			'color_mark_blue'         => '#b7e3ff',
			'color_mark_green'        => '#bdf9c3',
			'color_mark_yellow'       => '#fcf69f',
			'color_mark_orange'       => '#ffddbc',

			// ボタン
			'is_btn_gradation'        => '',
			'color_btn_red'           => '#f74a4a',
			'color_btn_red2'          => '#ffbc49',
			'color_btn_blue'          => '#338df4',
			'color_btn_blue2'         => '#35eaff',
			'color_btn_green'         => '#62d847',
			'color_btn_green2'        => '#7bf7bd',
			'btn_radius_normal'       => '80px',
			'btn_radius_solid'        => '80px',
			'btn_radius_shiny'        => '80px',
			'btn_radius_line'         => '80px',

			// リスト
			// 'color_list_dot' => '',
			'color_list_check'        => '',
			'color_list_good'         => '#86dd7b',
			'color_list_bad'          => '#f36060',
			'color_list_num'          => '',

			// キャプションブロック
			'color_cap_01'            => '#f59b5f',
			'color_cap_01_light'      => '#fff8eb',
			'color_cap_02'            => '#5fb9f5',
			'color_cap_02_light'      => '#edf5ff',
			'color_cap_03'            => '#2fcd90',
			'color_cap_03_light'      => '#eafaf2',

			// Q&A
			'color_faq_q'             => '#d55656',
			'color_faq_a'             => '#6599b7',

			// ふきだしカラーセット
			'color_bln_gray_bg'       => '#f7f7f7',
			'color_bln_gray_border'   => '#ccc',
			'color_bln_green_bg'      => '#d1f8c2',
			'color_bln_green_border'  => '#9ddd93',
			'color_bln_blue_bg'       => '#e2f6ff',
			'color_bln_blue_border'   => '#93d2f0',
			'color_bln_red_bg'        => '#ffebeb',
			'color_bln_red_border'    => '#f48789',
			'color_bln_yellow_bg'     => '#f9f7d2',
			'color_bln_yellow_border' => '#fbe593',

			// // アイコンボックス
			'iconbox_type'            => 'flat',
			'iconbox_s_type'          => 'fill-flat',

			// アイコンボックスカラー
			'color_icon_good'         => '#3cd250',
			'color_icon_good_bg'      => '#ecffe9',
			'color_icon_bad'          => '#4b73eb',
			'color_icon_bad_bg'       => '#eafaff',
			'color_icon_info'         => '#f578b4',
			'color_icon_info_bg'      => '#fff0fa',
			'color_icon_announce'     => '#ffa537',
			'color_icon_announce_bg'  => '#fff5f0',
			'color_icon_pen'          => '#7a7a7a',
			'color_icon_pen_bg'       => '#f7f7f7',
			'color_icon_book'         => '#787364',
			'color_icon_book_bg'      => '#f8f6ef',

			// 大アイコン
			'color_icon_point'        => '#ffa639',
			'color_icon_check'        => '#86d67c',
			'color_icon_batsu'        => '#f36060',
			'color_icon_hatena'       => '#5295cc',
			'color_icon_caution'      => '#f7da38',
			'color_icon_memo'         => '#84878a',

			// ボーダー線
			'border01'                => 'solid 1px var(--color_main)',
			'border02'                => 'double 4px var(--color_main)',
			'border03'                => 'dashed 2px var(--color_border)',
			'border04'                => 'solid 4px var(--color_gray)',

			// デザイン設定
			'blog_card_type'          => 'type1',
			'blog_card_type_ex'       => 'type3',
			'blockquote_type'         => 'simple',

			// カスタム書式
			'format_title_1'          => 'カスタム01',
			'format_title_2'          => '',
		];
	}


	public static function set_default_customizer() {
		return [
			'no_image'                => T_DIRE_URI . '/assets/img/no_img.png',
			'ttlbg_default_img'       => '',
			// ヘッダー設定
			'logo'                    => '', // 3.0で消す
			'logo_top'                => '', // 3.0で消す
			'logo_id'                 => 0,
			'logo_top_id'             => 0,
			'logo_size_pc'            => 40,
			'logo_size_pcfix'         => 32,
			'logo_size_sp'            => 48,
			'header_layout'           => 'series_right',  // series or parallel
			'header_layout_sp'        => 'center_left',
			'header_border'           => 'shadow',
			'header_transparent'      => 'no',
			'phrase_pos'              => 'head_bar',
			'headmenu_effect'         => 'line_center',
			'head_submenu_bg'         => 'white',
			'show_title'              => false,
			'show_icon_list'          => true,
			'show_head_border'        => false,
			'fix_header'              => true,
			'fix_header_sp'           => true,
			'fix_header_opacity'      => 1,
			'menu_btn_label'          => '',
			'menu_btn_bg'             => '',
			'custom_btn_icon'         => 'icon-search',
			'custom_btn_url'          => '',
			'custom_btn_label'        => '',
			'custom_btn_bg'           => '',
			'search_pos'              => 'head_bar',
			'search_pos_sp'           => 'header',
			'info_bar_pos'            => 'none',
			'info_bar_size'           => 'normal',
			'info_bar_effect'         => 'no_effect',
			'color_info_text'         => '#fff',
			'color_info_bg'           => '#ff4133',
			'color_info_bg2'          => '',
			'color_info_btn'          => '',
			'info_flowing'            => 'no_flow',
			'info_text'               => __( 'Please enter the information here.', 'swell' ),
			'info_btn_text'           => __( 'Button', 'swell' ),
			'info_url'                => '',

			'sp_head_nav_loop'        => false,

			// SNS
			'facebook_url'            => '',
			'twitter_url'             => '',
			'instagram_url'           => '',
			'room_url'                => '',
			'line_url'                => '',
			'pinterest_url'           => '',
			'github_url'              => '',
			'youtube_url'             => '',
			'amazon_url'              => '',
			'feedly_url'              => '',
			'rss_url'                 => get_bloginfo( 'rss2_url' ),
			'contact_url'             => '',

			// カラー設定
			'color_main'              => '#04384c',
			'color_text'              => '#333',
			'color_link'              => '#39a0ff',
			'color_bg'                => '#fdfdfd',
			'color_sub_bg'            => '#f7f7f7',
			'color_sub_text'          => '#333',
			'color_header_bg'         => '#fdfdfd',
			'color_header_text'       => '#333',
			'color_head_bar_bg'       => '',
			'color_head_bar_text'     => '#fff',
			'gnav_bg_type'            => 'default',
			'color_gnav_bg'           => '',
			'color_footer_bg'         => '#fdfdfd',
			'color_footer_text'       => '#333',
			'color_footwdgt_bg'       => '',
			'color_footwdgt_text'     => '',
			'color_gradient1'         => '#d8ffff',
			'color_gradient2'         => '#87e7ff',
			'color_head_hov'          => 'main',
			'color_htag'              => '',
			'color_sec_htag'          => '',

			// spメニュー
			'color_spmenu_text'       => '#333',
			'color_spmenu_bg'         => '#fdfdfd',
			'spmenu_opacity'          => '1',
			'color_menulayer_bg'      => '#000',
			'menulayer_opacity'       => '0.6',
			'spmenu_title_type'       => 'fill',
			'spmenu_main_title'       => 'MENU',

			// デザイン・レイアウト設定
			'body_bg'                 => '',
			'body_bg_sp'              => '',
			'noloop_body_bg'          => false,
			'fix_body_bg'             => false,
			'body_bg_size'            => '',
			'body_bg_pos_x'           => 'left',
			'body_bg_pos_y'           => 'top',
			// 'site_texture'           => 'solid',
			'to_site_flat'            => false,
			'to_site_rounded'         => false,
			'content_frame'           => 'frame_off',
			'on_frame_border'         => false,
			// 'frame_only_post'         => false,
			'frame_scope'             => '',
			'pos_breadcrumb'          => 'top',
			'hide_bg_breadcrumb'      => false,
			'breadcrumb_home_text'    => __( 'Home', 'swell' ),
			'breadcrumb_set_home'     => false,
			'container_size'          => 1200,
			'article_size'            => 880,
			'show_sidebar_top'        => true,
			'show_sidebar_post'       => true,
			'show_sidebar_page'       => true,
			'show_sidebar_archive'    => true,
			'sidebar_pos'             => 'right',
			'sidettl_type'            => 'b_bottom',
			'sidettl_type_sp'         => '',
			'body_font_family'        => 'yugo',
			'post_font_size_pc'       => '16px', // 15~17
			'post_font_size_sp'       => '4vw', // 14~16 / 3.8~4.2
			'acc_submenu'             => false,

			// 投稿リスト
			'post_list_layout'        => 'card',  // card
			'post_list_layout_sp'     => 'card',  // card
			'max_column'              => '3',
			'max_column_sp'           => '1',
			'post_list_read_more'     => 'READ MORE',
			'excerpt_length_pc'       => '120',
			'excerpt_length_sp'       => '0',
			'category_pos'            => 'on_thumb',
			'pl_cat_txt_color'        => '#fff',
			'pl_cat_bg_color'         => '',
			'pl_cat_bg_style'         => 'stripe',
			'hide_post_ttl'           => false,
			'show_list_date'          => true,
			'show_list_mod'           => false,
			'show_list_author'        => false,
			'card_posts_thumb_ratio'  => 'wide',
			'list_posts_thumb_ratio'  => 'golden',
			'big_posts_thumb_ratio'   => 'wide',
			'thumb_posts_thumb_ratio' => 'golden',

			// タイトルデザイン
			'page_title_style'        => 'b_bottom',
			'archive_title_style'     => 'b_bottom',
			'sec_title_style'         => 'b_left',

			// タブ切り替え
			'show_new_tab'            => true,
			'show_ranking_tab'        => false,
			'ranking_tab_title'       => __( 'Popular articles', 'swell' ),
			'new_tab_title'           => __( 'New articles', 'swell' ),
			'top_tab_terms'           => '',
			'top_tab_style'           => 'default',
			'show_tab_on_term'        => false,
			'show_tab_on_author'      => false,
			// 除外設定
			'exc_cat_id'              => '',
			'exc_tag_id'              => '',

			// pager
			'pager_shape'             => 'square',
			'pager_style'             => 'bg',

			// 投稿ページ設定
			'show_title_date'         => true,
			'show_title_date_sp'      => true,
			'show_meta_cat'           => true,
			'show_meta_tag'           => false,
			'show_meta_posted'        => true,
			'show_meta_modified'      => true,
			'show_meta_author'        => false,

			'post_title_pos'          => 'inner',
			'page_title_pos'          => 'inner',
			'term_title_pos'          => 'inner',
			'title_date_type'         => 'modified',
			'title_bg_filter'         => 'texture-dot',
			'ttlbg_overlay_color'     => '#000',
			'ttlbg_overlay_opacity'   => 0.2,
			'show_page_thumb'         => false,
			'show_post_thumb'         => true,
			'show_noimg_thumb'        => false,
			'show_index'              => true,
			'show_index_page'         => false,
			'index_style'             => 'double',
			'index_list_tag'          => 'ol',
			'toc_target'              => 'h3',
			'toc_minnum'              => 2,
			'toc_title'               => __( 'TOC', 'swell' ),
			'toc_before_color'        => 'text',
			'toc_before_custom_color' => '#000',
			'toc_ad_position'         => 'before',
			'show_toc_ad_alone_post'  => true,
			'show_toc_ad_alone_page'  => false,

			'show_share_btn_top'      => false,
			'show_share_btn_bottom'   => true,
			'show_share_btn_fix'      => true,
			'show_share_btn_fb'       => true,
			'show_share_btn_tw'       => true,
			'show_share_btn_hatebu'   => true,
			'show_share_btn_pocket'   => true,
			'show_share_btn_pin'      => true,
			'show_share_btn_line'     => true,
			'urlcopy_btn_pos'         => 'in',
			'share_message'           => __( "Let's share this post!", 'swell' ),
			'show_fb_like_box'        => false,
			'show_tw_follow_btn'      => false,
			'show_insta_follow_btn'   => false,
			'show_page_links'         => true,
			'show_author'             => true,
			'show_author_link'        => false,
			'show_comments'           => true,
			'show_img_shadow'         => true,
			'show_related_posts'      => true,
			'share_btn_style'         => 'block',
			'share_hashtags'          => '',
			'share_via'               => '',
			'fb_like_url'             => \SWELL_Theme::site_data( 'home' ),
			'fb_like_appID'           => '',
			'tw_follow_id'            => '',
			'insta_follow_id'         => '',
			'post_author_title'       => __( 'Author of this article', 'swell' ),
			'related_post_title'      => __( 'Related posts', 'swell' ),
			'comments_title'          => __( 'Comments', 'swell' ),
			'related_post_style'      => 'card',
			'post_relation_type'      => 'category',
			'page_link_style'         => 'normal',
			'show_page_link_thumb'    => false,
			'pn_link_is_same_term'    => false,

			// 投稿パーツ
			'h2_type'                 => 'band',
			'h3_type'                 => 'main_gray',
			'h4_type'                 => 'left_line',
			'sec_h2_type'             => '',

			'show_link_underline'     => false,
			'show_border_strong'      => false,

			// TOPその他
			'top_content_mt'          => '4em',
			// ピックアップバナー
			'pickbnr_layout_pc'       => 'fix_col4',
			'pickbnr_layout_sp'       => 'fix_col2',
			'pickbnr_style'           => 'top_left',
			'pickbnr_border'          => 'on',
			'pickbnr_bgblack'         => 'off',
			'pickbnr_show_under'      => false,

			// 記事スライダー設定
			'show_post_slide'         => 'on',
			'ps_style'                => 'normal',
			'ps_num'                  => 5,
			'ps_num_sp'               => 2,
			'ps_speed'                => 1500,
			'ps_delay'                => 5000,
			'ps_on_nav'               => false,
			'ps_on_pagination'        => true,
			'ps_no_space'             => false,
			// 'ps_img_filter'     => 'nofilter',
			'ps_img_opacity'          => '1',
			'ps_bg_color'             => '',

			'ps_orderby'              => 'rand',
			'ps_pickup_type'          => 'tag',
			'pickup_tag'              => '',
			'pickup_cat'              => '',
			'pickup_cat_pos'          => 'on_thumb',
			'pickup_title'            => '',
			'pickup_font_color'       => '',
			'bg_pickup'               => '',
			'pickup_pad_lr'           => 'wide',
			'pickup_pad_tb'           => 'small',
			'ps_show_date'            => false,
			'ps_show_modified'        => false,
			'ps_show_author'          => false,

			// メインビジュアル設定
			'main_visual_type'        => 'slider',  // slider or pickup
			'mv_slide_speed'          => 1500,
			'mv_slide_delay'          => 5000,
			'mv_slide_num'            => 1,
			'mv_slide_num_sp'         => 1,
			'mv_on_nav'               => false,
			'mv_on_pagination'        => true,
			'mv_img_filter'           => 'texture_dot',
			'mv_overlay_opacity'      => 0.2,
			'mv_overlay_color'        => '#000',
			'mv_btn_radius'           => '0',

			// 動画
			'mv_video'                => '',
			'mv_video_sp'             => '',
			'mv_video_poster'         => '',
			'mv_video_poster_sp'      => '',
			'movie_title'             => '',
			'movie_text'              => '',
			'movie_url'               => '',
			'movie_btn_text'          => '',
			'movie_btncol'            => '',
			'movie_btntype'           => 'n',
			'movie_txtcol'            => '#ffffff',
			'movie_shadowcol'         => '#000000',
			'movie_txtpos'            => 'c',
			'movie_parts_id'          => '',

			// スライダー設定
			'mv_slide_size'           => 'set',
			'mv_slide_height_pc'      => '30vw',
			'mv_slide_height_sp'      => '50vh',
			'mv_full_screen'          => true,
			'mv_on_margin'            => false,
			'mv_on_scroll'            => false,
			'mv_fix_text'             => false,
			'mv_slide_effect'         => 'fade',
			'mv_slide_animation'      => 'no',

			// 各スライド
			'slider1_imgid'           => '',
			'slider1_imgid_sp'        => '',
			'slider1_alt'             => '',
			'slider1_title'           => _x( 'Let\'s start.', 'mv', 'swell' ),
			'slider1_text'            => '',
			'slider1_parts_id'        => '',
			'slider1_url'             => '',
			'slider1_btn_text'        => '',
			'slider1_btncol'          => '',
			'slider1_btntype'         => 'n',
			'slider1_txtcol'          => '#ffffff',
			'slider1_shadowcol'       => '#000000',
			'slider1_txtpos'          => 'c',
			'slider2_imgid'           => '',
			'slider2_imgid_sp'        => '',
			'slider2_alt'             => '',
			'slider2_title'           => '',
			'slider2_text'            => '',
			'slider2_parts_id'        => '',
			'slider2_url'             => '',
			'slider2_btn_text'        => '',
			'slider2_btncol'          => '',
			'slider2_btntype'         => 'n',
			'slider2_txtcol'          => '#ffffff',
			'slider2_shadowcol'       => '#000000',
			'slider2_txtpos'          => 'c',
			'slider3_imgid'           => '',
			'slider3_imgid_sp'        => '',
			'slider3_alt'             => '',
			'slider3_title'           => '',
			'slider3_text'            => '',
			'slider3_parts_id'        => '',
			'slider3_url'             => '',
			'slider3_btn_text'        => '',
			'slider3_btncol'          => '',
			'slider3_btntype'         => 'n',
			'slider3_txtcol'          => '#ffffff',
			'slider3_shadowcol'       => '#000000',
			'slider3_txtpos'          => 'c',
			'slider4_imgid'           => '',
			'slider4_imgid_sp'        => '',
			'slider4_alt'             => '',
			'slider4_title'           => '',
			'slider4_text'            => '',
			'slider4_parts_id'        => '',
			'slider4_url'             => '',
			'slider4_btn_text'        => '',
			'slider4_btncol'          => '',
			'slider4_btntype'         => 'n',
			'slider4_txtcol'          => '#ffffff',
			'slider4_shadowcol'       => '#000000',
			'slider4_txtpos'          => 'c',
			'slider5_imgid'           => '',
			'slider5_imgid_sp'        => '',
			'slider5_alt'             => '',
			'slider5_title'           => '',
			'slider5_text'            => '',
			'slider5_parts_id'        => '',
			'slider5_url'             => '',
			'slider5_btn_text'        => '',
			'slider5_btncol'          => '',
			'slider5_btntype'         => 'n',
			'slider5_txtcol'          => '#ffffff',
			'slider5_shadowcol'       => '#000000',
			'slider5_txtpos'          => 'c',

			// footer
			'pagetop_style'           => 'fix_circle',
			'index_btn_style'         => 'none',
			'footer_no_mt'            => false,
			'show_foot_icon_list'     => false,
			'footer_title_type'       => 'b_bottom',
			'copyright'               => \SWELL_Theme::site_data( 'title' ) . '.',

			'show_fbm_menu'           => true,
			'show_fbm_search'         => false,
			'show_fbm_pagetop'        => false,
			'show_fbm_index'          => false,
			'fbm_menu_label'          => __( 'Menu', 'swell' ),
			'fbm_search_label'        => __( 'Search', 'swell' ),
			'fbm_pagetop_label'       => __( 'To the top', 'swell' ),
			'fbm_index_label'         => __( 'TOC', 'swell' ),
			'color_fbm_bg'            => '#fff',
			'color_fbm_text'          => '#333',
			'fbm_opacity'             => '0.9',

			'head_code'               => '',
			'body_open_code'          => '',
			'foot_code'               => '',
			'show_category_nav'       => true,
		];
	}
}
