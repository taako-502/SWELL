<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
while( have_posts() ) : the_post();
?>
<main id="main_content" class="l-mainContent l-article">
    <div class="l-mainContent__inner">
        <?php if ( is_active_sidebar('front_top') ) : ?>
            <div class="w-frontTop">
                <?php dynamic_sidebar( 'front_top' ); ?>
            </div>
        <?php endif; ?>
        <div class="<?=apply_filters('swell_post_content_class','post_content')?>">
            <?php the_content(); ?>
        </div>
        <?php if ( is_active_sidebar('front_bottom') ) : ?>
            <div class="w-frontBottom">
                <?php dynamic_sidebar( 'front_bottom' ); ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php endwhile; ?>
