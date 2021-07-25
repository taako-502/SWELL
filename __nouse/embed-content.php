<?php
/**
 * Contains the post embed content template part
 *
 * When a post is embedded in an iframe, this file is used to create the content template part
 * output if the active theme does not include an embed-content.php template.
 */
?>
<style>
.wp-embed{
	position: relative;
	padding: 1em;
}
.wp-embed-wrap{
	display: block;
	color: #333;
}
.wp-embed-link{
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.wp-embed-wrap::after{
	content: "";
	clear: both;
	display: block;
	height: 0;
}
.wp-embed-thumb{
	width: 40%;
	float: left;
}
.wp-embed-thumb img{
	max-width: 100%;
	height: auto;
}
.thumb_404{
	display: block;
	text-align: center;
	padding: 48px 0;
	background: #dcdcdc;
}
.wp-embed-text{
	width: 56%;
	float: right;
	overflow: hidden;
}
.wp-embed-title{
	font-size: 16px;
	padding-bottom: 6px;
	font-weight: bold;
	line-height: 1.4;
}
.wp-embed-excerpt{
	font-size: 13px;
	color: #666;
	line-height: 1.4;
}
</style>
	<div <?php post_class( 'wp-embed' ); ?>>
		<?php
		$thumbnail_id = 0;

		if ( has_post_thumbnail() ) {
			$thumbnail_id = get_post_thumbnail_id();
		}

		if ( 'attachment' === get_post_type() && wp_attachment_is_image() ) {
			$thumbnail_id = get_the_ID();
		}

		$thumbnail_id = apply_filters( 'embed_thumbnail_id', $thumbnail_id );

		if ( $thumbnail_id ) {
			$aspect_ratio = 1;
			$measurements = array( 1, 1 );
			$image_size   = 'full'; // Fallback.

			$meta = wp_get_attachment_metadata( $thumbnail_id );
			if ( ! empty( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $size => $data ) {
					if ( $data['height'] > 0 && $data['width'] / $data['height'] > $aspect_ratio ) {
						$aspect_ratio = $data['width'] / $data['height'];
						$measurements = array( $data['width'], $data['height'] );
						$image_size   = $size;
					}
				}
			}

			/**
			 * Filters the thumbnail image size for use in the embed template.
			 *
			 * @since 4.4.0
			 * @since 4.5.0 Added `$thumbnail_id` parameter.
			 *
			 * @param string $image_size   Thumbnail image size.
			 * @param int    $thumbnail_id Attachment ID.
			 */
			$image_size = apply_filters( 'embed_thumbnail_image_size', $image_size, $thumbnail_id );

			$shape = $measurements[0] / $measurements[1] >= 1.75 ? 'rectangular' : 'square';

		}

		?>
		<a href="<?php the_permalink(); ?>" target="_top" class="wp-embed-link"></a>
		<div class="wp-embed-wrap">
			<?php if ( $thumbnail_id ) : ?>
				<div class="wp-embed-thumb">
					<?php echo wp_get_attachment_image( $thumbnail_id, $image_size ); ?>
				</div>
			<?php else : ?>
			<div class="wp-embed-thumb">
				<span class="thumb_404">No Image</span>
			</div>
			<?php endif; ?>

			<div class="wp-embed-text">
				<p class="wp-embed-title">
					<?php the_title(); ?>
				</p>
				<div class="wp-embed-excerpt"><?php the_excerpt_embed(); ?></div>
			</div>
		</div>


	</div>
<?php
