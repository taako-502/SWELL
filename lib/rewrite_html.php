<?php
namespace SWELL_Theme\Rewrite_Html;

if ( ! defined( 'ABSPATH' ) ) exit;

function minify_html( $html ) {
	$html    = str_replace( "\r\n", "\n", trim( $html ) );
	$search  = [
		'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
		'/[^\S ]+\</s',  // strip whitespaces before tags, except space
		'/(\s)+/s',       // shorten multiple whitespace sequences
	];
	$replace = [
		'>',
		'<',
		'\\1',
	];
	$html    = preg_replace( $search, $replace, $html );
	return $html;
}


if ( ! is_admin() ) {

	add_action( 'wp', function() {
		// ob_start();
		ob_start( __NAMESPACE__ . '\rewrite_lazyload_scripts' );
		// ob_start( __NAMESPACE__ . '\minify_html' );
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
	// $js_code = $matches[2];

	$include_list = apply_filters( 'swell_lazyscripts_target_list', [
		'twitter.com/widgets.js',
		'instagram.com/embed.js',
		'connect.facebook.net',
		'assets.pinterest.com',
		'pagead2.googlesyndication.com', // /pagead/js/adsbygoogle.js
	] );

	if ( empty( $attrs ) ) return $script;

	preg_match( '/\ssrc="([^"]*)"/', $attrs, $matched_src );
	$src = ( $matched_src ) ? $matched_src[1] : '';

	// srcなければ何もせず返す
	if ( ! $src ) return $script;

	if ( is_keyword_included( $src, $include_list ) ) {
		// src を data-srcへ
		$new_attrs = str_replace( ' src=', ' data-type="lazy" data-src=', $attrs );

		// attrs入れ替え
		$script = str_replace( $attrs, $new_attrs, $script );
	}

	// log
	// error_log( $script, 3, ABSPATH . 'my.log' );

	return $script;
}

function scripts_inject() {
	$timeout = intval( 4000 );
	?>
<script type="text/javascript" id="swell-lazyloadscripts">
(function () {
	const loadJsTimer = setTimeout(loadJs,<?php echo esc_attr( $timeout ); ?>);
	const userEvents = ["mouseover","keydown","touchstart","touchmove","wheel"];
	userEvents.forEach(function(event){
		window.addEventListener(event,eventTrigger,{passive:!0})
	});

	function eventTrigger(){
		loadJs();
		clearTimeout(loadJsTimer);
		userEvents.forEach(function(event){
			window.removeEventListener(event,eventTrigger,{passive:!0});
		});
	}

	function loadJs(){
		document.querySelectorAll("script[data-type='lazy']").forEach(function(elem){
			elem.setAttribute("src",elem.getAttribute("data-src"));
		});
	}
})();
</script>
	<?php
}

add_action( 'wp_print_footer_scripts', __NAMESPACE__ . '\scripts_inject' );
