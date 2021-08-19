<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * render_hook
 */
require __DIR__ . '/gutenberg/render_hook/core_list.php';
require __DIR__ . '/gutenberg/render_hook/core_table.php';
require __DIR__ . '/gutenberg/render_hook/faq.php';
require __DIR__ . '/gutenberg/render_hook/full_wide.php';


/**
 * SWELLブロックの登録
 */
require __DIR__ . '/gutenberg/register_blocks.php';


/**
 * init: ブロックパターン
 */
if ( is_admin() && function_exists( 'register_block_pattern' ) ) {
	require __DIR__ . '/gutenberg/block_patterns.php';
}


/**
 * ブロック制御
 */
require __DIR__ . '/gutenberg/block_control.php';
