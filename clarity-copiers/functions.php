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
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	// Fonts are self-hosted (assets/fonts, declared at the top of style.css) — no third-party requests.
	wp_enqueue_style( 'clarity-style', $uri . '/style.css', array(), (string) filemtime( $dir . '/style.css' ) );
	if ( is_child_theme() ) {
		wp_enqueue_style( 'clarity-child', get_stylesheet_uri(), array( 'clarity-style' ), (string) filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
	wp_enqueue_script( 'clarity-site', $uri . '/assets/js/site.js', array(), (string) filemtime( $dir . '/assets/js/site.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'clarity_assets' );

/** Preload the two fonts used above the fold. */
add_action( 'wp_head', function () {
	$f = get_template_directory_uri() . '/assets/fonts/';
	foreach ( array( 'montserrat-latin.woff2', 'open-sans-latin.woff2' ) as $font ) {
		echo '<link rel="preload" href="' . esc_url( $f . $font ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
	if ( is_front_page() ) {
		echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/mockup/hero-poster.jpg' ) . '" as="image" fetchpriority="high">' . "\n";
	}
}, 2 );

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
		'phone'   => 'u-phone.svg',
		'audit'   => 'u-audit.svg',
		'remote'  => 'u-remote.svg',
		'showroom'=> 'u-showroom.svg',
	);
	$file = isset( $map[ $key ] ) ? $map[ $key ] : 'w-dealer.svg';
	return get_template_directory_uri() . '/assets/mockup/' . $file;
}

/* ACF field definitions (code-defined; edited under wp-admin → Theme Content). */
require get_template_directory() . '/inc/acf-fields.php';
/* One-time content seeding so Theme Content opens pre-filled. */
require get_template_directory() . '/inc/seed.php';
require get_template_directory() . '/inc/acf-builder.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/performance.php';
require get_template_directory() . '/inc/site.php';

/**
 * get_field() that degrades gracefully when ACF is inactive.
 */
function clarity_get( $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

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
