<?php
/**
 * Code-defined ACF field groups for Clarity Copiers (version-controlled here).
 * - Homepage content: edited on the Home page itself (Pages → Home).
 * - Site-wide details (phone, email, address, socials, footer): Site Settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Options page: "Site Settings" (slug kept as theme-content so saved values stay attached) */
add_action( 'acf/init', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => 'Site Settings',
			'menu_title' => 'Site Settings',
			'menu_slug'  => 'theme-content',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-layout',
			'position'   => 3,
			'redirect'   => false,
		) );
	}
} );

/* Icon choices shared by the icon selects */
function clarity_icon_choices() {
	return array(
		'legal' => 'Gavel (Legal)', 'hotel' => 'Hotel (Hospitality)',
		'estate' => 'Keys (Estate Agents)', 'council' => 'Meeting (Councils)',
		'clock' => 'Stopwatch', 'award' => 'Heart', 'pin' => 'Location pin',
		'printer' => 'Printer', 'support' => 'Headset',
		'phone' => 'Phone', 'audit' => 'Screen tick (audit)', 'remote' => 'Mouse (remote)', 'showroom' => 'Showroom',
	);
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

	/* ---------- HOMEPAGE ---------- */
	acf_add_local_field_group( array(
		'key'      => 'group_clarity_home',
		'title'    => 'Homepage content',
		'location' => array( array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ) ),
		'position' => 'acf_after_title',
		'hide_on_screen' => array( 'the_content' ),
		'menu_order' => 0,
		'fields'   => array(
			array( 'key' => 'f_hero_tab', 'label' => 'Hero', 'type' => 'tab' ),
			array( 'key' => 'f_hero_pill', 'label' => 'Pill text', 'name' => 'hero_pill', 'type' => 'text', 'default_value' => '24/7 Remote Support Available' ),
			array( 'key' => 'f_hero_heading', 'label' => 'Heading (new line = line break)', 'name' => 'hero_heading', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'default_value' => "Empowering Productivity Through\nSharp Technology" ),
			array( 'key' => 'f_hero_text', 'label' => 'Sub text', 'name' => 'hero_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Managed print and document solutions that save time, reduce waste and keep your business moving. Trusted by businesses across the UK for 30 years.' ),
			array( 'key' => 'f_hero_video', 'label' => 'Background video (MP4)', 'name' => 'hero_video', 'type' => 'file', 'return_format' => 'url', 'mime_types' => 'mp4,webm', 'instructions' => 'Optional looping background video for the hero. Leave empty for the dark gradient.' ),
			array( 'key' => 'f_hero_b1l', 'label' => 'Button 1 label', 'name' => 'hero_btn1_label', 'type' => 'text', 'default_value' => 'Get a Quote', 'wrapper' => array( 'width' => 25 ) ),
			array( 'key' => 'f_hero_b1u', 'label' => 'Button 1 URL', 'name' => 'hero_btn1_url', 'type' => 'text', 'default_value' => '/contact/', 'wrapper' => array( 'width' => 25 ) ),
			array( 'key' => 'f_hero_b2l', 'label' => 'Button 2 label', 'name' => 'hero_btn2_label', 'type' => 'text', 'default_value' => 'Get Remote Support', 'wrapper' => array( 'width' => 25 ) ),
			array( 'key' => 'f_hero_b2u', 'label' => 'Button 2 URL', 'name' => 'hero_btn2_url', 'type' => 'text', 'default_value' => '/support/', 'wrapper' => array( 'width' => 25 ) ),

			array( 'key' => 'f_sec_tab', 'label' => 'Sectors', 'type' => 'tab' ),
			array( 'key' => 'f_sec_h', 'label' => 'Heading', 'name' => 'sectors_heading', 'type' => 'text', 'default_value' => 'Expertise Across Sectors' ),
			array( 'key' => 'f_sec_i', 'label' => 'Intro', 'name' => 'sectors_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.' ),
			array( 'key' => 'f_sec_r', 'label' => 'Sector cards', 'name' => 'sectors', 'type' => 'repeater', 'button_label' => 'Add sector', 'layout' => 'block', 'sub_fields' => array(
				array( 'key' => 'f_sec_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'select', 'choices' => clarity_icon_choices(), 'wrapper' => array( 'width' => 30 ) ),
				array( 'key' => 'f_sec_t', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => 70 ) ),
				array( 'key' => 'f_sec_d', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2 ),
			) ),

			array( 'key' => 'f_why_tab', 'label' => 'Why Choose', 'type' => 'tab' ),
			array( 'key' => 'f_why_h', 'label' => 'Heading', 'name' => 'why_heading', 'type' => 'text', 'default_value' => 'Why Choose Clarity?' ),
			array( 'key' => 'f_why_i', 'label' => 'Intro', 'name' => 'why_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.' ),
			array( 'key' => 'f_why_r', 'label' => 'Cards', 'name' => 'why_items', 'type' => 'repeater', 'button_label' => 'Add card', 'layout' => 'block', 'sub_fields' => array(
				array( 'key' => 'f_why_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'select', 'choices' => clarity_icon_choices(), 'wrapper' => array( 'width' => 30 ) ),
				array( 'key' => 'f_why_t', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => 70 ) ),
				array( 'key' => 'f_why_d', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2 ),
			) ),

			array( 'key' => 'f_team_tab', 'label' => 'Team', 'type' => 'tab' ),
			array( 'key' => 'f_team_h', 'label' => 'Heading', 'name' => 'team_heading', 'type' => 'text', 'default_value' => 'Meet Our Team' ),
			array( 'key' => 'f_team_t', 'label' => 'Text', 'name' => 'team_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => "Led by Warren Dryden, our dedicated team combines decades of experience with a passion for exceptional customer service. We're not just your supplier – we're your technology partner." ),
			array( 'key' => 'f_team_img', 'label' => 'Image', 'name' => 'team_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ),
			array( 'key' => 'f_team_bl', 'label' => 'Button label', 'name' => 'team_btn_label', 'type' => 'text', 'default_value' => 'Learn More', 'wrapper' => array( 'width' => 50 ) ),
			array( 'key' => 'f_team_bu', 'label' => 'Button URL', 'name' => 'team_btn_url', 'type' => 'text', 'default_value' => '/our-team/', 'wrapper' => array( 'width' => 50 ) ),

			array( 'key' => 'f_prod_tab', 'label' => 'Products', 'type' => 'tab' ),
			array( 'key' => 'f_prod_h', 'label' => 'Heading', 'name' => 'products_heading', 'type' => 'text', 'default_value' => 'Products & Services' ),
			array( 'key' => 'f_prod_i', 'label' => 'Intro', 'name' => 'products_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Comprehensive Sharp technology solutions designed to meet every business need. From multifunction printers to interactive displays and cloud services.' ),
			array( 'key' => 'f_prod_r', 'label' => 'Product cards', 'name' => 'products', 'type' => 'repeater', 'button_label' => 'Add product', 'layout' => 'block', 'sub_fields' => array(
				array( 'key' => 'f_prod_img', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => 30 ) ),
				array( 'key' => 'f_prod_t', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => 35 ) ),
				array( 'key' => 'f_prod_l', 'label' => 'Link', 'name' => 'link', 'type' => 'text', 'wrapper' => array( 'width' => 35 ) ),
				array( 'key' => 'f_prod_d', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2 ),
			) ),

			array( 'key' => 'f_tst_tab', 'label' => 'Testimonials', 'type' => 'tab' ),
			array( 'key' => 'f_tst_h', 'label' => 'Heading', 'name' => 'testimonials_heading', 'type' => 'text', 'default_value' => 'What Our Clients Say' ),
			array( 'key' => 'f_tst_i', 'label' => 'Intro', 'name' => 'testimonials_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => "Don't just take our word for it. Here's what our satisfied clients have to say about our service." ),
			array( 'key' => 'f_tst_r', 'label' => 'Testimonials', 'name' => 'testimonials', 'type' => 'repeater', 'button_label' => 'Add testimonial', 'layout' => 'block', 'sub_fields' => array(
				array( 'key' => 'f_tst_q', 'label' => 'Quote', 'name' => 'quote', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'f_tst_n', 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'f_tst_ro', 'label' => 'Role', 'name' => 'role', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'f_tst_ra', 'label' => 'Stars', 'name' => 'rating', 'type' => 'number', 'default_value' => 5, 'min' => 1, 'max' => 5, 'wrapper' => array( 'width' => 20 ) ),
			) ),

			array( 'key' => 'f_news_tab', 'label' => 'News', 'type' => 'tab' ),
			array( 'key' => 'f_news_h', 'label' => 'Heading', 'name' => 'news_heading', 'type' => 'text', 'default_value' => 'Latest News & Insights' ),
			array( 'key' => 'f_news_i', 'label' => 'Intro', 'name' => 'news_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Stay updated with the latest product releases, industry insights, and company news.' ),
		),
	) );

	/* ---------- GLOBAL / CONTACT (header + footer) ---------- */
	acf_add_local_field_group( array(
		'key'      => 'group_clarity_global',
		'title'    => 'Header & footer (all pages)',
		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'theme-content' ) ) ),
		'menu_order' => 1,
		'fields'   => array(
			array( 'key' => 'g_phone', 'label' => 'Phone', 'name' => 'phone', 'type' => 'text', 'default_value' => '0330 221 9131', 'wrapper' => array( 'width' => 33 ) ),
			array( 'key' => 'g_email', 'label' => 'Email', 'name' => 'email', 'type' => 'text', 'default_value' => 'glamorgan@clarity-copiers.co.uk', 'wrapper' => array( 'width' => 33 ) ),
			array( 'key' => 'g_addr', 'label' => 'Address', 'name' => 'address', 'type' => 'text', 'default_value' => '1 North Rd Bridgend Industrial Estate Bridgend CF31 3TP', 'wrapper' => array( 'width' => 34 ) ),
			array( 'key' => 'g_fabout', 'label' => 'Footer blurb', 'name' => 'footer_about', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Your trusted Sharp technology partner since 1995. Delivering exceptional managed print and document solutions with local, personal service.' ),
			array( 'key' => 'g_social', 'label' => 'Social links', 'name' => 'socials', 'type' => 'repeater', 'button_label' => 'Add link', 'layout' => 'table', 'sub_fields' => array(
				array( 'key' => 'g_soc_net', 'label' => 'Network', 'name' => 'network', 'type' => 'select', 'choices' => array( 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'x' => 'X / Twitter' ) ),
				array( 'key' => 'g_soc_url', 'label' => 'URL', 'name' => 'url', 'type' => 'text' ),
			) ),
		),
	) );
} );

/* Sanitise global contact values on save (they are printed site-wide, incl. JSON-LD and mail headers). */
add_filter( 'acf/update_value/name=phone', function ( $v ) { return preg_replace( '/[^0-9+ ()]/', '', (string) $v ); } );
add_filter( 'acf/update_value/name=email', function ( $v ) { $v = sanitize_email( (string) $v ); return is_email( $v ) ? $v : ''; } );
add_filter( 'acf/update_value/key=g_soc_url', function ( $v ) { return esc_url_raw( (string) $v ); } );
