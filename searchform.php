<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<form role="search" method="get" class="c-searchForm" action="<?=esc_url( \SWELL_Theme::site_data( 'home' ) )?>" role="search">
	<input type="text" value="" name="s" class="c-searchForm__s s" placeholder="検索" aria-label="検索ワード">
	<button type="submit" class="c-searchForm__submit icon-search hov-opacity u-bg-main" value="search" aria-label="検索を実行する"></button>
</form>
