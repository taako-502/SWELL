<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$show_thumb   = SWELL_Theme::get_setting( 'show_page_link_thumb' );
$is_same_term = is_singular( 'post' ) ? SWELL_Theme::get_setting( 'pn_link_is_same_term' ) : false;

$pn_style  = '-style-' . SWELL_Theme::get_setting( 'page_link_style' );
$add_class = ( $show_thumb ) ? $pn_style . ' -thumb-on' : $pn_style;

// 前後のポスト
$prev_post = get_adjacent_post( $is_same_term, '', true );
$next_post = get_adjacent_post( $is_same_term, '', false );

?>
<ul class="p-pnLinks <?=esc_attr( $add_class )?>">
	<li class="p-pnLinks__item -prev">
		<?php
			if ( $prev_post ) :
				SWELL_Theme::pluggable_parts( 'pnlink', [
					'type'       => 'prev',
					'id'         => $prev_post->ID,
					'title'      => $prev_post->post_title,
					'show_thumb' => $show_thumb,
				] );
			endif;
		?>
	</li>
	<li class="p-pnLinks__item -next">
	<?php
		if ( $next_post ) :
			SWELL_Theme::pluggable_parts( 'pnlink', [
				'type'       => 'next',
				'id'         => $next_post->ID,
				'title'      => $next_post->post_title,
				'show_thumb' => $show_thumb,
			] );
		endif;
		?>
	</li>
</ul>
