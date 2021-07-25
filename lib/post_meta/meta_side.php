<?php
namespace SWELL_Theme\Meta\Side;

use \SWELL_Theme as SWELL;
use \SWELL_THEME\Parts\Setting_Field as Field;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\hook_add_meta_box', 1 );
add_action( 'save_post', __NAMESPACE__ . '\hook_save_post' );


/**
 * add_meta_box()
 */
function hook_add_meta_box() {
	add_meta_box(
		'swell_post_meta_side',
		__( 'SWELL Settings', 'swell' ),
		__NAMESPACE__ . '\side_meta_cb',
		['post', 'page' ],
		'side',
		'default',
		null
	);
}


/**
 * 【SWELL設定】
 */
function side_meta_cb( $post ) {
	global $post_type;
	$the_id = $post->ID;

	SWELL::set_nonce_field( '_meta_side' );
?>
	<div id="swell_metabox_side" class="swl-meta -side">
		<?php if ( 'page' === $post_type ) : ?>
			<div class="swl-meta__item">
			<?php
				$field_args = [
					'id'          => 'swell_meta_subttl',
					'title'       => __( 'Subtitle', 'swell' ),
					'meta'        => get_post_meta( $the_id, 'swell_meta_subttl', true ),
				];
				Field::meta_text_input( $field_args );
			?>
			</div>
		<?php elseif ( 'post' === $post_type ) : ?>

			<div class="swl-meta__item">
				<?php
					$field_args = [
						'id'          => 'swell_meta_related_posts',
						'title'       => __( 'Related articles to be displayed with priority', 'swell' ),
						'meta'        => get_post_meta( $the_id, 'swell_meta_related_posts', true ),
						'placeholder' => __( 'Enter the post ID', 'swell' ),
					];
					Field::meta_text_input( $field_args );
				?>
				<p class="swl-meta__desc">
					<?=esc_html__( 'If there are multiple, specify them separated by ",".', 'swell' )?>
				</p>
			</div>

			<div class="swl-meta__item">
			<?php
				$field_args = [
					'id'          => 'swell_meta_youtube',
					'title'       => __( 'Youtube videos for Featured image', 'swell' ),
					'meta'        => get_post_meta( $the_id, 'swell_meta_youtube', true ),
					'placeholder' => __( 'Enter the Youtube video ID', 'swell' ),
				];
				Field::meta_text_input( $field_args );
			?>
			<p class="swl-meta__desc">
				<?=esc_html__( 'Please enter only the ID part from the Youtube URL.', 'swell' )?>
			</p>
			</div>
		<?php endif; ?>

		<div class="swl-meta__item">
			<?php
				$field_args = [
					'id'          => 'swell_meta_thumb_caption',
					'title'       => __( 'Notes of featured images', 'swell' ),
					'meta'        => get_post_meta( $the_id, 'swell_meta_thumb_caption', true ),
				];
				Field::meta_text_input( $field_args );
			?>
		</div>

		<div class="swl-meta__item">
			<?php $meta_val = get_post_meta( $the_id, 'swell_meta_ttlbg', true ); ?>
			<label for="swell_meta_ttlbg" class="swl-meta__subttl">
				<?=esc_html__( 'Background image for title', 'swell' )?>
			</label>
			<div class="swl-meta__field">
				<?php Field::media_btns( 'swell_meta_ttlbg', $meta_val ); ?>
			</div>
			<p class="swl-meta__desc">
				<?=esc_html__( 'Specify the background image when the title display position is "Above the content".', 'swell' )?>
			</p>
		</div>

		<div class="swl-meta__item">
			<div class="swl-meta__subttl">
				<?=esc_html__( 'Display override settings', 'swell' )?>
			</div>
			<?php
				$show_or_hide_options = [
					'show' => _x( 'Show', 'show', 'swell' ),
					'hide' => _x( 'Hide', 'show', 'swell' ),
				];

				$meta_items = [
					'swell_meta_ttl_pos' => [
						'title'   => __( 'Title position', 'swell' ),
						'options' => [
							'top'   => __( 'Above the content', 'swell' ),
							'inner' => __( 'Inside the content', 'swell' ),
						],
					],
					'swell_meta_show_pickbnr' => [
						'title'   => __( 'Pickup Banner', 'swell' ),
						'options' => $show_or_hide_options,
					],
					'swell_meta_show_sidebar' => [
						'title'   => __( 'Sidebar', 'swell' ),
						'options' => $show_or_hide_options,
					],
					'swell_meta_show_index' => [
						'title'   => __( 'TOC', 'swell' ),
						'options' => $show_or_hide_options,
					],
					'swell_meta_show_thumb' => [
						'title'   => __( 'Featured image', 'swell' ),
						'options' => $show_or_hide_options,
					],
				];

				if ( 'post' === $post_type ) :
					$meta_items['swell_meta_show_related']  = [
						'title'   => __( 'Related posts', 'swell' ),
						'options' => $show_or_hide_options,
					];
					$meta_items['swell_meta_show_author']   = [
						'title'   => __( 'Author data', 'swell' ),
						'options' => $show_or_hide_options,
					];
					$meta_items['swell_meta_show_comments'] = [
						'title'   => __( 'Comments', 'swell' ),
						'options' => $show_or_hide_options,
					];
				endif;

				foreach ( $meta_items as $key => $data ) :
					$meta_val = get_post_meta( $the_id, $key, true );
			?>
					<div class="swl-meta__field -select">
						<label for="<?=esc_attr( $key )?>" class="swl-meta__label">
							<?=esc_html( $data['title'] )?>
						</label>
						<?php Field::meta_select( $key, $data['options'], $meta_val ); ?>
					</div>
			<?php
				endforeach;

				$meta_checkboxes = [
					'swell_meta_show_widget_top'    => __( 'Hide top-widget', 'swell' ),
					'swell_meta_show_widget_bottom' => __( 'Hide bottom-widget', 'swell' ),
					'swell_meta_hide_before_index'  => __( 'Hide pre-toc ad', 'swell' ),
					'swell_meta_hide_autoad'        => __( 'Stop automatic ad', 'swell' ),
				];
				if ( 'post' === $post_type ) :
					$meta_checkboxes['swell_meta_hide_sharebtn']   = __( 'Hide share buttons', 'swell' );
					$meta_checkboxes['swell_meta_hide_widget_cta'] = __( 'Hide CTA-widget', 'swell' );
				elseif ( 'page' === $post_type ) :
					$meta_checkboxes['swell_meta_no_mb'] = __( 'Eliminate the margins under the content', 'swell' );
				endif;

				foreach ( $meta_checkboxes as $key => $label ) :
					$meta_val = get_post_meta( $the_id, $key, true );
				?>
					<div class="swl-meta__field">
						<?php Field::meta_checkbox( $key, $label, $meta_val ); ?>
					</div>
				<?php
				endforeach;
			?>
		</div>
	</div>
<?php
}


