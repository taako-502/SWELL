<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$is_show_thumb = SWELL_FUNC::get_setting( 'show_page_link_thumb' );
$is_same_term  = SWELL_FUNC::get_setting( 'pn_link_is_same_term' );

$pn_style  = '-style-' . SWELL_FUNC::get_setting( 'page_link_style' );
$add_class = ( $is_show_thumb ) ? $pn_style . ' -thumb-on' : $pn_style;

// 前後のポスト
$prev_post = get_adjacent_post( $is_same_term, '', true );
$next_post = get_adjacent_post( $is_same_term, '', false );

?>
<ul class="p-pnLinks <?=esc_attr( $add_class )?>">
	<li class="p-pnLinks__item -prev">
		<?php
			if ( $prev_post ) :
				$prev_id    = $prev_post->ID;
				$prev_thumb = $is_show_thumb ? get_the_post_thumbnail_url( $prev_id, 'medium' ) : null;

				\SWELL_Theme::pluggable_parts( 'pnlink', [
					'type'  => 'prev',
					'id'    => $prev_id,
					'title' => $prev_post->post_title,
					'thumb' => $prev_thumb,
				] );
			endif;
		?>
	</li>
	<li class="p-pnLinks__item -next">
	<?php
		if ( $next_post ) :
			$next_id    = $next_post->ID;
			$next_thumb = $is_show_thumb ? get_the_post_thumbnail_url( $next_id, 'medium' ) : null;

			\SWELL_Theme::pluggable_parts( 'pnlink', [
				'type'  => 'next',
				'id'    => $next_id,
				'title' => $next_post->post_title,
				'thumb' => $next_thumb,
			] );
		endif;
		?>
	</li>
</ul>
