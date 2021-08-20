<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$placeholder = apply_filters( 'swell_searchform_placeholder', '検索' );
?>
<form role="search" method="get" class="c-searchForm" action="<?=esc_url( \SWELL_Theme::site_data( 'home' ) )?>" role="search">
	<input type="text" value="" name="s" class="c-searchForm__s s" placeholder="<?=esc_attr( $placeholder )?>" aria-label="検索ワード">
	<button type="submit" class="c-searchForm__submit icon-search hov-opacity u-bg-main" value="search" aria-label="検索を実行する"></button>
</form>
