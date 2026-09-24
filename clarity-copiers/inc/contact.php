<?php
/**
 * Enquiry form handler (admin-post). Every enquiry is stored as a private
 * "Enquiry" post in wp-admin and emailed to the site address.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	register_post_type( 'clarity_enquiry', array(
		'label'           => 'Enquiries',
		'labels'          => array( 'name' => 'Enquiries', 'singular_name' => 'Enquiry' ),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-email-alt',
		'menu_position'   => 4,
		'supports'        => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
} );

add_action( 'admin_post_nopriv_clarity_enquiry', 'clarity_handle_enquiry' );
add_action( 'admin_post_clarity_enquiry', 'clarity_handle_enquiry' );

function clarity_handle_enquiry() {
	$back = isset( $_POST['back'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['back'] ) ), home_url( '/contact/' ) ) : home_url( '/contact/' );
	$go   = function ( $status ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'enquiry', $status, $back ) . '#enquiry' );
		exit;
	};

	// Nonces are user-bound and expire, so they break on cached pages for anonymous visitors.
	// Enforce them for logged-in users (real CSRF risk); anonymous spam is handled by the checks below.
	if ( is_user_logged_in() && ( ! isset( $_POST['clarity_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['clarity_nonce'] ) ), 'clarity_enquiry' ) ) ) {
		$go( 'error' );
	}
	// Bots: honeypot filled, or submitted faster than a human could.
	$ts = isset( $_POST['ts'] ) ? (int) $_POST['ts'] : 0;
	if ( ! empty( $_POST['website'] ) || ( time() - $ts ) < 3 ) {
		$go( 'sent' );
	}

	// Per-IP limit is generous (visitors behind one proxy share an address); per-email limit is tight.
	$ip_key = 'clarity_enq_ip_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	if ( (int) get_transient( $ip_key ) >= 20 ) {
		$go( 'limited' );
	}

	$clean = function ( $k, $max ) {
		$v = isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '';
		return mb_substr( $v, 0, $max );
	};
	$name    = $clean( 'name', 100 );
	$company = $clean( 'company', 120 );
	$email   = sanitize_email( $clean( 'email', 150 ) );
	$phone   = $clean( 'phone', 40 );
	$topic   = $clean( 'topic', 80 );
	$message = isset( $_POST['message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['message'] ) ), 0, 3000 ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === trim( $message ) ) {
		$go( 'invalid' );
	}
	$em_key = 'clarity_enq_em_' . md5( strtolower( $email ) );
	if ( (int) get_transient( $em_key ) >= 3 ) {
		$go( 'limited' );
	}
	set_transient( $ip_key, (int) get_transient( $ip_key ) + 1, HOUR_IN_SECONDS );
	set_transient( $em_key, (int) get_transient( $em_key ) + 1, HOUR_IN_SECONDS );

	$body = "Name: {$name}\nCompany: {$company}\nEmail: {$email}\nPhone: {$phone}\nTopic: {$topic}\n\n{$message}\n\nSent from: {$back}";

	wp_insert_post( array(
		'post_type'    => 'clarity_enquiry',
		'post_status'  => 'private',
		'post_title'   => wp_strip_all_tags( $topic . ' — ' . $name . ( $company ? ' (' . $company . ')' : '' ) ),
		'post_content' => esc_html( $body ),
	) );

	$to      = function_exists( 'clarity_opt' ) ? sanitize_email( clarity_opt( 'email' ) ) : '';
	$to      = is_email( $to ) ? $to : get_option( 'admin_email' );
	$headers = array( 'Reply-To: ' . str_replace( array( "\r", "\n" ), '', $name ) . ' <' . $email . '>' );
	wp_mail( $to, 'Website enquiry: ' . $topic . ' — ' . $name, $body, $headers );

	$go( 'sent' );
}
