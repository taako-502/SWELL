<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$date     = $variable['date'] ?? null;
$modified = $variable['modified'] ?? null;

// 両方表示する設定の時、公開日＝更新日であれば更新日は表示しない。
if ( ( $date && $modified ) && ( $date >= $modified ) ) {
	$modified = null;
}

?>
<div class="p-articleMetas__times c-postTimes u-thin">
	<?php if ( $date ) : ?>
		<span class="c-postTimes__posted icon-posted" aria-label="公開日">
			<?=esc_html( $date->format( 'Y.m.d' ) )?>
		</span>
	<?php endif; ?>
	<?php if ( $modified ) : ?>
		<time class="c-postTimes__modified icon-modified" datetime="<?=esc_attr( $modified->format( 'Y-m-d' ) )?>" aria-label="更新日">
			<?=esc_html( $modified->format( 'Y.m.d' ) )?>
		</time>
	<?php endif; ?>
</div>
