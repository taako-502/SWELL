<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$SETTING = SWELL_Theme::get_setting();

?>
<div class="p-fixBtnWrap">
	<?php if ( SWELL_Theme::is_show_index() && $SETTING['index_btn_style'] !== 'none' ) : ?>
		<div id="fix_index_btn" class="c-fixBtn hov-bg-main" data-onclick="toggleIndex" role="button" aria-label="目次ボタン">
			<i class="icon-index c-fixBtn__icon" role="presentation"></i>
		</div>
	<?php endif; ?>

	<?php if ( $SETTING['pagetop_style'] !== 'none' ) : ?>
		<div id="pagetop" class="c-fixBtn hov-bg-main" data-onclick="pageTop" role="button" aria-label="ページトップボタン">
			<i class="c-fixBtn__icon icon-chevron-small-up" role="presentation"></i>
		</div>
	<?php endif; ?>
</div>
