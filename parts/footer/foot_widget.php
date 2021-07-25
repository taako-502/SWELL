<?php 
if( ! defined( 'ABSPATH' ) ) exit;
$SETTING = SWELL_FUNC::get_setting(); //セッティング情報取得

$foot_widget = '';
$widget_ct = 0;

if ( IS_MOBILE && is_active_sidebar( 'footer_sp' ) ) {
	// スマホ用フッターウィジェット
	ob_start();
	echo '<div class="w-footer__box">';
		dynamic_sidebar( 'footer_sp' );
	echo '</div>';
	$foot_widget = ob_get_clean();
} else {
	// フッターウィジェット 1 ~ 3
	ob_start();
	$widget_ct = 0;
	if ( is_active_sidebar( 'footer_box1' ) ) {
		$widget_ct++;
		echo '<div class="w-footer__box">';
			dynamic_sidebar( 'footer_box1' );
		echo '</div>';
	}
	if ( is_active_sidebar( 'footer_box2' ) ) {
		$widget_ct++;
		echo '<div class="w-footer__box">';
			dynamic_sidebar( 'footer_box2' );
		echo '</div>';
	}
	if ( is_active_sidebar( 'footer_box3' ) ) {
		$widget_ct++;
		echo '<div class="w-footer__box">';
			dynamic_sidebar( 'footer_box3' );
		echo '</div>';
	}
	$foot_widget = ob_get_clean();
}
?>

<?php if ( $foot_widget !== '' ) : ?>
<div class="l-footer__widgetArea">
	<div class="l-container w-footer <?='-col'.$widget_ct?>">
		<?php echo $foot_widget; ?>
	</div>
</div>
<?php endif; ?>

