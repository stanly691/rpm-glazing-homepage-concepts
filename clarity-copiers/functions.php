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
	// Exact families/weights used in the XD mockup.
	wp_enqueue_style(
		'clarity-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@700&family=Montserrat:wght@500;700;800;900&family=Open+Sans:wght@400;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'clarity-style', get_stylesheet_uri(), array( 'clarity-fonts' ), $v . '.' . filemtime( get_stylesheet_directory() . '/style.css' ) );
}
add_action( 'wp_enqueue_scripts', 'clarity_assets' );

add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

/**
 * Map a card icon key (ACF select) to the mockup's exported icon file.
 */
function clarity_mock_icon( $key ) {
	$map = array(
		'legal'   => 'icon-legal.webp',
		'hotel'   => 'icon-hospitality.webp',
		'estate'  => 'icon-estate.webp',
		'council' => 'icon-council.webp',
		'clock'   => 'w-repairs.svg',
		'award'   => 'w-experience.svg',
		'pin'     => 'w-local.svg',
		'printer' => 'w-dealer.svg',
		'support' => 'icon-customer-care.webp',
	);
	$file = isset( $map[ $key ] ) ? $map[ $key ] : 'w-dealer.svg';
	return get_template_directory_uri() . '/assets/mockup/' . $file;
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
