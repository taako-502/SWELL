<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="search_modal" class="c-modal p-searchModal">
    <div class="c-overlay" data-onclick="toggleSearch"></div>
    <div class="p-searchModal__inner">
        <?php echo get_search_form(); ?>
    </div>
</div>

<?php if ( SWELL_FUNC::is_show_index() ) : ?>
    <div id="index_modal" class="c-modal p-indexModal">
        <div class="c-overlay" data-onclick="toggleIndex"></div>
        <div class="p-indexModal__inner">
            <div class="p-toc post_content -modal"><span class="p-toc__ttl"><?=SWELL_FUNC::get_setting('toc_title')?></span></div>
            <div class="p-indexModal__close" data-onclick="toggleIndex">
                <i class="icon-batsu"></i> 閉じる
            </div>
        </div>
    </div>
<?php endif;