<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 公開日・更新日情報
 */
$show_date     = $variable['show_date'] ?? false;
$show_modified = $variable['show_modified'] ?? false;
$date          = $variable['date'] ?? '';
$modified      = $variable['modified'] ?? '';

$date     = new DateTime( $date );
$modified = new DateTime( $modified );

// 更新日が公開日より遅い場合だけ表示（ただし、更新日だけ表示の時は更新日をそのまま表示する）
if ( $show_modified && $show_date ) {
	$show_modified = ( $date < $modified ) ? $show_modified : false;
}

if ( ! $show_date && ! $show_modified ) return;

?>
<div class="p-postList__times c-postTimes u-thin">
	<?php if ( $show_date ) : ?>
		<time class="c-postTimes__posted icon-posted" datetime="<?=esc_attr( $date->format( 'Y-m-d' ) )?>"><?=esc_html( $date->format( 'Y.m.d' ) )?></time>
	<?php endif; ?>
	<?php if ( $show_modified ) : ?>
		<time class="c-postTimes__modified icon-modified" datetime="<?=esc_attr( $modified->format( 'Y-m-d' ) )?>"><?=esc_html( $modified->format( 'Y.m.d' ) )?></time>
	<?php endif; ?>
</div>
