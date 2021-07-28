<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SNSなどのアイコンリストを出力するパーツテンプレート。
 *
 * @param int $variable : SWELL_Theme::get_parts()で渡される変数。
 */

// 引数から受け取る情報
$user_name      = $variable['user_name'];
$user_job       = $variable['user_job'];
$user_profile   = $variable['user_profile'];
$user_icon      = $variable['user_icon'];
$user_bg        = $variable['user_bg'];
$btn_link       = $variable['btn_link'];
$btn_text       = $variable['btn_text'];
$btn_color      = $variable['btn_color'];
$show_user_sns  = $variable['show_user_sns'];
$is_icon_circle = $variable['is_icon_circle'];

?>
<div class="p-profileBox">
	<?php if ( $user_bg ) : ?>
		<figure class="p-profileBox__bg">
			<?php
				\SWELL_Theme::lazyimg([
					'src'   => $user_bg,
					'class' => 'p-profileBox__bgImg',
				]);
			?>
		</figure>
	<?php endif; ?>
	<?php if ( $user_icon ) : ?>
		<figure class="p-profileBox__icon">
			<?php
				\SWELL_Theme::lazyimg([
					'src'   => $user_icon,
					'class' => 'p-profileBox__iconImg',
				]);
			?>
		</figure>
	<?php endif; ?>
	<div class="p-profileBox__name u-fz-m">
		<?=esc_html( $user_name )?>
	</div>
	<?php if ( $user_job ) : ?>
		<div class="p-profileBox__job u-thin">
			<?=esc_html( $user_job )?>
		</div>
	<?php endif; ?>
	<?php if ( $user_profile ) : ?>
		<div class="p-profileBox__text">
			<?=wp_kses_post( nl2br( $user_profile ) )?>
			<?php if ( $btn_link ) : ?>
				<div class="p-profileBox__btn is-style-btn_normal">
					<a href="<?=esc_url( $btn_link )?>" style="background:<?=esc_attr( $btn_color )?>" class="p-profileBox__btnLink">
						<?=wp_kses_post( do_shortcode( $btn_text ) )?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
		if ( $show_user_sns ) : // SNSアイコンリスト
			$sns_settings = SWELL_Theme::get_sns_settings();

			if ( ! empty( $sns_settings ) ) :
				$list_data = [
					'list_data' => $sns_settings,
				];
				if ( false === $is_icon_circle ) :
					$list_data['fz_class'] = 'u-fz-16';
					$list_data['ul_class'] = 'p-profileBox__iconList';
				else :
					$list_data['ul_class']  = 'p-profileBox__iconList is-style-circle';
					$list_data['fz_class']  = 'u-fz-14';
					$list_data['hov_class'] = 'hov-flash-up';
				endif;

				SWELL_Theme::get_parts( 'parts/icon_list', $list_data );
			endif;
		endif;
	?>
</div>
