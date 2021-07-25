<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * sns_btns
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$position = $variable['position'];
$SETTEING = SWELL_FUNC::get_setting();


$the_id      = $variable['post_id'] ?: get_the_ID();
$share_url   = get_permalink( $the_id );
$share_title = html_entity_decode( get_the_title( $the_id ) );

$style         = $SETTEING['share_btn_style'];
$hashtags      = $SETTEING['share_hashtags'];
$via           = $SETTEING['share_via'];
$urlcopy_pos   = $SETTEING['urlcopy_btn_pos'];
$share_message = $SETTEING['share_message'];

$is_fix = '-fix' === $position;

$share_btns_class = $position . ' -style-' . $style;
$hov_class        = ( 'icon' === $style ) ? '' : 'hov-flash-up';

$share_btns = [
	'facebook' => [
		'check_key'   => 'show_share_btn_fb',
		'title'       => __( 'Share on Facebook', 'swell' ),
		'href'        => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $share_url ),
		'window_size' => 'height=800,width=600',
	],
	'twitter' => [
		'check_key'   => 'show_share_btn_tw',
		'title'       => __( 'Share on Twitter', 'swell' ),
		'href'        => 'https://twitter.com/share?',
		'window_size' => 'height=400,width=600',
		'querys'      => [
			'url'  => $share_url,
			'text' => $share_title,
		],
	],
	'hatebu' => [
		'check_key'   => 'show_share_btn_hatebu',
		'title'       => __( 'Register in Hatena Bookmark', 'swell' ),
		'href'        => '//b.hatena.ne.jp/add?mode=confirm&url=' . urlencode( $share_url ),
		'window_size' => 'height=600,width=1000',
	],
	'pocket' => [
		'check_key' => 'show_share_btn_pocket',
		'title'     => __( 'Save to Pocket', 'swell' ),
		'href'      => 'https://getpocket.com/edit?',
		'querys'    => [
			'url'   => $share_url,
			'title' => $share_title,
		],
	],
	'pinterest' => [
		'check_key' => 'show_share_btn_pin',
		'title'     => __( 'Save pin', 'swell' ),
		'href'      => 'https://jp.pinterest.com/pin/create/button/',
		'attrs'     => 'data-pin-do="buttonBookmark" data-pin-custom="true" data-pin-lang="ja"',
	],
	'line' => [
		'check_key' => 'show_share_btn_line',
		'title'     => __( 'Send to LINE', 'swell' ),
		'href'      => 'https://social-plugins.line.me/lineit/share?',
		'querys'    => [
			'url'   => $share_url,
			'text'  => $share_title,
		],
	],
];

if ( 'out' === $urlcopy_pos ) $share_btns_class .= ' has-big-copybtn';
?>
<div class="c-shareBtns <?=esc_attr( $share_btns_class )?>">
	<?php if ( '-bottom' === $position && $share_message ) : ?>
		<div class="c-shareBtns__message">
			<span class="__text">
				<?=esc_html( $share_message )?>
			</span>
		</div>
	<?php endif; ?>
	<ul class="c-shareBtns__list">
		<?php foreach ( $share_btns as $key => $data ) : ?>
		<?php
			if ( ! $SETTEING[ $data['check_key'] ] ) continue;

			if ( 'pinterest' === $key ) {
				\SWELL_Theme::$use['pinterest'] = true;
			}

			if ( isset( $data['querys'] ) ) :
				$querys = $data['querys'];

				// Twitterだけ追加設定あり
				if ( 'twitter' === $key ) :
					if ( $hashtags ) $querys['hashtags'] = $hashtags;
					if ( $via ) $querys['via']           = $via;
				endif;
				$href = $data['href'] . http_build_query( $querys, '', '&' );
			else :
				$href = $data['href'];
			endif;

			$btn_attrs  = 'href="' . esc_url( $href ) . '"';
			$btn_attrs .= ' title="' . $data['title'] . '"';

			// onclick
			if ( isset( $data['window_size'] ) ) :
				$window_size = $data['window_size'];

				$onclick = "javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,${window_size}');return false;";

				$btn_attrs .= ' onclick="' . $onclick . '"';
			endif;

			// 追加の属性があれば
			if ( isset( $data['attrs'] ) ) $btn_attrs .= ' ' . $data['attrs'];

			?>
			<li class="c-shareBtns__item -<?=$key?>">
				<a class="c-shareBtns__btn <?=$hov_class?>" <?=$btn_attrs?> target="_blank" role="button">
					<i class="snsicon c-shareBtns__icon icon-<?=$key?>" role="presentation"></i>
				</a>
			</li>
		<?php endforeach; ?>
		<?php if ( ( ! $is_fix && 'in' === $urlcopy_pos ) || ( $is_fix && 'none' !== $urlcopy_pos ) ) : ?>
			<?php \SWELL_Theme::$use['clipboard'] = true; ?>
			<li class="c-shareBtns__item -copy">
				<div class="c-urlcopy c-shareBtns__btn <?=$hov_class?>" data-clipboard-text="<?=esc_url( $share_url )?>" title="<?=__( 'Copy the URL', 'swell' )?>">
					<div class="c-urlcopy__content">
						<i class="c-shareBtns__icon icon-clipboard-copy -to-copy"></i>
						<i class="c-shareBtns__icon icon-clipboard-copied -copied"></i>
					</div>
				</div>
				<div class="c-copyedPoppup">URL Copied!</div>
			</li>
		<?php endif; ?>
	</ul>

	<?php if ( ! $is_fix && 'out' === $urlcopy_pos ) : ?>
		<?php \SWELL_Theme::$use['clipboard'] = true; ?>
		<div class="c-shareBtns__item -copy c-big-urlcopy">
			<div class="c-urlcopy c-shareBtns__btn <?=$hov_class?>" data-clipboard-text="<?=esc_url( $share_url )?>" title="<?=__( 'Copy the URL', 'swell' )?>" role="button">
			<div class="c-urlcopy__content">
				<div class="c-shareBtns__icon -to-copy">
					<i class="icon-clipboard-copy"></i>
					<span class="c-urlcopy__text"><?=__( 'Copy the URL', 'swell' )?></span>
				</div>
				<div class="c-shareBtns__icon -copied">
					<i class="icon-clipboard-copied"></i>
					<span class="c-urlcopy__text"><?=__( 'Copied the URL!', 'swell' )?></span>
				</div>
			</div>
			</div>
		</div>
	<?php endif; ?>

</div>
