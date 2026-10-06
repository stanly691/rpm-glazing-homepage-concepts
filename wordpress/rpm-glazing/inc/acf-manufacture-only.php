<?php
/**
 * ACF field group: Manufacture Only page.
 *
 * Every piece of copy on template-manufacture-only.php is read from these
 * fields. The default values hold the approved launch copy, so a new page
 * using the template opens in wp-admin pre-filled and only needs "Update".
 *
 * Uses only field types available in free ACF (no repeaters), so it works
 * with or without ACF Pro.
 *
 * Load from functions.php:
 *     require_once get_template_directory() . '/inc/acf-manufacture-only.php';
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a title + text group used for numbered cards and process steps.
 */
function rpm_mo_card_group( $key, $name, $label, $title, $text ) {
	return array(
		'key'        => 'field_mo_' . $key,
		'label'      => $label,
		'name'       => $name,
		'type'       => 'group',
		'layout'     => 'block',
		'sub_fields' => array(
			array(
				'key'           => 'field_mo_' . $key . '_title',
				'label'         => 'Title',
				'name'          => 'title',
				'type'          => 'text',
				'default_value' => $title,
			),
			array(
				'key'           => 'field_mo_' . $key . '_text',
				'label'         => 'Text',
				'name'          => 'text',
				'type'          => 'textarea',
				'rows'          => 2,
				'new_lines'     => '',
				'default_value' => $text,
			),
		),
	);
}

function rpm_mo_text( $key, $label, $default, $type = 'text', $extra = array() ) {
	return array_merge(
		array(
			'key'           => 'field_mo_' . $key,
			'label'         => $label,
			'name'          => 'mo_' . $key,
			'type'          => $type,
			'default_value' => $default,
		),
		'textarea' === $type ? array( 'rows' => 3, 'new_lines' => '' ) : array(),
		$extra
	);
}

