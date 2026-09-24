<?php
/**
 * Site-level behaviour: favicon fallback, staging noindex and legacy-URL redirects.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Theme favicon, used until a Site Icon is set under Appearance → Customize. */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) { return; }
	$i = get_template_directory_uri() . '/assets/img/';
	echo '<link rel="icon" href="' . esc_url( $i . 'favicon-32.png' ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $i . 'favicon-192.png' ) . '" sizes="192x192">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $i . 'apple-touch-icon.png' ) . '">' . "\n";
}, 3 );

/**
 * Keep the staging copy out of search engines. Only applies on the ITCS
 * dev host, so it switches itself off once the site runs on the live domain.
 */
function clarity_is_staging() {
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	return (bool) preg_match( '/(^|\.)itcscloud\.co\.uk$/i', $host );
}
add_filter( 'wp_robots', function ( $robots ) {
	if ( clarity_is_staging() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );
add_filter( 'robots_txt', function ( $txt ) {
	return clarity_is_staging() ? "User-agent: *\nDisallow: /\n" : $txt;
}, 99 );

/**
 * 301s for URLs from the previous website so existing links and rankings carry over.
 * Only runs for requests WordPress could not resolve (404s).
 */
add_action( 'template_redirect', function () {
	if ( ! is_404() ) { return; }
	$path = strtolower( rawurldecode( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$path = '/' . trim( $path, '/' ) . '/';
	$map  = array(
		'/blog/'                => '/news/',
		'/category/news/'       => '/news/',
		'/category/blog/'       => '/news/',
		'/author/web/'          => '/news/',
		'/support/videos/'      => '/support/video-library/',
		'/videos/'              => '/support/video-library/',
		'/sitemap_index.xml/'   => '/wp-sitemap.xml',
	);
	$files = array(
		'/wp-content/uploads/brochures/range brochure - dealer _ clarity glamorgan.pdf/' => 'clarity-sharp-range-brochure.pdf',
		'/wp-content/uploads/2021/09/clarity-copiers-security-white-paper.pdf/'          => 'clarity-sharp-security-white-paper.pdf',
	);
	$target = '';
	if ( isset( $map[ $path ] ) ) {
		$target = home_url( $map[ $path ] );
	} elseif ( isset( $files[ $path ] ) ) {
		$target = clarity_upload_url( $files[ $path ] );
	}
	if ( $target ) {
		wp_safe_redirect( $target, 301, 'Clarity Copiers' );
		exit;
	}
}, 1 );

/** Public URL of a media-library file by its filename (cached). */
function clarity_upload_url( $filename ) {
	$key = 'clarity_upl_' . md5( $filename );
	$url = get_transient( $key );
	if ( false === $url ) {
		global $wpdb;
		$id  = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s LIMIT 1", '%' . $wpdb->esc_like( $filename ) ) );
		$url = $id ? (string) wp_get_attachment_url( $id ) : '';
		set_transient( $key, $url, DAY_IN_SECONDS );
	}
	return $url ? $url : home_url( '/' );
}

/** Vimeo poster image for the video facade, via oEmbed (cached for a week). */
function clarity_vimeo_thumb( $id ) {
	$key   = 'clarity_vm_' . $id;
	$thumb = get_transient( $key );
	if ( false === $thumb ) {
		$thumb = '';
		$res   = wp_remote_get( 'https://vimeo.com/api/oembed.json?width=640&url=' . rawurlencode( 'https://vimeo.com/' . $id ), array( 'timeout' => 5 ) );
		if ( ! is_wp_error( $res ) && 200 === wp_remote_retrieve_response_code( $res ) ) {
			$data  = json_decode( wp_remote_retrieve_body( $res ), true );
			$thumb = isset( $data['thumbnail_url'] ) ? (string) $data['thumbnail_url'] : '';
		}
		set_transient( $key, $thumb, $thumb ? WEEK_IN_SECONDS : HOUR_IN_SECONDS );
	}
	return $thumb;
}
