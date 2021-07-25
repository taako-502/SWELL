<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( SWELL_FUNC::is_show_sidebar() ) {
	get_sidebar();
}
?>
</div>
<?php
	$SETTING = SWELL_FUNC::get_setting();

	if ( \SWELL_Theme::is_use( 'pjax' ) ) echo '</div>'; // End : Barba[data-barba="container"]

	// フッター前ウィジェット
	if ( is_active_sidebar( 'before_footer' ) ) :
		echo '<div id="before_footer_widget" class="w-beforeFooter">';
		if ( ! \SWELL_Theme::is_use( 'ajax_footer' ) ) :
			SWELL_FUNC::get_parts( 'parts/footer/before_footer' );
		endif;
		echo '</div>';
	endif;

	// ぱんくず
	if ( 'top' !== $SETTING['pos_breadcrumb'] ) :
		SWELL_FUNC::get_parts( 'parts/breadcrumb' );
	endif;
?>
<footer id="footer" class="l-footer">
	<?php if ( ! \SWELL_Theme::is_use( 'ajax_footer' ) ) SWELL_FUNC::get_parts( 'parts/footer/footer_contents' ); ?>
</footer>
<?php
	// 固定フッターメニュー
	if ( has_nav_menu( 'fix_bottom_menu' ) ) :
		$cache_key = $SETTING['cache_bottom_menu'] ? 'fix_bottom_menu' : '';
		SWELL_FUNC::get_parts( 'parts/footer/fix_menu', null, $cache_key );
	endif;

	// 固定ボタン
	SWELL_FUNC::get_parts( 'parts/footer/fix_btns' );

	// モーダル
	SWELL_FUNC::get_parts( 'parts/footer/modals' );
?>
</div><!--/ #all_wrapp-->
<?php
wp_footer();
echo $SETTING['foot_code']; // phpcs:ignore
?>
</body></html>
