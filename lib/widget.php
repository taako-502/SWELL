<?php
namespace SWELL_Theme\Widget;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * ウィジェットアイテムを読み込む
 */
new \SWELL_Theme\Legacy_Widget();


/**
 * ウィジェット登録
 */
add_action( 'widgets_init', __NAMESPACE__ . '\register_area' );
function register_area() {

	// ウィジェットエリアの登録
	register_sidebar([
		'name'          => 'ヘッダー内部',
		'id'            => 'head_box',
		'description'   => 'ヘッダー内に表示するウィジェット。スマホでは表示されません。',
		'before_widget' => '<div id="%1$s" class="w-header__item %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="w-header__title">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => '共通サイドバー',
		'id'            => 'sidebar-1',
		'description'   => 'サイドバーに表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -side">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => '共通サイドバー【スマホ版】',
		'id'            => 'sidebar_sp',
		'description'   => 'ここにウィジェットをセットすると、「共通サイドバー」がスマホでのみ上書きされます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -side">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'トップページ専用サイドバー',
		'id'            => 'sidebar_top',
		'description'   => 'トップページにのみ表示されるサイドバー。「共通サイドバー」の<b>上部</b>に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -side">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => '追尾サイドバー',
		'id'            => 'fix_sidebar',
		'description'   => 'スクロールに合わせて固定表示させるサイドバー。PC表示でのみ表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -side">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'スマホ開閉メニュー下',
		'id'            => 'sp_menu_bottom',
		'description'   => 'スマホメニューの下部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -spmenu">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'トップページ上部',
		'id'            => 'front_top',
		'description'   => 'トップページのコンテンツ上部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget"><span>',
		'after_title'   => '</span></h2>',
	]);
	register_sidebar([
		'name'          => 'トップページ下部',
		'id'            => 'front_bottom',
		'description'   => 'トップページのコンテンツ下部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget"><span>',
		'after_title'   => '</span></h2>',
	]);
	register_sidebar([
		'name'          => '固定ページ上部',
		'id'            => 'page_top',
		'description'   => '固定ページのコンテンツ上部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget"><span>',
		'after_title'   => '</span></h2>',
	]);
	register_sidebar([
		'name'          => '固定ページ下部',
		'id'            => 'page_bottom',
		'description'   => '固定ページのコンテンツ下部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget"><span>',
		'after_title'   => '</span></h2>',
	]);
	register_sidebar([
		'name'          => '記事上部',
		'id'            => 'single_top',
		'description'   => '投稿ページのコンテンツ上部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget">',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
		'name'          => '記事下部',
		'id'            => 'single_bottom',
		'description'   => '投稿ページのコンテンツ下部に表示されます。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget">',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
		'name'          => 'CTAウィジェット',
		'id'            => 'single_cta',
		'description'   => '投稿ページのコンテンツ下部に表示されるCATウィジェット',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="c-secTitle -widget">',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
		'name'          => '関連記事上',
		'id'            => 'before_related',
		'description'   => '投稿ページの関連記事エリアの上に表示されるウィジェットエリア',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="l-articleBottom__title c-secTitle">',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
		'name'          => '関連記事下',
		'id'            => 'after_related',
		'description'   => '投稿ページの関連記事エリアの下に表示されるウィジェットエリア',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="l-articleBottom__title c-secTitle">',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
		'name'          => 'フッター直前',
		'id'            => 'before_footer',
		'description'   => 'フッター直前に挿入されるウィジェットエリア。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -footer">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'フッター（ PC ）1',
		'id'            => 'footer_box1',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -footer">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'フッター（ PC ）2',
		'id'            => 'footer_box2',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -footer">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'フッター（ PC ）3',
		'id'            => 'footer_box3',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -footer">',
		'after_title'   => '</div>',
	]);
	register_sidebar([
		'name'          => 'フッター（スマホ）',
		'id'            => 'footer_sp',
		'description'   => 'スマホで優先的に表示するフッターウィジェット。このウィジェットが使用されている時、「フッター（ PC )１〜３」は非表示となります。',
		'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c-widget__title -footer">',
		'after_title'   => '</div>',
	]);
}
