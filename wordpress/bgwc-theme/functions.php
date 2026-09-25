<?php
/**
 * BGWC Coming Soon theme.
 */

defined( 'ABSPATH' ) || exit;

require_once get_theme_file_path( 'inc/acf-fields.php' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'page', 'excerpt' );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bgwc', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Keep the page identical to the design: no block editor styles on the holding page.
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter( 'pre_get_document_title', function () {
	return bgwc_field( 'meta_title' );
} );

add_action( 'wp_head', function () {
	printf( "<meta name=\"theme-color\" content=\"#121713\">\n" );
	printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( bgwc_field( 'meta_description' ) ) );
	if ( ! has_site_icon() ) {
		$icon = esc_url( get_theme_file_uri( 'assets/images/favicon.svg' ) );
		printf( "<link rel=\"icon\" type=\"image/svg+xml\" href=\"%s\">\n", $icon );
	}
}, 1 );

/**
 * On activation, create a "Home" page and make it the static front page so
 * the ACF fields appear straight away.
 */
add_action( 'after_switch_theme', function () {
	$front_id = (int) get_option( 'page_on_front' );

	if ( ! $front_id || 'page' !== get_post_type( $front_id ) ) {
		$existing = get_page_by_path( 'home' );
		$front_id = $existing ? $existing->ID : wp_insert_post(
			array(
				'post_title'  => 'Home',
				'post_name'   => 'home',
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);
	}

	if ( $front_id && ! is_wp_error( $front_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
	}
} );

/**
 * Nudge admins to install a custom fields plugin if none is active.
 */
add_action( 'admin_notices', function () {
	if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>BGWC theme:</strong> activate Advanced Custom Fields (or Secure Custom Fields) to edit the homepage content. The page still shows the default design without it.</p></div>';
} );

/**
 * Show the price on each membership card, e.g. "£25.99 / month".
 * Monthly plans are the product fields with the "bgwc-monthly" class.
 */
add_filter( 'gform_field_choice_markup_pre_render', function ( $markup, $choice, $field ) {
	if ( 'product' !== $field->type || false === strpos( (string) $field->cssClass, 'bgwc-cards' ) || '' === rgar( $choice, 'price' ) ) {
		return $markup;
	}
	$price = GFCommon::to_money( GFCommon::to_number( $choice['price'] ) );
	$per   = false !== strpos( $field->cssClass, 'bgwc-monthly' ) ? '<small> / month</small>' : '';

	return str_replace( '</label>', '<span class="bgwc-price">' . esc_html( str_replace( array( ' ', "\xc2\xa0" ), '', $price ) ) . $per . '</span></label>', $markup );
}, 10, 3 );

/**
 * UK-style prices: "£19.99", not "£ 19.99".
 */
add_filter( 'gform_currencies', function ( $currencies ) {
	if ( isset( $currencies['GBP'] ) ) {
		$currencies['GBP']['symbol_padding'] = '';
	}
	return $currencies;
} );
