<?php
/**
 * Clarity Copiers theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'clarity_setup' ) ) {
	function clarity_setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array( 'height' => 60, 'flex-width' => true ) );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'automatic-feed-links' );
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'clarity-copiers' ),
			'footer'  => __( 'Footer Quick Links', 'clarity-copiers' ),
		) );
	}
}
add_action( 'after_setup_theme', 'clarity_setup' );

function clarity_assets() {
	$v = wp_get_theme()->get( 'Version' );
	wp_enqueue_style(
		'clarity-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'clarity-style', get_stylesheet_uri(), array( 'clarity-fonts' ), $v );
}
add_action( 'wp_enqueue_scripts', 'clarity_assets' );

/**
 * Small inline SVG icon helper (used across templates).
 */
function clarity_icon( $name ) {
	$p = array(
		'phone'    => '<path d="M6.6 10.8a15.6 15.6 0 006.6 6.6l2.2-2.2a1 1 0 011-.24 11.4 11.4 0 003.6.58 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.4 11.4 0 00.58 3.6 1 1 0 01-.24 1z"/>',
		'monitor'  => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
		'support'  => '<path d="M4 13a8 8 0 0116 0v4a2 2 0 01-2 2h-1v-6h3M4 13v4a2 2 0 002 2h1v-6H4"/>',
		'audit'    => '<path d="M9 11l3 3 8-8M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>',
		'showroom' => '<path d="M3 9l9-6 9 6M5 10v9h14v-9M9 19v-5h6v5"/>',
		'legal'    => '<path d="M12 3v18M6 8l-3 6a3 3 0 006 0zM18 8l-3 6a3 3 0 006 0zM7 21h10"/>',
		'hotel'    => '<path d="M3 21V5a1 1 0 011-1h10a1 1 0 011 1v16M15 21V9h4a1 1 0 011 1v11M7 8h3M7 12h3M7 16h3"/>',
		'estate'   => '<path d="M3 11l9-7 9 7M5 10v10h14V10M10 20v-6h4v6"/>',
		'council'  => '<path d="M3 21h18M4 10h16M12 3l8 5H4zM6 10v9M10 10v9M14 10v9M18 10v9"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'award'    => '<circle cx="12" cy="9" r="6"/><path d="M9 15l-2 6 5-3 5 3-2-6"/>',
		'pin'      => '<path d="M12 22s7-6.5 7-12a7 7 0 10-14 0c0 5.5 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>',
		'printer'  => '<path d="M6 9V3h12v6M6 18H4a2 2 0 01-2-2v-4a2 2 0 012-2h16a2 2 0 012 2v4a2 2 0 01-2 2h-2M6 14h12v7H6z"/>',
	);
	$d = isset( $p[ $name ] ) ? $p[ $name ] : '';
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

/* ACF field definitions (code-defined; edited under wp-admin → Theme Content). */
require get_template_directory() . '/inc/acf-fields.php';
/* One-time content seeding so Theme Content opens pre-filled. */
require get_template_directory() . '/inc/seed.php';

/**
 * Read an ACF option, falling back to a default when empty or when ACF is absent.
 */
function clarity_field( $name, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$v = get_field( $name, 'option' );
		if ( $v !== null && $v !== '' && $v !== false ) {
			return $v;
		}
	}
	return $default;
}

/**
 * Read a repeater option, falling back to a hard-coded array of rows.
 */
function clarity_rows( $name, $default_rows = array() ) {
	if ( function_exists( 'get_field' ) ) {
		$rows = get_field( $name, 'option' );
		if ( is_array( $rows ) && ! empty( $rows ) ) {
			return $rows;
		}
	}
	return $default_rows;
}

/**
 * Contact details (ACF-backed with sensible defaults).
 */
function clarity_opt( $key ) {
	$defaults = array(
		'phone'   => '0330 221 9131',
		'email'   => 'glamorgan@clarity-copiers.co.uk',
		'address' => '1 North Rd, Bridgend Industrial Estate, Bridgend CF31 3TP',
	);
	$fallback = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return clarity_field( $key, $fallback );
}
