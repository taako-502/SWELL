<?php
use \SWELL_THEME\Parts\Post_List;
if ( ! defined( 'ABSPATH' ) ) exit;

// 引数受け取り
$the_id = $variable['post_id'] ?? 0;

// 投稿情報を取得 （ループ中 or ID直接指定でループ外からの呼び出しの２パターンがあることに注意）
$post_data = $the_id ? get_post( $the_id ) : get_post();
$the_id    = $the_id ?: $post_data->ID;
$the_title = get_the_title( $the_id );
$date      = mysql2date( 'Y.m.d', $post_data->post_date );
?>
<li class="p-postList__item">
	<a href="<?php the_permalink( $the_id ); ?>" class="p-postList__link">
		<div class="p-postList__thumb c-postThumb">
			<figure class="c-postThumb__figure">
			<?php
				\SWELL_Theme::get_thumbnail( [
					'post_id' => $the_id,
					'size'    => 'medium',
					'sizes'   => '(min-width: 600px) 320px, 50vw',
					'class'   => 'c-postThumb__img u-obf-cover',
					'echo'    => true,
				] );
			?>
			</figure>
		</div>
		<div class="p-postList__body">
			<div class="p-postList__title"><?=esc_html( $the_title )?></div>
			<div class="p-postList__meta">
				<div class="p-postList__times c-postTimes u-thin">
					<span class="c-postTimes__posted icon-posted"><?=esc_html( $date )?></span>
				</div>
			</div>
		</div>
	</a>
</li>