/**
 * 保存処理
 */
function hook_save_post( $post_id ) {

	// nonce チェック
	if ( ! SWELL::check_nonce( '_meta_side' ) ) {
		return;
	}

	// 自動保存時には保存しないように
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// 権限確認
	if ( ! SWELL::check_user_can_edit( $post_id ) ) {
		return;
	}

	$METAKEYS = [
		'swell_meta_subttl',
		'swell_meta_related_posts',
		'swell_meta_youtube',
		'swell_meta_thumb_caption',
		'swell_meta_ttlbg',
		'swell_meta_ttl_pos',
		'swell_meta_show_pickbnr',
		'swell_meta_show_sidebar',
		'swell_meta_show_index',
		'swell_meta_show_thumb',
		'swell_meta_show_related',
		'swell_meta_show_author',
		'swell_meta_show_comments',
		'swell_meta_show_widget_top',
		'swell_meta_show_widget_bottom',
		'swell_meta_hide_widget_cta',
		'swell_meta_hide_before_index',
		'swell_meta_hide_autoad',
		'swell_meta_hide_sharebtn',
		'swell_meta_no_mb',
	];

	// 値の保存
	foreach ( $METAKEYS as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$meta_val = $_POST[ $key ];
			if ( ! is_bool( $meta_val ) ) {
				$meta_val = wp_kses_post( $meta_val );  // 入力された値をサニタイズ
			}

			// DBアップデート
			update_post_meta( $post_id, $key, $meta_val );
		}
	}
}
