<?php
/**
 * ACF field group for the front page, registered in code so it works with
 * ACF, ACF Pro or Secure Custom Fields without importing anything.
 *
 * Every field defaults to the original design copy, so the page renders
 * exactly as designed before anything is edited.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default content for each field, taken from the original HTML.
 */
function bgwc_defaults() {
	return array(
		'meta_title'       => 'Billy’s Gym & Wellness Centre CIC | Maesteg',
		'meta_description' => 'Billy’s Gym & Wellness Centre CIC, Maesteg. New website coming soon.',
		'brand_name'       => 'Billy’s.',
		'brand_tagline'    => 'Gym & Wellness Centre CIC',
		'status_text'      => 'New website coming soon',
		'eyebrow'          => 'Maesteg · South Wales',
		'heading_line_1'   => 'Move.',
		'heading_line_2'   => 'Connect.',
		'heading_subline'  => 'Join the movement.',
		'intro'            => 'Fitness, combat sports, holistic approaches to wellness & community, together in one place.',
		'cta_text'         => 'Join our opening list',
		'cta_url'          => 'https://form.jotform.com/261932568626063',
		'social_text'      => 'Follow Billy’s on Instagram ↗',
		'social_url'       => 'https://www.instagram.com/bgwc_maesteg/',
		'caption'          => 'A look inside our new space',
		'location_text'    => 'Billy’s Gym & Wellness Centre CIC · Maesteg',
		'hero_alt'         => 'Inside Billy’s Gym & Wellness Centre in Maesteg',
		'logo_alt'         => 'BGWC logo',
	);
}

/**
 * Read a front-page field, falling back to the design default when ACF is
 * missing or the field is empty.
 */
function bgwc_field( $name ) {
	$value = '';
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, (int) get_option( 'page_on_front' ) );
	}
	if ( '' === $value || null === $value || false === $value ) {
		$defaults = bgwc_defaults();
		$value    = isset( $defaults[ $name ] ) ? $defaults[ $name ] : '';
	}
	return $value;
}

/**
 * Image URL for an ACF image field, falling back to the bundled asset.
 */
function bgwc_image( $name, $fallback ) {
	if ( function_exists( 'get_field' ) ) {
		$id = get_field( $name, (int) get_option( 'page_on_front' ) );
		if ( $id ) {
			$url = wp_get_attachment_image_url( (int) $id, 'full' );
			if ( $url ) {
				return $url;
			}
		}
	}
	return get_theme_file_uri( 'assets/images/' . $fallback );
}

add_action( 'acf/init', 'bgwc_register_fields' );

function bgwc_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$d = bgwc_defaults();

	$text = function ( $key, $label, $instructions = '', $type = 'text' ) use ( $d ) {
		return array(
			'key'           => 'field_bgwc_' . $key,
			'label'         => $label,
			'name'          => $key,
			'type'          => $type,
			'instructions'  => $instructions,
			'default_value' => $d[ $key ],
			'placeholder'   => $d[ $key ],
		);
	};
	$tab = function ( $key, $label ) {
		return array(
			'key'       => 'field_bgwc_tab_' . $key,
			'label'     => $label,
			'type'      => 'tab',
			'placement' => 'top',
		);
	};
	$image = function ( $key, $label, $instructions ) {
		return array(
			'key'           => 'field_bgwc_' . $key,
			'label'         => $label,
			'name'          => $key,
			'type'          => 'image',
			'instructions'  => $instructions,
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'library'       => 'all',
		);
	};

	acf_add_local_field_group(
		array(
			'key'                   => 'group_bgwc_front_page',
			'title'                 => 'Homepage content',
			'fields'                => array(
				$tab( 'header', 'Header' ),
				$image( 'logo', 'Logo', 'Round logo, top left. Leave empty to use the built-in BGWC logo.' ),
				$text( 'logo_alt', 'Logo alt text' ),
				$text( 'brand_name', 'Brand name' ),
				$text( 'brand_tagline', 'Brand tagline' ),
				$text( 'status_text', 'Status pill', 'Top right badge. Hidden on mobile.' ),

				$tab( 'hero', 'Hero' ),
				$image( 'hero_image', 'Background photo', 'Full-screen background. Leave empty to use the built-in gym photo.' ),
				$text( 'hero_alt', 'Background photo alt text' ),
				$text( 'eyebrow', 'Eyebrow', 'Small text above the heading.' ),
				$text( 'heading_line_1', 'Heading line 1' ),
				$text( 'heading_line_2', 'Heading line 2' ),
				$text( 'heading_subline', 'Heading italic line' ),
				$text( 'intro', 'Intro', '', 'textarea' ),

				$tab( 'buttons', 'Buttons' ),
				$text( 'cta_text', 'Main button text' ),
				$text( 'cta_url', 'Main button link', '', 'url' ),
				$text( 'social_text', 'Second button text' ),
				$text( 'social_url', 'Second button link', '', 'url' ),

				$tab( 'footer', 'Footer' ),
				$text( 'caption', 'Caption', 'Bottom left.' ),
				$text( 'location_text', 'Location', 'Bottom right. Hidden on mobile.' ),

				$tab( 'seo', 'SEO' ),
				$text( 'meta_title', 'Browser title' ),
				$text( 'meta_description', 'Meta description', '', 'textarea' ),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'position'              => 'acf_after_title',
			'style'                 => 'default',
			'label_placement'       => 'left',
			'hide_on_screen'        => array( 'the_content', 'excerpt', 'discussion', 'comments', 'featured_image' ),
			'show_in_rest'          => 1,
		)
	);
}
