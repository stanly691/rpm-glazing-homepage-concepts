<?php
/**
 * Contact Form 7 integration: markup/styling hooks, per-page asset loading, honeypot.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Our form templates use their own layout markup, so skip CF7's auto <p>/<br>. */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/** Does the current page render an enquiry form? (Contact section in the page builder.) */
function clarity_page_has_form() {
	if ( ! is_singular( 'page' ) || ! function_exists( 'get_field' ) ) { return false; }
	foreach ( (array) get_field( 'sections', get_queried_object_id() ) as $s ) {
		if ( is_array( $s ) && 'contact' === ( $s['acf_fc_layout'] ?? '' ) && ! empty( $s['show_form'] ) ) { return true; }
	}
	return false;
}

/** Only load CF7's CSS/JS where a form is actually shown. */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_action( 'wp_enqueue_scripts', function () {
	if ( clarity_page_has_form() ) {
		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) { wpcf7_enqueue_scripts(); }
		if ( function_exists( 'wpcf7_enqueue_styles' ) ) { wpcf7_enqueue_styles(); }
	}
}, 20 );

/** Honeypot: the hidden "cc-website" input must stay empty. */
add_filter( 'wpcf7_spam', function ( $spam ) {
	if ( $spam ) { return $spam; }
	return ! empty( $_POST['cc-website'] ); // phpcs:ignore WordPress.Security.NonceVerification -- CF7 handles the submission.
}, 10, 1 );

/**
 * Shortcode for the site's enquiry form: an explicit per-section shortcode wins,
 * otherwise the CF7 form titled "Website enquiry" is used.
 */
function clarity_enquiry_shortcode( $explicit = '' ) {
	$explicit = trim( (string) $explicit );
	if ( $explicit && 0 === strpos( $explicit, '[contact-form-7' ) ) { return $explicit; }
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) { return ''; }
	$forms = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'title' => 'Website enquiry', 'posts_per_page' => 1, 'no_found_rows' => true ) );
	return $forms ? '[contact-form-7 id="' . (int) $forms[0]->ID . '" html_class="enquiry-form"]' : '';
}
