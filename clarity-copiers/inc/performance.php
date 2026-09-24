<?php
/**
 * Front-end performance + hardening. The theme is classic PHP with its own CSS,
 * so core's block/emoji assets are dead weight on every request.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	// Emoji detection script + styles (browsers render emoji natively).
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );

	// Head clutter.
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

// Block-editor styles are only needed when the current content was written with blocks.
function clarity_needs_block_css() {
	return is_singular() && has_blocks( get_queried_object() );
}
add_action( 'wp_enqueue_scripts', function () {
	if ( clarity_needs_block_css() ) { return; }
	foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles' ) as $h ) {
		wp_dequeue_style( $h );
	}
}, 100 );
add_action( 'wp', function () {
	if ( clarity_needs_block_css() ) { return; }
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
} );

// Embeds script (oEmbed consumer) is not needed.
add_action( 'wp_footer', function () { wp_dequeue_script( 'wp-embed' ); } );

// Smaller generated image files.
add_filter( 'wp_editor_set_quality', function () { return 82; } );

// Hardening: XML-RPC is unused; hide the WP version from assets.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'style_loader_src', 'clarity_strip_wp_ver', 10 );
add_filter( 'script_loader_src', 'clarity_strip_wp_ver', 10 );
function clarity_strip_wp_ver( $src ) {
	return ( $src && strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) ? remove_query_arg( 'ver', $src ) : $src;
}
