<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 投稿ページのタイトル部分
 */
$SETTING   = SWELL_Theme::get_setting();
$the_id    = get_the_ID();
$post_data = get_post( $the_id );
$date      = new DateTime( $post_data->post_date );
$modified  = new DateTime( $post_data->post_modified );

// 公開日 < 更新日かどうか
$is_modified = ( $date < $modified );

// タイトル横に表示する日付
$title_date = ( $is_modified && 'modified' === $SETTING['title_date_type'] ) ? $modified : $date;
?>
<div class="p-articleHead c-postTitle">
	<h1 class="c-postTitle__ttl"><?php the_title(); ?></h1>
	<time class="c-postTitle__date u-thin" datetime="<?=esc_attr( $title_date->format( 'Y-m-d' ) )?>">
		<span class="__y"><?=esc_html( $title_date->format( 'Y' ) )?></span>
		<span class="__md"><?=esc_html( $title_date->format( 'n/d' ) )?></span>
	</time>
</div>
<div class="p-articleMetas -top">
	<?php
		SWELL_Theme::get_parts( 'parts/single/item/term_list', [
			'show_cat' => $SETTING['show_meta_cat'],
			'show_tag' => $SETTING['show_meta_tag'],
		] );

		SWELL_Theme::get_parts( 'parts/single/item/times', [
			'date'     => $SETTING['show_meta_posted'] ? $date : null,
			'modified' => $SETTING['show_meta_modified'] ? $modified : null,
		] );

		if ( $SETTING['show_meta_author'] ) :
			\SWELL_Theme::pluggable_parts( 'the_post_author', [ 'author_id' => $post_data->post_author ] );
		endif;
	?>
</div>
