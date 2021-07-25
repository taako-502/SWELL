<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main id="main_content" class="l-mainContent l-article">
	<div class="l-mainContent__inner">
		<h1 class="c-ttl404">
			ページが見つかりませんでした。
		</h1>
		<div class="post_content">
			<p class="u-ta-c">
				お探しのページは移動または削除された可能性があります。
			</p>
			<p class="u-ta-c">
				以下より キーワード を入力して検索してみてください。
			</p>
			<?php echo get_search_form(); ?>
			<div class="is-style-more_btn">
				<a href="<?=esc_url( \SWELL_Theme::site_data( 'home' ) )?>">TOPページへ</a>
			</div>

		</div>
	</div>
</main>
<?php get_footer(); ?>
