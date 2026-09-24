<?php
/**
 * One-time seeding of ACF option content so the "Theme Content" screen
 * opens pre-filled with the approved homepage copy instead of blank fields.
 * Runs once (guarded by the clarity_seeded_v flag) then never again, so
 * later manual edits in wp-admin are preserved.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'acf/init', 'clarity_seed_content', 20 );

function clarity_seed_content() {
	if ( ! function_exists( 'update_field' ) ) { return; }

	$version = '3';
	if ( get_option( 'clarity_seeded_v' ) === $version ) { return; }

	// Media library attachment IDs uploaded for the theme.
	$img_team   = 49;
	$img_colour = 50;
	$img_mono   = 51;
	$img_bigpad = 52;

	$set = array(
		'hero_pill'          => '24/7 Remote Support Available',
		'hero_heading'       => "Empowering Productivity Through\nSharp Technology",
		'hero_text'          => 'Managed print and document solutions that save time, reduce waste and keep your business moving. Trusted by businesses across the UK for 30 years.',
		'hero_btn1_label'    => 'Get a Quote',
		'hero_btn1_url'      => '/contact/',
		'hero_btn2_label'    => 'Get Remote Support',
		'hero_btn2_url'      => '/support/',
		'hero_video'         => 45, // hero-clarity.mp4 attachment (from the XD mockup)

		'sectors_heading'    => 'Expertise Across Sectors',
		'sectors_intro'      => 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.',
		'sectors'            => array(
			array( 'icon' => 'legal',   'title' => 'Legal',          'description' => 'Data security and compliance solutions for law firms and legal practices.' ),
			array( 'icon' => 'hotel',   'title' => 'Hospitality',    'description' => 'Seamless, guest-focused technology experiences for hotels and venues.' ),
			array( 'icon' => 'estate',  'title' => 'Estate Agents',  'description' => 'Cloud solutions enabling on-the-go operations for property professionals.' ),
			array( 'icon' => 'council', 'title' => 'Local Councils', 'description' => 'Secure connectivity and communications for public sector organizations.' ),
		),

		'why_heading'        => 'Why Choose Clarity?',
		'why_intro'          => 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.',
		'why_items'          => array(
			array( 'icon' => 'clock',   'title' => 'Prompt Repairs',      'description' => 'Quick response times to minimize your business downtime' ),
			array( 'icon' => 'award',   'title' => '30 Years Experience', 'description' => 'Serving businesses since 1995 with unmatched expertise' ),
			array( 'icon' => 'pin',     'title' => 'Local Service',       'description' => 'Based locally with engineers ready to respond quickly' ),
			array( 'icon' => 'printer', 'title' => 'Sharp Main Dealer',   'description' => 'Official Sharp partner providing genuine products and support' ),
		),

		'team_heading'       => 'Meet Our Team',
		'team_text'          => "Led by Warren Dryden, our dedicated team combines decades of experience with a passion for exceptional customer service. We're not just your supplier – we're your technology partner.",
		'team_image'         => $img_team,
		'team_btn_label'     => 'Learn More',
		'team_btn_url'       => '/our-team/',

		'products_heading'   => 'Products & Services',
		'products_intro'     => 'Comprehensive Sharp technology solutions designed to meet every business need. From multifunction printers to interactive displays and cloud services.',
		'products'           => array(
			array( 'image' => $img_colour, 'title' => 'Colour MFPs',          'link' => '/colour-mfps/',   'description' => 'High-performance multifunction printers delivering vibrant colour documents' ),
			array( 'image' => $img_mono,   'title' => 'Mono MFPs',            'link' => '/mono-mfps/',     'description' => 'Cost-effective black and white printing solutions ideal for high-volume document workflows.' ),
			array( 'image' => $img_bigpad, 'title' => 'Interactive Displays', 'link' => '/sharp-big-pad/', 'description' => 'Sharp Big Pad touchscreen displays transform collaboration with intuitive' ),
		),

		'testimonials_heading' => 'What Our Clients Say',
		'testimonials_intro'   => "Don't just take our word for it. Here's what our satisfied clients have to say about our service.",
		'testimonials'         => array(
			array( 'quote' => '"Fantastic service! Would highly recommend. The team are knowledgeable and responsive to our needs."', 'name' => 'Sarah Johnson', 'role' => 'Operations Director', 'rating' => 5 ),
			array( 'quote' => '"We have used this company for over 10 years. Excellent support and they really understand our business."', 'name' => 'Michael Chen', 'role' => 'IT Manager', 'rating' => 5 ),
			array( 'quote' => '"Professional service from start to finish. The installation was seamless and the ongoing support is excellent."', 'name' => 'Emma Williams', 'role' => 'Finance Director', 'rating' => 5 ),
		),

		'news_heading'       => 'Latest News & Insights',
		'news_intro'         => 'Stay updated with the latest product releases, industry insights, and company news.',

		// Global / contact
		'phone'              => '0330 221 9131',
		'email'              => 'glamorgan@clarity-copiers.co.uk',
		'address'            => '1 North Rd Bridgend Industrial Estate Bridgend CF31 3TP',
		'footer_about'       => 'Your trusted Sharp technology partner since 1995. Delivering exceptional managed print and document solutions with local, personal service.',
		'footer_sectors'     => array(
			array( 'label' => 'Education' ), array( 'label' => 'Legal' ), array( 'label' => 'Healthcare' ),
			array( 'label' => 'Manufacturing' ), array( 'label' => 'Public Sector' ),
		),
		'socials'            => array(
			array( 'network' => 'facebook', 'url' => '#' ),
			array( 'network' => 'linkedin', 'url' => '#' ),
			array( 'network' => 'x',        'url' => '#' ),
		),
	);

	foreach ( $set as $name => $value ) {
		update_field( $name, $value, 'option' );
	}

	update_option( 'clarity_seeded_v', $version );
}