function rpm_mo_tab( $key, $label ) {
	return array(
		'key'       => 'field_mo_tab_' . $key,
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'top',
	);
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_rpm_manufacture_only',
				'title'                 => 'Manufacture Only page',
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'template-manufacture-only.php',
						),
					),
				),
				'position'              => 'acf_after_title',
				'style'                 => 'default',
				'hide_on_screen'        => array( 'the_content' ),
				'fields'                => array(

					// Hero.
					rpm_mo_tab( 'hero', 'Hero' ),
					array(
						'key'           => 'field_mo_hero_image',
						'label'         => 'Hero image',
						'name'          => 'mo_hero_image',
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'instructions'  => 'Falls back to the featured image when empty. A photo of the Bridgend workshop works best here.',
					),
					rpm_mo_text( 'hero_eyebrow', 'Eyebrow', 'Manufacture-only service' ),
					rpm_mo_text( 'hero_title', 'Heading', 'Aluminium glazing manufactured for your installation team' ),
					rpm_mo_text( 'hero_text', 'Summary', 'Supply-only fabrication of curtain walling, windows, doors and shopfronts from our Bridgend facility, for general builders, contractors and installation-only companies.', 'textarea' ),

					// Introduction.
					rpm_mo_tab( 'intro', 'Introduction' ),
					rpm_mo_text( 'intro_eyebrow', 'Eyebrow', 'Trade manufacturing' ),
					rpm_mo_text( 'intro_title', 'Heading', 'Our manufacturing, your installation' ),
					rpm_mo_text(
						'intro_body',
						'Body',
						"<p>Not every project needs a full design-and-install package. RPM's manufacture-only service gives general builders, main contractors and installation-only companies access to the same controlled fabrication we use on our own commercial projects.</p>\n<p>Send us your drawings, schedules or survey sizes and we will review the requirement, confirm the specification and manufacture to an agreed programme, ready for your own team to install.</p>\n<p>We work mainly across South Wales and the South West, and can supply further afield depending on the job.</p>",
						'wysiwyg',
						array(
							'tabs'         => 'all',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						)
					),

					// Who it is for.
					rpm_mo_tab( 'audience', 'Who it is for' ),
					rpm_mo_card_group( 'audience_1', 'mo_audience_1', 'Card 1', 'General builders', 'Commercial-grade aluminium glazing for new-build, extension and refurbishment work, without subcontracting the installation.' ),
					rpm_mo_card_group( 'audience_2', 'mo_audience_2', 'Card 2', 'Installation-only companies', 'Frames, doors and screens fabricated to your sizes, so your fitters can concentrate on site.' ),
					rpm_mo_card_group( 'audience_3', 'mo_audience_3', 'Card 3', 'Main contractors', 'A dependable manufacturing partner for glazing packages you prefer to install with your own teams.' ),

					// Products.
					rpm_mo_tab( 'products', 'What we manufacture' ),
					rpm_mo_text( 'products_eyebrow', 'Eyebrow', 'Supply-only range' ),
					rpm_mo_text( 'products_title', 'Heading', 'What we manufacture' ),
					rpm_mo_text( 'products_text', 'Text', 'Manufactured in aluminium to your drawings or sizes, with the same quality checks as our full-service projects.', 'textarea' ),
					rpm_mo_text(
						'products_list',
						'Products',
						"Curtain walling\nAluminium windows\nDoors & entrances\nShopfronts\nGlazed screens\nBespoke aluminium & glass",
						'textarea',
						array(
							'rows'         => 7,
							'instructions' => 'One product per line.',
						)
					),

					// Process.
					rpm_mo_tab( 'process', 'How it works' ),
					rpm_mo_text( 'process_eyebrow', 'Eyebrow', 'How it works' ),
					rpm_mo_text( 'process_title', 'Heading', 'From enquiry to finished frames' ),
					rpm_mo_text( 'process_text', 'Text', 'A clear, controlled route from your drawings to manufactured items ready for your installers.', 'textarea' ),
					rpm_mo_card_group( 'step_1', 'mo_step_1', 'Step 1', 'Send your enquiry', 'Share drawings, schedules or survey sizes along with your programme.' ),
					rpm_mo_card_group( 'step_2', 'mo_step_2', 'Step 2', 'Review & quotation', 'We check the requirement, confirm the system and specification, and quote.' ),
					rpm_mo_card_group( 'step_3', 'mo_step_3', 'Step 3', 'Sizes signed off', 'Final sizes and details are confirmed with you before production begins.' ),
					rpm_mo_card_group( 'step_4', 'mo_step_4', 'Step 4', 'Manufacture', 'Precision fabrication and quality control at our Bridgend facility.' ),
					rpm_mo_card_group( 'step_5', 'mo_step_5', 'Step 5', 'Ready for your team', 'Finished items are checked and released for your installers.' ),

					// Enquiry strip.
					rpm_mo_tab( 'cta', 'Enquiry strip' ),
					rpm_mo_text( 'cta_eyebrow', 'Eyebrow', 'Trade and supply-only enquiries' ),
					rpm_mo_text( 'cta_title', 'Heading', 'Need glazing manufactured for your next project?' ),
					rpm_mo_text( 'cta_text', 'Text', 'Send your drawings, schedules or sizes and our team will come back to you with a quotation.', 'textarea' ),
					rpm_mo_text( 'cta_button_label', 'Button label', 'Make an enquiry' ),
					rpm_mo_text( 'cta_button_link', 'Button link', '/contact/', 'text', array( 'instructions' => 'A site path such as /contact/, or a full URL.' ) ),
					rpm_mo_text( 'cta_phone_label', 'Phone label', 'Speak directly to RPM' ),
					rpm_mo_text( 'cta_phone', 'Phone number', '01656 724704' ),
					rpm_mo_text( 'cta_email', 'Email address', 'info@rpmglazing.com', 'email' ),
				),
			)
		);
	}
);
