<?php
/**
 * Inner-page builder: page hero + flexible "sections", model details, SEO.
 * Code-defined (version controlled) and exposed to the REST API.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Small helpers so field definitions stay readable. */
function cc_f( $key, $label, $name, $type, $extra = array() ) {
	return array_merge( array( 'key' => 'cc_' . $key, 'label' => $label, 'name' => $name, 'type' => $type ), $extra );
}
function cc_w( $n ) { return array( 'wrapper' => array( 'width' => $n ) ); }
function cc_bg( $k ) {
	return cc_f( $k, 'Background', 'bg', 'select', array( 'choices' => array( 'white' => 'White', 'beige' => 'Beige', 'dark' => 'Dark' ), 'default_value' => 'white', 'wrapper' => array( 'width' => 25 ) ) );
}
function cc_head( $k ) {
	return array(
		cc_f( $k . '_h', 'Heading', 'heading', 'text', cc_w( 50 ) ),
		cc_f( $k . '_i', 'Intro', 'intro', 'textarea', array( 'rows' => 2, 'wrapper' => array( 'width' => 50 ) ) ),
	);
}
function cc_layout( $name, $label, $fields ) {
	return array( 'key' => 'cc_l_' . $name, 'name' => $name, 'label' => $label, 'display' => 'block', 'sub_fields' => $fields );
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

	$icon_choices = function_exists( 'clarity_icon_choices' ) ? clarity_icon_choices() : array();
	$btn_repeater = cc_f( 'btns', 'Buttons', 'buttons', 'repeater', array(
		'layout' => 'table', 'button_label' => 'Add button', 'max' => 3,
		'sub_fields' => array(
			cc_f( 'btn_l', 'Label', 'label', 'text' ),
			cc_f( 'btn_u', 'URL', 'url', 'text' ),
			cc_f( 'btn_s', 'Style', 'style', 'select', array( 'choices' => array( 'red' => 'Red', 'outline' => 'Outline' ), 'default_value' => 'red' ) ),
		),
	) );

	$layouts = array(
		cc_layout( 'split', 'Image + text', array(
			cc_f( 'sp_img', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 30 ) ) ),
			cc_f( 'sp_side', 'Image side', 'side', 'select', array( 'choices' => array( 'left' => 'Left', 'right' => 'Right' ), 'wrapper' => array( 'width' => 20 ) ) ),
			cc_bg( 'sp_bg' ),
			cc_f( 'sp_fit', 'Image fit', 'fit', 'select', array( 'choices' => array( 'cover' => 'Fill (photo)', 'contain' => 'Fit (product/logo)' ), 'wrapper' => array( 'width' => 25 ) ) ),
			cc_f( 'sp_eb', 'Eyebrow', 'eyebrow', 'text', cc_w( 30 ) ),
			cc_f( 'sp_h', 'Heading', 'heading', 'text', cc_w( 70 ) ),
			cc_f( 'sp_t', 'Text', 'text', 'wysiwyg', array( 'media_upload' => 0, 'toolbar' => 'basic' ) ),
			$btn_repeater,
		) ),
		cc_layout( 'icon_cards', 'Icon cards', array_merge( cc_head( 'ic' ), array(
			cc_f( 'ic_cols', 'Columns', 'columns', 'select', array( 'choices' => array( '2' => '2', '3' => '3', '4' => '4' ), 'default_value' => '4', 'wrapper' => array( 'width' => 25 ) ) ),
			cc_bg( 'ic_bg' ),
			cc_f( 'ic_cards', 'Cards', 'cards', 'repeater', array( 'layout' => 'block', 'button_label' => 'Add card', 'sub_fields' => array(
				cc_f( 'ic_c_icon', 'Icon', 'icon', 'select', array( 'choices' => $icon_choices, 'allow_null' => 1, 'wrapper' => array( 'width' => 25 ) ) ),
				cc_f( 'ic_c_t', 'Title', 'title', 'text', cc_w( 75 ) ),
				cc_f( 'ic_c_d', 'Text', 'text', 'textarea', array( 'rows' => 2 ) ),
			) ) ),
		) ) ),
		cc_layout( 'link_cards', 'Link cards (image)', array_merge( cc_head( 'lc' ), array(
			cc_bg( 'lc_bg' ),
			cc_f( 'lc_cards', 'Cards', 'cards', 'repeater', array( 'layout' => 'block', 'button_label' => 'Add card', 'sub_fields' => array(
				cc_f( 'lc_c_img', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => 25 ) ) ),
				cc_f( 'lc_c_t', 'Title', 'title', 'text', cc_w( 25 ) ),
				cc_f( 'lc_c_u', 'Link', 'link', 'text', cc_w( 25 ) ),
				cc_f( 'lc_c_l', 'Link label', 'link_label', 'text', array( 'default_value' => 'Explore more', 'wrapper' => array( 'width' => 25 ) ) ),
				cc_f( 'lc_c_d', 'Text', 'text', 'textarea', array( 'rows' => 2 ) ),
			) ) ),
		) ) ),
		cc_layout( 'stats', 'Stats band', array(
			cc_f( 'st_items', 'Stats', 'items', 'repeater', array( 'layout' => 'table', 'max' => 4, 'sub_fields' => array(
				cc_f( 'st_v', 'Value', 'value', 'text' ),
				cc_f( 'st_l', 'Label', 'label', 'text' ),
			) ) ),
		) ),
		cc_layout( 'steps', 'Process steps', array_merge( cc_head( 'sx' ), array(
			cc_bg( 'sx_bg' ),
			cc_f( 'sx_items', 'Steps', 'steps', 'repeater', array( 'layout' => 'block', 'sub_fields' => array(
				cc_f( 'sx_t', 'Title', 'title', 'text' ),
				cc_f( 'sx_d', 'Text', 'text', 'textarea', array( 'rows' => 2 ) ),
			) ) ),
		) ) ),
		cc_layout( 'model_grid', 'Product model grid', array_merge( cc_head( 'mg' ), array(
			cc_bg( 'mg_bg' ),
			cc_f( 'mg_parent', 'Show models under', 'parent', 'post_object', array( 'post_type' => array( 'page' ), 'return_format' => 'id' ) ),
			cc_f( 'mg_range', 'Only this range (optional)', 'range', 'text', array( 'instructions' => 'e.g. "Desktop A4" or several separated by commas — leave blank for all' ) ),
		) ) ),
		cc_layout( 'team_grid', 'Team grid', array_merge( cc_head( 'tg' ), array(
			cc_f( 'tg_groups', 'Groups', 'groups', 'repeater', array( 'layout' => 'block', 'button_label' => 'Add group', 'sub_fields' => array(
				cc_f( 'tg_g_t', 'Group title', 'title', 'text' ),
				cc_f( 'tg_g_m', 'Members', 'members', 'repeater', array( 'layout' => 'table', 'button_label' => 'Add member', 'sub_fields' => array(
					cc_f( 'tg_m_p', 'Photo', 'photo', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail' ) ),
					cc_f( 'tg_m_n', 'Name', 'name', 'text' ),
					cc_f( 'tg_m_r', 'Role', 'role', 'text' ),
				) ) ),
			) ) ),
		) ) ),
		cc_layout( 'testimonials', 'Testimonials', array_merge( cc_head( 'ts' ), array(
			cc_bg( 'ts_bg' ),
			cc_f( 'ts_items', 'Testimonials', 'items', 'repeater', array( 'layout' => 'block', 'sub_fields' => array(
				cc_f( 'ts_q', 'Quote', 'quote', 'textarea', array( 'rows' => 3 ) ),
				cc_f( 'ts_n', 'Name', 'name', 'text', cc_w( 50 ) ),
				cc_f( 'ts_r', 'Role / company', 'role', 'text', cc_w( 50 ) ),
			) ) ),
		) ) ),
		cc_layout( 'gallery', 'Photo gallery', array_merge( cc_head( 'ga' ), array(
			cc_f( 'ga_imgs', 'Images', 'images', 'gallery', array( 'return_format' => 'id', 'preview_size' => 'thumbnail' ) ),
		) ) ),
		cc_layout( 'videos', 'Video library', array_merge( cc_head( 'vd' ), array(
			cc_f( 'vd_items', 'Videos', 'items', 'repeater', array( 'layout' => 'table', 'button_label' => 'Add video', 'sub_fields' => array(
				cc_f( 'vd_id', 'YouTube ID', 'youtube_id', 'text' ),
				cc_f( 'vd_t', 'Title', 'title', 'text' ),
			) ) ),
		) ) ),
		cc_layout( 'contact', 'Contact (offices, form, map)', array_merge( cc_head( 'ct' ), array(
			cc_f( 'ct_offices', 'Offices', 'offices', 'repeater', array( 'layout' => 'block', 'sub_fields' => array(
				cc_f( 'ct_o_n', 'Name', 'name', 'text', cc_w( 50 ) ),
				cc_f( 'ct_o_p', 'Phone', 'phone', 'text', cc_w( 25 ) ),
				cc_f( 'ct_o_e', 'Email', 'email', 'text', cc_w( 25 ) ),
				cc_f( 'ct_o_a', 'Address', 'address', 'textarea', array( 'rows' => 3, 'wrapper' => array( 'width' => 50 ) ) ),
				cc_f( 'ct_o_h', 'Opening hours', 'hours', 'textarea', array( 'rows' => 3, 'wrapper' => array( 'width' => 50 ) ) ),
			) ) ),
			cc_f( 'ct_form', 'Show enquiry form', 'show_form', 'true_false', array( 'default_value' => 1, 'ui' => 1, 'wrapper' => array( 'width' => 33 ) ) ),
			cc_f( 'ct_map', 'Show map', 'show_map', 'true_false', array( 'default_value' => 1, 'ui' => 1, 'wrapper' => array( 'width' => 33 ) ) ),
			cc_f( 'ct_mq', 'Map address', 'map_query', 'text', array( 'wrapper' => array( 'width' => 34 ) ) ),
			cc_f( 'ct_sc', 'Form shortcode (optional)', 'form_shortcode', 'text', array( 'instructions' => 'Contact Form 7 shortcode. Leave empty to use the "Website enquiry" form.' ) ),
		) ) ),
		cc_layout( 'cta', 'Call to action band', array(
			cc_f( 'cta_h', 'Heading', 'heading', 'text', cc_w( 50 ) ),
			cc_f( 'cta_t', 'Text', 'text', 'textarea', array( 'rows' => 2, 'wrapper' => array( 'width' => 50 ) ) ),
			$btn_repeater,
		) ),
		cc_layout( 'text', 'Text block', array(
			cc_f( 'tx_h', 'Heading', 'heading', 'text', cc_w( 75 ) ),
			cc_bg( 'tx_bg' ),
			cc_f( 'tx_t', 'Text', 'text', 'wysiwyg', array( 'media_upload' => 0 ) ),
		) ),
	);
	// Repeater sub-field keys must be unique per layout.
	foreach ( $layouts as &$l ) {
		foreach ( $l['sub_fields'] as &$f ) {
			if ( 'cc_btns' === $f['key'] ) {
				$p = 'cc_' . $l['name'] . '_';
				$f['key'] = $p . 'btns';
				foreach ( $f['sub_fields'] as &$sf ) { $sf['key'] = $p . substr( $sf['key'], 3 ); }
				unset( $sf );
			}
		}
		unset( $f );
	}
	unset( $l );

	acf_add_local_field_group( array(
		'key'          => 'group_cc_page',
		'title'        => 'Page content',
		'show_in_rest' => 1,
		'location'     => array(
			array(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
				array( 'param' => 'page_type', 'operator' => '!=', 'value' => 'front_page' ),
			),
		),
		'position'       => 'acf_after_title',
		'hide_on_screen' => array(),
		'fields'         => array(
			cc_f( 'ph_tab', 'Page hero', '', 'tab' ),
			cc_f( 'ph_sub', 'Hero subtitle', 'hero_subtitle', 'textarea', array( 'rows' => 2, 'wrapper' => array( 'width' => 60 ) ) ),
			cc_f( 'ph_img', 'Hero background image', 'hero_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'wrapper' => array( 'width' => 40 ) ) ),
			cc_f( 'sec_tab', 'Sections', '', 'tab' ),
			cc_f( 'sections', 'Sections', 'sections', 'flexible_content', array(
				'button_label' => 'Add section',
				'layouts'      => $layouts,
				'instructions' => 'Build the page from sections. If empty, the normal editor content is shown instead.',
			) ),
		),
	) );

	acf_add_local_field_group( array(
		'key'          => 'group_cc_model',
		'title'        => 'Model details',
		'show_in_rest' => 1,
		'position'     => 'acf_after_title',
		'location'     => array(
			array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'template-model.php' ) ),
		),
		'fields'       => array(
			cc_f( 'md_code', 'Model code', 'model_code', 'text', cc_w( 25 ) ),
			cc_f( 'md_range', 'Range', 'model_range', 'select', array( 'choices' => array( 'Desktop A4' => 'Desktop A4', 'A4 MFP' => 'A4 MFP', 'A4 Printer' => 'A4 Printer', 'A3 Office MFP' => 'A3 Office MFP', 'High-volume A3' => 'High-volume A3', 'Production' => 'Production' ), 'wrapper' => array( 'width' => 25 ) ) ),
			cc_f( 'md_mode', 'Colour', 'colour_mode', 'select', array( 'choices' => array( 'colour' => 'Colour', 'mono' => 'Black & white' ), 'wrapper' => array( 'width' => 25 ) ) ),
			cc_f( 'md_ppm', 'Speed (ppm)', 'speed_ppm', 'number', cc_w( 25 ) ),
			cc_f( 'md_paper', 'Max paper size', 'paper_size', 'select', array( 'choices' => array( 'A4' => 'A4', 'A3' => 'A3', 'SRA3' => 'SRA3' ), 'wrapper' => array( 'width' => 25 ) ) ),
			cc_f( 'md_fn', 'Functions', 'functions', 'checkbox', array( 'choices' => array( 'print' => 'Print', 'copy' => 'Copy', 'scan' => 'Scan', 'fax' => 'Fax' ), 'layout' => 'horizontal', 'wrapper' => array( 'width' => 75 ) ) ),
			cc_f( 'md_sum', 'Summary', 'summary', 'textarea', array( 'rows' => 3 ) ),
			cc_f( 'md_feat', 'Key features', 'features', 'repeater', array( 'layout' => 'table', 'button_label' => 'Add feature', 'sub_fields' => array( cc_f( 'md_f_t', 'Feature', 'text', 'text' ) ) ) ),
		),
	) );

} );
