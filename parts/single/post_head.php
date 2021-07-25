<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 投稿ページのタイトル部分
 * $variable : $post_id
 */
$SETTING   = SWELL_FUNC::get_setting();
$post_id   = $variable ?: get_the_ID();
$post_data = get_post( $post_id );
$title     = get_the_title( $post_data ); // data渡すのが一番速そう
$date      = new DateTime( $post_data->post_date );
$modified  = new DateTime( $post_data->post_modified );

$show_meta_posted   = $SETTING['show_meta_posted'];
$show_meta_modified = $SETTING['show_meta_modified'];

$show_only_modified = ! $show_meta_posted && $show_meta_modified;

// 公開日 < 更新日かどうか
$is_modified = ( $date < $modified );

// タイトル横に表示する日付
$title_date = ( $is_modified && $SETTING['title_date_type'] === 'modified' ) ? $modified : $date;
?>
<div class="p-articleHead c-postTitle">
	<h1 class="c-postTitle__ttl"><?=$title?></h1>
	<time class="c-postTitle__date u-thin" datetime="<?=$title_date->format( 'Y-m-d' )?>">
		<span class="__y"><?=$title_date->format( 'Y' )?></span>
		<span class="__md"><?=$title_date->format( 'n/d' )?></span>
	</time>
</div>
<div class="p-articleMetas -top">
	<div class="p-articleMetas__group">
		<?php
			if ( $SETTING['show_meta_cat'] ) :
			$cat_list = \SWELL_FUNC::get_the_term_links( $post_id, 'cat' );
			if ( $cat_list ) echo '<div class="p-articleMetas__termList c-categoryList">' . $cat_list . '</div>';
			endif;
			if ( $SETTING['show_meta_tag'] ) :
			$tag_list = \SWELL_FUNC::get_the_term_links( $post_id, 'tag' );
			if ( $tag_list ) echo '<div class="p-articleMetas__termList c-tagList">' . $tag_list . '</div>';
			endif;
		?>
	</div>
	<div class="p-articleMetas__group">
		<div class="p-articleMetas__times c-postTimes u-thin">
			<?php if ( $show_meta_posted ) : ?>
				<span class="c-postTimes__posted icon-posted" aria-label="公開日">
					<?=$date->format( 'Y.m.d' )?>
				</span>
			<?php endif; ?>
			<?php if ( $show_meta_modified ) : ?>
				<?php
				if ( $show_only_modified ) :
				$modified = $is_modified ? $modified : $date;
				?>
					<time class="c-postTimes__modified icon-modified" datetime="<?=$modified->format( 'Y-m-d' )?>" aria-label="更新日">
						<?=$modified->format( 'Y.m.d' )?>
					</time>
				<?php elseif ( $is_modified ) : ?>
					<time class="c-postTimes__modified icon-modified" datetime="<?=$modified->format( 'Y-m-d' )?>" aria-label="更新日">
						<?=$modified->format( 'Y.m.d' )?>
					</time>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
		if ( $SETTING['show_meta_author'] ) :
			\SWELL_Theme::pluggable_parts( 'the_post_author', [
				'author_id' => $post_data->post_author,
			] );
		endif;
		?>
	</div>
</div>
