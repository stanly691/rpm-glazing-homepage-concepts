<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
clarity_page_hero( array( 'title' => 'Page not found', 'subtitle' => 'The page you were looking for has moved or no longer exists.', 'image' => 0, 'crumbs' => array( array( 'Home', home_url( '/' ) ), array( 'Page not found', '' ) ) ) );
$mk = get_template_directory_uri() . '/assets/mockup/';
get_template_part( 'template-parts/section', 'link_cards', array( 's' => array(
	'bg'      => 'beige',
	'heading' => 'Where would you like to go?',
	'intro'   => 'These are the pages people look for most. Or call us on ' . clarity_opt( 'phone' ) . ' and we will point you in the right direction.',
	'cards'   => array(
		array( 'image' => $mk . 'product-colour.webp', 'title' => 'Products', 'text' => 'Sharp colour and mono MFPs, BIG PAD displays and visitor management.', 'link' => home_url( '/products/' ), 'link_label' => 'Browse products' ),
		array( 'image' => $mk . 'team.webp', 'title' => 'Support', 'text' => 'Remote support, service calls, toner orders and how-to videos.', 'link' => home_url( '/support/' ), 'link_label' => 'Get support' ),
		array( 'image' => $mk . 'news-mfp.webp', 'title' => 'Contact', 'text' => 'Talk to our Bridgend or Newport team about a quote or an existing account.', 'link' => home_url( '/contact/' ), 'link_label' => 'Contact us' ),
	),
) ) );
get_footer();
