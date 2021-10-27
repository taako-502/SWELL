<?php
namespace SWELL_Theme\Rewrite_Html;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! is_admin() ) {

	add_action( 'wp', function() {
		ob_start( __NAMESPACE__ . '\rewrite_lazyload_scripts' );
	} );

	// add_action( 'shutdown', function() {
	// 	echo '<pre style="margin: 200px; background:#ccc">';
	// 	var_dump( 'shutdown' );
	// 	echo '</pre>';
	// }, 1 );
}

function is_keyword_included( $content, $keywords ) {
	foreach ( $keywords as $keyword ) {
		if ( strpos( $content, $keyword ) !== false ) {
			return true;
		}
	}
	return false;
}

function rewrite_lazyload_scripts( $html ) {
	try {

		// Process only GET requests
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'GET' ) return false;

		$html = trim( $html );

		// Detect non-HTML
		if ( ! isset( $html ) || $html === '' || strcasecmp( substr( $html, 0, 5 ), '<?xml' ) === 0 || $html[0] !== '<' ) {
			return false;
		}

		// Exclude on pages
		$disabled_pages = apply_filters( 'swell_lazyscripts_disabled_pages', [] );
		$current_url    = isset( $_SERVER['REQUEST_URI'] ) ? home_url( $_SERVER['REQUEST_URI'] ) : '';
		if ( is_keyword_included( $current_url, $disabled_pages ) ) {
			return false;
		}

		// error_log( PHP_EOL . '---', 3, ABSPATH . 'my.log' );
		$new_html = preg_replace_callback(
			'/<script([^>]*?)?>(.*?)?<\/script>/ims',
			__NAMESPACE__ . '\replace_scripts',
			$html
		);
		// error_log( PHP_EOL, 3, ABSPATH . 'my.log' );

		return $new_html;

	} catch ( Exception $e ) {
		return $html;
	}
}

function replace_scripts( $matches ) {

	$script = $matches[0];
	$attrs  = $matches[1];
	$code   = trim( $matches[2] );

	// https://perfmatters.io/docs/delay-javascript/#scripts

	// 遅延読み込み対象のキーワード
	$delay_js_list = trim( trim( \SWELL_Theme::get_option( 'delay_js_list' ) ), ',' );
	$delay_js_list = explode( ',', $delay_js_list );
	array_walk( $delay_js_list, function( &$item ) {
		$item = trim( $item );
	} );

	$delay_js_list = apply_filters( 'swell_delay_js_list', $delay_js_list );

	if ( $code ) {
		if ( is_keyword_included( $code, $delay_js_list ) ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			$attrs .= ' data-swldelayedjs="data:text/javascript;base64,' . base64_encode( $code ) . '"';
			$script = '<script ' . $attrs . '></script>';
		}
	} elseif ( ! empty( $attrs ) ) {
		preg_match( '/\ssrc="([^"]*)"/', $attrs, $matched_src );
		$src = ( $matched_src ) ? $matched_src[1] : '';

		if ( $src ) {
			if ( is_keyword_included( $src, $delay_js_list ) ) {
				// src を data-srcへ
				$new_attrs = str_replace( ' src=', ' data-swldelayedjs=', $attrs );

				// attrs入れ替え
				$script = str_replace( $attrs, $new_attrs, $script );
			}
			}
	}

	// log
	// error_log( $script, 3, ABSPATH . 'my.log' );

	return $script;
}

function scripts_inject() {

	$timeout = 3000 ?: 0;
	?>
<script type="text/javascript" id="swell-lazyloadscripts">
(function () {
	const timeout = <?php echo esc_attr( intval( $timeout ) ); ?>;
	const loadTimer = timeout ? setTimeout(loadJs,timeout) : null;
	const userEvents = ["mouseover","keydown","wheel","touchmove touchend","touchstart touchend"];
	userEvents.forEach(function(e){
		window.addEventListener(e,eTrigger,{passive:!0})
	});
	function eTrigger(){
		loadJs();
		if(null !== loadTimer) clearTimeout(loadTimer);
		userEvents.forEach(function(e){
			window.removeEventListener(e,eTrigger,{passive:!0});
		});
	}
	function loadJs(){
		document.querySelectorAll("script[data-swldelayedjs]").forEach(function(el){
			el.setAttribute("src",el.getAttribute("data-swldelayedjs"));
		});
	}
})();
</script>
	<?php
}

add_action( 'wp_print_footer_scripts', __NAMESPACE__ . '\scripts_inject' );
