<?php
/**
 * Lightweight SEO: meta description, Open Graph / Twitter, JSON-LD
 * (LocalBusiness + BreadcrumbList). Disabled automatically when a
 * dedicated SEO plugin (Yoast, Rank Math, SEOPress, AIOSEO) is active.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function clarity_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

add_filter( 'document_title_separator', function () { return '|'; } );

/** Best available description for the current request (≤ 158 chars). */
function clarity_meta_description() {
	$d = '';
	if ( is_front_page() ) {
		$d = function_exists( 'clarity_field' ) ? clarity_field( 'hero_text', '' ) : '';
	} elseif ( is_singular() && post_password_required( get_queried_object_id() ) ) {
		$d = '';
	} elseif ( is_singular() ) {
		$id = get_queried_object_id();
		if ( function_exists( 'get_field' ) ) {
			$d = (string) get_field( 'seo_description', $id );
			if ( ! $d ) { $d = (string) get_field( 'summary', $id ); }
			if ( ! $d ) { $d = (string) get_field( 'hero_subtitle', $id ); }
			if ( ! $d ) {
				$rows = get_field( 'sections', $id );
				foreach ( (array) $rows as $r ) {
					$d = wp_strip_all_tags( $r['intro'] ?? ( $r['text'] ?? '' ) );
					if ( $d ) { break; }
				}
			}
		}
		if ( ! $d && has_excerpt( $id ) ) { $d = get_the_excerpt( $id ); }
		if ( ! $d ) { $d = wp_strip_all_tags( get_post_field( 'post_content', $id ) ); }
	} elseif ( is_home() ) {
		$d = 'Product releases, industry insights and company news from Clarity Copiers Glamorgan, your local Sharp main dealer.';
	}
	if ( ! $d ) { $d = get_bloginfo( 'description' ); }
	$d = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $d ) ) );
	if ( mb_strlen( $d ) > 158 ) {
		$d = rtrim( mb_substr( $d, 0, 155 ), " ,.;:-–" ) . '…';
	}
	return $d;
}

function clarity_share_image() {
	if ( is_singular() ) {
		$id  = get_queried_object_id();
		$img = function_exists( 'get_field' ) ? get_field( 'hero_image', $id ) : 0;
		if ( ! $img && has_post_thumbnail( $id ) ) { $img = get_post_thumbnail_id( $id ); }
		if ( $img ) {
			$u = wp_get_attachment_image_url( (int) $img, 'large' );
			if ( $u ) { return $u; }
		}
	}
	return get_template_directory_uri() . '/assets/mockup/hero-poster.jpg';
}

add_action( 'wp_head', function () {
	if ( clarity_seo_plugin_active() ) { return; }
	$desc  = clarity_meta_description();
	$title = wp_get_document_title();
	if ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_singular() ) {
		$url = get_permalink();
	} elseif ( is_home() ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		$url   = 1 === $paged ? get_permalink( (int) get_option( 'page_for_posts' ) ) : get_pagenum_link( $paged, false );
	} else {
		$url = remove_query_arg( array_keys( $_GET ), get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ), false ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	$img   = clarity_share_image();

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	$og = array(
		'og:locale'      => 'en_GB',
		'og:type'        => is_singular( 'post' ) ? 'article' : 'website',
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $img,
	);
	foreach ( $og as $k => $v ) {
		echo '<meta property="' . esc_attr( $k ) . '" content="' . esc_attr( $v ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	if ( is_front_page() || is_home() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}
}, 3 );

add_action( 'wp_head', function () {
	if ( clarity_seo_plugin_active() ) { return; }
	$opt   = function ( $k, $d = '' ) { return function_exists( 'clarity_opt' ) ? clarity_opt( $k ) : $d; };
	$hours = array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ), 'opens' => '09:00', 'closes' => '17:00' );
	$same  = array();
	foreach ( (array) ( function_exists( 'clarity_rows' ) ? clarity_rows( 'socials', array() ) : array() ) as $s ) {
		if ( ! empty( $s['url'] ) && '#' !== $s['url'] ) { $same[] = $s['url']; }
	}
	$graph = array(
		array(
			'@type'                     => 'LocalBusiness',
			'@id'                       => home_url( '/#business' ),
			'name'                      => get_bloginfo( 'name' ),
			'description'               => 'Sharp main dealer supplying multifunction printers, managed print and interactive displays across South Wales since 1995.',
			'url'                       => home_url( '/' ),
			'logo'                      => get_template_directory_uri() . '/assets/mockup/logo.webp',
			'image'                     => get_template_directory_uri() . '/assets/mockup/hero-poster.jpg',
			'telephone'                 => $opt( 'phone' ),
			'email'                     => $opt( 'email' ),
			'foundingDate'              => '1995',
			'priceRange'                => '££',
			'areaServed'                => 'South Wales',
			'address'                   => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => '1 North Road, Bridgend Industrial Estate',
				'addressLocality' => 'Bridgend',
				'postalCode'      => 'CF31 3TP',
				'addressCountry'  => 'GB',
			),
			'openingHoursSpecification' => array( $hours ),
			'department'                => array(
				array( '@type' => 'LocalBusiness', 'name' => get_bloginfo( 'name' ) . ' – Newport', 'telephone' => '01633 744207', 'address' => array( '@type' => 'PostalAddress', 'addressLocality' => 'Newport', 'addressCountry' => 'GB' ), 'openingHoursSpecification' => array( $hours ) ),
			),
		),
	);
	if ( $same ) { $graph[0]['sameAs'] = $same; }

	if ( is_singular() && ! is_front_page() && function_exists( 'clarity_breadcrumb_items' ) ) {
		$list = array();
		foreach ( clarity_breadcrumb_items() as $i => $c ) {
			$list[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1] );
		}
		$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
	}
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}, 20 );
