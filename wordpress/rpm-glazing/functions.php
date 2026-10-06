<?php
/**
 * RPM Glazing Systems theme functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('RPM_THEME_VERSION', '1.2.6');
define('RPM_SEED_VERSION', '1.1.0');

function rpm_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('responsive-embeds');
    register_nav_menus(array('primary' => __('Primary Navigation', 'rpm-glazing')));
}
add_action('after_setup_theme', 'rpm_theme_setup');

function rpm_assets() {
    wp_enqueue_style('rpm-glazing', get_stylesheet_uri(), array(), RPM_THEME_VERSION);
    wp_enqueue_style('rpm-readability', get_template_directory_uri() . '/assets/css/readability.css', array('rpm-glazing'), RPM_THEME_VERSION);
    wp_enqueue_script('rpm-glazing', get_template_directory_uri() . '/assets/js/site.js', array(), RPM_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'rpm_assets');

function rpm_register_project_type() {
    register_post_type('rpm_project', array(
        'labels' => array(
            'name' => __('Projects', 'rpm-glazing'),
            'singular_name' => __('Project', 'rpm-glazing'),
            'add_new_item' => __('Add New Project', 'rpm-glazing'),
            'edit_item' => __('Edit Project', 'rpm-glazing'),
        ),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'has_archive' => true,
        'rewrite' => array('slug' => 'projects'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
    ));
}
add_action('init', 'rpm_register_project_type');

function rpm_order_project_archive($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('rpm_project')) {
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts', 'rpm_order_project_archive');

function rpm_acf_options() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('RPM Site Settings', 'rpm-glazing'),
            'menu_title' => __('RPM Settings', 'rpm-glazing'),
            'menu_slug' => 'rpm-site-settings',
            'capability' => 'edit_theme_options',
            'redirect' => false,
            'position' => 61,
            'icon_url' => 'dashicons-admin-settings',
        ));
    }
}
add_action('acf/init', 'rpm_acf_options');

function rpm_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_rpm_settings',
        'title' => 'Global Site Settings',
        'fields' => array(
            array('key' => 'field_rpm_phone', 'label' => 'Phone', 'name' => 'phone', 'type' => 'text'),
            array('key' => 'field_rpm_header_logo', 'label' => 'Header Logo', 'name' => 'header_logo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium'),
            array('key' => 'field_rpm_footer_logo', 'label' => 'Footer Logo', 'name' => 'footer_logo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium'),
            array('key' => 'field_rpm_email', 'label' => 'Email', 'name' => 'email', 'type' => 'email'),
            array('key' => 'field_rpm_address', 'label' => 'Address', 'name' => 'address', 'type' => 'textarea', 'rows' => 4),
            array('key' => 'field_rpm_header_cta', 'label' => 'Header CTA Label', 'name' => 'header_cta_text', 'type' => 'text'),
            array('key' => 'field_rpm_header_cta_url', 'label' => 'Header / Default Enquiry Destination', 'name' => 'header_cta_url', 'type' => 'url', 'instructions' => 'Leave blank to use the Contact page.'),
            array('key' => 'field_rpm_call_label', 'label' => 'Header / Hero Phone Label', 'name' => 'call_label', 'type' => 'text', 'default_value' => 'Call RPM'),
            array('key' => 'field_rpm_enquiry_recipient', 'label' => 'Enquiry Recipient', 'name' => 'enquiry_recipient', 'type' => 'email'),
            array('key' => 'field_rpm_footer_legal', 'label' => 'Footer Legal Text', 'name' => 'footer_legal', 'type' => 'text'),
            array('key' => 'field_rpm_contact_kicker', 'label' => 'Contact Section Kicker', 'name' => 'contact_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_contact_heading', 'label' => 'Contact Section Heading', 'name' => 'contact_heading', 'type' => 'text'),
            array('key' => 'field_rpm_contact_intro', 'label' => 'Contact Section Introduction', 'name' => 'contact_intro', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_contact_phone_label', 'label' => 'Contact Phone Label', 'name' => 'contact_phone_label', 'type' => 'text'),
            array('key' => 'field_rpm_contact_location', 'label' => 'Contact Location Line', 'name' => 'contact_location_text', 'type' => 'text'),
            array('key' => 'field_rpm_form_submit_label', 'label' => 'Form Submit Label', 'name' => 'form_submit_label', 'type' => 'text'),
            array('key' => 'field_rpm_projects_archive_kicker', 'label' => 'Projects Archive Kicker', 'name' => 'projects_archive_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_projects_archive_heading', 'label' => 'Projects Archive Heading', 'name' => 'projects_archive_heading', 'type' => 'text'),
            array('key' => 'field_rpm_projects_archive_intro', 'label' => 'Projects Archive Introduction', 'name' => 'projects_archive_intro', 'type' => 'textarea', 'rows' => 3),
        ),
        'location' => array(array(array('param' => 'options_page', 'operator' => '==', 'value' => 'rpm-site-settings'))),
    ));

    acf_add_local_field_group(array(
        'key' => 'group_rpm_page',
        'title' => 'Page Content',
        'fields' => array(
            array('key' => 'field_rpm_page_kicker', 'label' => 'Hero Kicker', 'name' => 'hero_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_page_heading', 'label' => 'Hero Heading', 'name' => 'hero_heading', 'type' => 'text'),
            array('key' => 'field_rpm_page_intro', 'label' => 'Hero Introduction', 'name' => 'hero_intro', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_page_image', 'label' => 'Hero Image', 'name' => 'hero_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large'),
            array('key' => 'field_rpm_page_section_heading', 'label' => 'Main Section Heading', 'name' => 'section_heading', 'type' => 'text'),
            array('key' => 'field_rpm_page_section_kicker', 'label' => 'Main Section Kicker', 'name' => 'section_kicker', 'type' => 'text', 'default_value' => 'Built around your project'),
            array('key' => 'field_rpm_page_section_copy', 'label' => 'Main Section Copy', 'name' => 'section_copy', 'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0),
            array(
                'key' => 'field_rpm_page_highlights',
                'label' => 'Highlights',
                'name' => 'highlights',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Highlight',
                'sub_fields' => array(
                    array('key' => 'field_rpm_highlight_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_rpm_highlight_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3),
                    array('key' => 'field_rpm_highlight_link', 'label' => 'Linked Page', 'name' => 'link', 'type' => 'page_link', 'post_type' => array('page'), 'allow_archives' => 0),
                ),
            ),
            array('key' => 'field_rpm_page_cta_heading', 'label' => 'CTA Heading', 'name' => 'cta_heading', 'type' => 'text'),
            array('key' => 'field_rpm_page_cta_text', 'label' => 'CTA Text', 'name' => 'cta_text', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_rpm_page_cta_kicker', 'label' => 'CTA Kicker', 'name' => 'cta_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_page_cta_button', 'label' => 'CTA Button Label', 'name' => 'cta_button_label', 'type' => 'text'),
            array('key' => 'field_rpm_page_cta_phone', 'label' => 'CTA Phone Label', 'name' => 'cta_phone_label', 'type' => 'text'),
            array('key' => 'field_rpm_page_cta_url', 'label' => 'CTA Destination', 'name' => 'cta_url', 'type' => 'url', 'instructions' => 'Leave blank to use the global enquiry destination.'),
        ),
        'location' => array(array(
            array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
            array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
        )),
    ));

    acf_add_local_field_group(array(
        'key' => 'group_rpm_home',
        'title' => 'Homepage Sections',
        'fields' => array(
            array('key' => 'field_rpm_home_eyebrow', 'label' => 'Hero Eyebrow', 'name' => 'home_hero_eyebrow', 'type' => 'text'),
            array('key' => 'field_rpm_home_heading', 'label' => 'Hero Heading', 'name' => 'home_hero_heading', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_rpm_home_summary', 'label' => 'Hero Summary', 'name' => 'home_hero_summary', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_home_image', 'label' => 'Hero Image', 'name' => 'home_hero_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large'),
            array('key' => 'field_rpm_home_team_heading', 'label' => 'Hero Team Heading', 'name' => 'home_team_heading', 'type' => 'text'),
            array('key' => 'field_rpm_home_team_text', 'label' => 'Hero Team Text', 'name' => 'home_team_text', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_rpm_home_cta_heading', 'label' => 'Enquiry Strip Heading', 'name' => 'cta_heading', 'type' => 'text'),
            array('key' => 'field_rpm_home_cta_text', 'label' => 'Enquiry Strip Text', 'name' => 'cta_text', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_rpm_home_cta_kicker', 'label' => 'Enquiry Strip Kicker', 'name' => 'cta_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_home_cta_button', 'label' => 'Enquiry Strip Button Label', 'name' => 'cta_button_label', 'type' => 'text'),
            array('key' => 'field_rpm_home_cta_phone', 'label' => 'Enquiry Strip Phone Label', 'name' => 'cta_phone_label', 'type' => 'text'),
            array('key' => 'field_rpm_home_cta_url', 'label' => 'Enquiry Strip Destination', 'name' => 'cta_url', 'type' => 'url', 'instructions' => 'Leave blank to use the global enquiry destination.'),
            array('key' => 'field_rpm_home_proof_intro', 'label' => 'Proof Bar Introduction', 'name' => 'proof_intro', 'type' => 'text'),
            array(
                'key' => 'field_rpm_home_proof_items', 'label' => 'Proof Bar Items', 'name' => 'proof_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add Proof Item',
                'sub_fields' => array(
                    array('key' => 'field_rpm_home_proof_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text'),
                    array('key' => 'field_rpm_home_proof_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                ),
            ),
            array('key' => 'field_rpm_overview_kicker', 'label' => 'Overview Kicker', 'name' => 'overview_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_overview_heading', 'label' => 'Overview Heading', 'name' => 'overview_heading', 'type' => 'text'),
            array('key' => 'field_rpm_overview_left', 'label' => 'Overview Column One', 'name' => 'overview_left', 'type' => 'textarea', 'rows' => 4),
            array('key' => 'field_rpm_overview_right', 'label' => 'Overview Column Two', 'name' => 'overview_right', 'type' => 'textarea', 'rows' => 4),
            array('key' => 'field_rpm_overview_link_label', 'label' => 'Overview Link Label', 'name' => 'overview_link_label', 'type' => 'text'),
            array('key' => 'field_rpm_overview_link', 'label' => 'Overview Linked Page', 'name' => 'overview_link', 'type' => 'page_link', 'post_type' => array('page'), 'allow_archives' => 0),
            array('key' => 'field_rpm_services_kicker', 'label' => 'Services Kicker', 'name' => 'services_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_services_heading', 'label' => 'Services Heading', 'name' => 'services_heading', 'type' => 'text'),
            array(
                'key' => 'field_rpm_services', 'label' => 'Services', 'name' => 'services', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add Service',
                'sub_fields' => array(
                    array('key' => 'field_rpm_service_number', 'label' => 'Number', 'name' => 'number', 'type' => 'text'),
                    array('key' => 'field_rpm_service_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_rpm_service_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3),
                    array('key' => 'field_rpm_service_link', 'label' => 'Linked Page', 'name' => 'link', 'type' => 'page_link', 'post_type' => array('page'), 'allow_archives' => 0),
                ),
            ),
            array('key' => 'field_rpm_portfolio_heading', 'label' => 'Portfolio Heading', 'name' => 'portfolio_heading', 'type' => 'text'),
            array('key' => 'field_rpm_portfolio_intro', 'label' => 'Portfolio Introduction', 'name' => 'portfolio_intro', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_portfolio_kicker', 'label' => 'Portfolio Kicker', 'name' => 'portfolio_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_portfolio_button', 'label' => 'Portfolio Button Label', 'name' => 'portfolio_button_label', 'type' => 'text'),
            array('key' => 'field_rpm_spotlight_kicker', 'label' => 'Spotlight Kicker', 'name' => 'spotlight_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_spotlight_title', 'label' => 'Spotlight Heading', 'name' => 'spotlight_title', 'type' => 'text'),
            array('key' => 'field_rpm_spotlight_text', 'label' => 'Spotlight Text', 'name' => 'spotlight_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_spotlight_image', 'label' => 'Spotlight Image', 'name' => 'spotlight_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large'),
            array('key' => 'field_rpm_spotlight_caption', 'label' => 'Spotlight Image Caption', 'name' => 'spotlight_caption', 'type' => 'text', 'default_value' => 'Project visualisation'),
            array('key' => 'field_rpm_process_heading', 'label' => 'Process Heading', 'name' => 'process_heading', 'type' => 'text'),
            array('key' => 'field_rpm_process_intro', 'label' => 'Process Introduction', 'name' => 'process_intro', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_process_kicker', 'label' => 'Process Kicker', 'name' => 'process_kicker', 'type' => 'text'),
            array(
                'key' => 'field_rpm_process', 'label' => 'Process Steps', 'name' => 'process', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add Step',
                'sub_fields' => array(
                    array('key' => 'field_rpm_process_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_rpm_process_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3),
                ),
            ),
            array('key' => 'field_rpm_quality_heading', 'label' => 'Quality Heading', 'name' => 'quality_heading', 'type' => 'text'),
            array('key' => 'field_rpm_quality_text', 'label' => 'Quality Text', 'name' => 'quality_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_rpm_quality_kicker', 'label' => 'Quality Kicker', 'name' => 'quality_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_quality_link_label', 'label' => 'Quality Link Label', 'name' => 'quality_link_label', 'type' => 'text'),
            array('key' => 'field_rpm_quality_link', 'label' => 'Quality Linked Page', 'name' => 'quality_link', 'type' => 'page_link', 'post_type' => array('page'), 'allow_archives' => 0),
            array(
                'key' => 'field_rpm_quality_items', 'label' => 'Quality Metrics', 'name' => 'quality_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add Metric',
                'sub_fields' => array(
                    array('key' => 'field_rpm_quality_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text'),
                    array('key' => 'field_rpm_quality_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                ),
            ),
        ),
        'location' => array(array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'))),
    ));

    acf_add_local_field_group(array(
        'key' => 'group_rpm_project',
        'title' => 'Project Details',
        'fields' => array(
            array('key' => 'field_rpm_project_sector', 'label' => 'Sector', 'name' => 'sector', 'type' => 'text'),
            array('key' => 'field_rpm_project_location', 'label' => 'Location', 'name' => 'location', 'type' => 'text'),
            array('key' => 'field_rpm_project_summary', 'label' => 'Summary', 'name' => 'summary', 'type' => 'textarea', 'rows' => 4),
            array('key' => 'field_rpm_project_services', 'label' => 'Services Delivered', 'name' => 'services_text', 'type' => 'text'),
            array('key' => 'field_rpm_project_image', 'label' => 'Hero Image', 'name' => 'hero_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large'),
            array('key' => 'field_rpm_project_kicker', 'label' => 'Overview Kicker', 'name' => 'overview_kicker', 'type' => 'text', 'default_value' => 'Project overview'),
            array('key' => 'field_rpm_project_sector_label', 'label' => 'Sector Label', 'name' => 'sector_label', 'type' => 'text', 'default_value' => 'Sector'),
            array('key' => 'field_rpm_project_location_label', 'label' => 'Location Label', 'name' => 'location_label', 'type' => 'text', 'default_value' => 'Location'),
            array('key' => 'field_rpm_project_package_label', 'label' => 'Package Label', 'name' => 'package_label', 'type' => 'text', 'default_value' => 'Package'),
            array('key' => 'field_rpm_project_cta_heading', 'label' => 'CTA Heading', 'name' => 'cta_heading', 'type' => 'text', 'default_value' => 'Quick project enquiry'),
            array('key' => 'field_rpm_project_cta_text', 'label' => 'CTA Text', 'name' => 'cta_text', 'type' => 'textarea', 'rows' => 2),
            array('key' => 'field_rpm_project_cta_kicker', 'label' => 'CTA Kicker', 'name' => 'cta_kicker', 'type' => 'text', 'default_value' => 'Have a live project or tender?'),
            array('key' => 'field_rpm_project_cta_button', 'label' => 'CTA Button Label', 'name' => 'cta_button_label', 'type' => 'text', 'default_value' => 'Make an enquiry'),
            array('key' => 'field_rpm_project_cta_phone', 'label' => 'CTA Phone Label', 'name' => 'cta_phone_label', 'type' => 'text', 'default_value' => 'Speak directly to RPM'),
            array('key' => 'field_rpm_project_cta_url', 'label' => 'CTA Destination', 'name' => 'cta_url', 'type' => 'url', 'instructions' => 'Leave blank to use the global enquiry destination.'),
        ),
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'rpm_project'))),
    ));
}
add_action('acf/include_fields', 'rpm_register_acf_fields');

function rpm_get_field($name, $post_id = false, $fallback = '') {
    if (function_exists('get_field')) {
        $value = get_field($name, $post_id);
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }
    return $fallback;
}

function rpm_image_url($value, $size = 'full') {
    if (is_array($value) && !empty($value['url'])) {
        return $value['url'];
    }
    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, $size);
        return $url ? $url : '';
    }
    return is_string($value) ? $value : '';
}

function rpm_project_image_url($post_id, $size = 'large') {
    $acf_image = rpm_get_field('hero_image', $post_id);
    $url = rpm_image_url($acf_image, $size);
    if ($url) {
        return $url;
    }
    $featured = get_post_thumbnail_id($post_id);
    return $featured ? rpm_image_url($featured, $size) : '';
}

function rpm_project_card_image($post_id) {
    $image_id = (int) rpm_get_field('hero_image', $post_id, get_post_thumbnail_id($post_id));
    if (!$image_id) { return ''; }
    return wp_get_attachment_image($image_id, 'large', false, array(
        'alt' => '', 'loading' => 'lazy', 'decoding' => 'async',
        'sizes' => '(max-width: 760px) calc(100vw - 44px), (max-width: 1440px) 60vw, 1000px',
    ));
}

function rpm_phone_href($phone) {
    return 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
}

function rpm_mark_seed_pending() {
    update_option('rpm_seed_pending', 1);
}
add_action('after_switch_theme', 'rpm_mark_seed_pending');

function rpm_create_page($title, $slug, $parent = 0) {
    // Match the exact page at the intended hierarchy level. Looking up only a
    // child slug with get_page_by_path() can miss services/example and create a
    // duplicate example-2 page during a theme upgrade.
    $matches = get_posts(array(
        'post_type' => array('page'),
        'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
        'name' => $slug,
        'post_parent' => (int) $parent,
        'posts_per_page' => 1,
        'no_found_rows' => true,
    ));
    $existing = $matches ? $matches[0] : null;
    if ($existing) {
        return (int) $existing->ID;
    }
    return (int) wp_insert_post(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_parent' => $parent,
        'post_content' => '',
    ));
}

function rpm_import_theme_asset($filename, $title) {
    $existing = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'title' => $title, 'posts_per_page' => 1));
    if ($existing) {
        return (int) $existing[0]->ID;
    }
    $path = get_template_directory() . '/assets/images/' . $filename;
    if (!file_exists($path)) {
        return 0;
    }
    $contents = file_get_contents($path);
    $upload = wp_upload_bits($filename, null, $contents);
    if (!empty($upload['error'])) {
        return 0;
    }
    $filetype = wp_check_filetype($upload['file']);
    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'],
        'post_title' => $title,
        'post_status' => 'inherit',
    ), $upload['file']);
    if (!is_wp_error($attachment_id)) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
        return (int) $attachment_id;
    }
    return 0;
}

function rpm_update_acf_fields($post_id, $values) {
    if (!function_exists('update_field')) {
        return;
    }
    foreach ($values as $key => $value) {
        // Theme upgrades add missing defaults without overwriting content that an
        // editor has already changed in ACF.
        $existing = get_field($key, $post_id, false);
        if ($existing === false || $existing === null || $existing === '' || $existing === array()) {
            update_field($key, $value, $post_id);
        }
    }
}

function rpm_seed_site() {
    $seed_pending = (bool) get_option('rpm_seed_pending');
    $seed_current = get_option('rpm_theme_seeded_version') === RPM_SEED_VERSION;
    if ((!$seed_pending && $seed_current) || !current_user_can('switch_themes')) {
        return;
    }

    $images = array(
        'terminal' => rpm_import_theme_asset('airport-original.jpg', 'Bristol Airport terminal — original RPM photograph'),
        'lister' => rpm_import_theme_asset('lister-original.jpg', 'Lister Hospital — original NHS visualisation'),
        'volvo' => rpm_import_theme_asset('volvo-original.jpg', 'Volvo Bristol — original RPM photograph'),
        'airport' => rpm_import_theme_asset('bristol-carpark-original.jpg', 'Bristol Airport — original GOLDBECK photograph'),
        'bmw' => rpm_import_theme_asset('bmw-original.jpg', 'BMW Cardiff — original MCS photograph'),
        'cubex' => rpm_import_theme_asset('cubex-original.jpg', 'Cubex Skyline — original developer photograph'),
    );

    $home_id = rpm_create_page('Home', 'home');
    $services_id = rpm_create_page('Services', 'services');

    $service_data = array(
        'curtain-walling' => array('Curtain Walling', 'High-performance glazed facades engineered for commercial, healthcare and public buildings.', 'Engineered curtain walling for demanding commercial projects', 'From early design support to installation, RPM delivers curtain walling packages that balance architectural intent, thermal performance and dependable programme delivery.', array(
            array('Design & engineering', 'System selection, detailing and buildability support from the outset.'),
            array('Controlled manufacture', 'Precision fabrication and quality checks at our Bridgend facility.'),
            array('Specialist installation', 'Accredited teams delivering safely and efficiently on live sites.'),
        )),
        'aluminium-windows' => array('Aluminium Windows', 'Durable, thermally efficient window systems manufactured for demanding project specifications.', 'Aluminium windows built around performance', 'We manufacture and install commercial aluminium window systems with the thermal, acoustic and security performance required for modern building envelopes.', array(
            array('Thermal performance', 'Modern profiles, glazing and interfaces designed to meet project targets.'),
            array('Flexible configurations', 'Casement, tilt-and-turn, fixed and project-specific window arrangements.'),
            array('Complete delivery', 'Survey, manufacture, installation and handover through one accountable team.'),
        )),
        'doors-entrances' => array('Doors & Entrances', 'Aluminium doors, automatic entrances and integrated access solutions built for intensive use.', 'Welcoming, secure and dependable entrances', 'RPM supplies commercial door and entrance packages designed around access, traffic levels, security and the architectural requirements of each project.', array(
            array('Commercial doors', 'Robust aluminium doors for public, commercial and industrial settings.'),
            array('Automatic entrances', 'Accessible automated systems coordinated with controls and safety requirements.'),
            array('Integrated access', 'Interfaces planned with adjacent glazing, locking and access-control systems.'),
        )),
        'shopfronts' => array('Shopfronts', 'Bespoke retail facades that balance security, performance and a strong architectural identity.', 'Shopfront systems that make a confident first impression', 'From individual retail units to multi-site programmes, we deliver glazed shopfronts that bring together appearance, accessibility, security and long-term reliability.', array(
            array('Bespoke design', 'Framing, glass, doors and finishes coordinated to the brand and building.'),
            array('Secure specification', 'Laminated glazing, robust hardware and access requirements built into the package.'),
            array('Live environments', 'Careful programming and installation for occupied retail and commercial settings.'),
        )),
        'rooflights-aovs' => array('Rooflights & AOVs', 'Daylight and ventilation solutions coordinated into the wider building envelope.', 'Daylight, ventilation and smoke control', 'We deliver rooflights and automatic opening vents as coordinated components of the building envelope, supporting natural light, ventilation and smoke-control strategies.', array(
            array('Coordinated detailing', 'Interfaces with roofing and facade systems resolved before manufacture.'),
            array('Control integration', 'AOV equipment planned around the wider fire and building-management strategy.'),
            array('Testing & handover', 'Commissioning, certification and clear documentation at completion.'),
        )),
        'bespoke-glazing' => array('Bespoke Glazing', 'Project-specific aluminium and glass packages developed around complex design requirements.', 'Specialist glazing for distinctive projects', 'Where standard systems are not enough, RPM develops carefully engineered aluminium and glass solutions around the project geometry, performance brief and installation constraints.', array(
            array('Problem solving', 'Practical technical development for unusual interfaces and project conditions.'),
            array('Precision fabrication', 'Controlled manufacture for one-off and complex assemblies.'),
            array('Single-team delivery', 'Design, manufacture and installation managed as one coordinated package.'),
        )),
    );

    $service_ids = array();
    $service_index = 0;
    foreach ($service_data as $slug => $data) {
        $service_ids[$slug] = rpm_create_page($data[0], $slug, $services_id);
        if ($service_index > 0 && (int) get_post_field('menu_order', $service_ids[$slug]) === 0) {
            wp_update_post(array('ID' => $service_ids[$slug], 'menu_order' => $service_index));
        }
        $service_index++;
        $highlights = array();
        foreach ($data[4] as $item) {
            $highlights[] = array('title' => $item[0], 'text' => $item[1], 'link' => '');
        }
        rpm_update_acf_fields($service_ids[$slug], array(
            'field_rpm_page_kicker' => 'Commercial glazing service',
            'field_rpm_page_heading' => $data[2],
            'field_rpm_page_intro' => $data[1],
            'field_rpm_page_image' => $images['terminal'],
            'field_rpm_page_section_heading' => 'Built around your project',
            'field_rpm_page_section_copy' => '<p>' . esc_html($data[3]) . '</p><p>Our specialists work with contractors, architects and building owners from early review through technical development, manufacture, installation and handover.</p>',
            'field_rpm_page_highlights' => $highlights,
        'field_rpm_page_cta_heading' => 'Discuss your glazing requirements',
        'field_rpm_page_cta_text' => 'Share your programme, drawings or specification and our team will help identify the right route forward.',
            'field_rpm_page_cta_kicker' => 'Have a live project or tender?',
            'field_rpm_page_cta_button' => 'Make an enquiry',
            'field_rpm_page_cta_phone' => 'Speak directly to RPM',
        ));
    }

    $service_highlights = array();
    foreach ($service_data as $slug => $data) {
        $service_highlights[] = array('title' => $data[0], 'text' => $data[1], 'link' => get_permalink($service_ids[$slug]));
    }
    rpm_update_acf_fields($services_id, array(
        'field_rpm_page_kicker' => 'Our capabilities',
        'field_rpm_page_heading' => 'Commercial glazing services',
        'field_rpm_page_intro' => 'A complete design, manufacture and installation service for aluminium and glass building-envelope packages.',
        'field_rpm_page_image' => $images['terminal'],
        'field_rpm_page_section_heading' => 'One team from design to handover',
        'field_rpm_page_section_copy' => '<p>RPM supports projects across South Wales and throughout the UK. Our in-house capability brings design development, procurement, fabrication, installation and quality control together under one accountable team.</p>',
        'field_rpm_page_highlights' => $service_highlights,
        'field_rpm_page_cta_heading' => 'Need help defining the package?',
        'field_rpm_page_cta_text' => 'Send us your drawings, specification or early-stage requirements for a practical review.',
        'field_rpm_page_cta_kicker' => 'Have a live project or tender?',
        'field_rpm_page_cta_button' => 'Make an enquiry',
        'field_rpm_page_cta_phone' => 'Speak directly to RPM',
    ));

    $about_id = rpm_create_page('About RPM', 'about-rpm');
    rpm_update_acf_fields($about_id, array(
        'field_rpm_page_kicker' => 'One specialist team',
        'field_rpm_page_heading' => 'Glazing systems delivered with control and care',
        'field_rpm_page_intro' => 'RPM Glazing Systems is a Bridgend-based commercial glazing contractor supporting projects across South Wales and throughout the UK.',
        'field_rpm_page_image' => $images['cubex'],
        'field_rpm_page_section_heading' => 'Built around your project',
        'field_rpm_page_section_copy' => '<p>We combine technical expertise, controlled manufacture and experienced site installation to deliver dependable aluminium and glazing packages.</p><p>Our approach is collaborative and practical: understand the project, resolve details early, manufacture accurately and install safely.</p>',
        'field_rpm_page_highlights' => array(
            array('title' => 'Established 1970', 'text' => 'Decades of specialist glazing knowledge applied to modern construction.', 'link' => ''),
            array('title' => 'Bridgend facility', 'text' => 'Purpose-built manufacturing and project support in South Wales.', 'link' => ''),
            array('title' => 'Nationwide delivery', 'text' => 'Commercial, healthcare, automotive, transport and industrial projects.', 'link' => ''),
        ),
        'field_rpm_page_cta_heading' => 'Talk to the RPM team',
        'field_rpm_page_cta_text' => 'Tell us what you are planning and where the project needs support.',
        'field_rpm_page_cta_kicker' => 'Have a live project or tender?',
        'field_rpm_page_cta_button' => 'Make an enquiry',
        'field_rpm_page_cta_phone' => 'Speak directly to RPM',
    ));

    $accreditations_id = rpm_create_page('Accreditations', 'accreditations');
    rpm_update_acf_fields($accreditations_id, array(
        'field_rpm_page_kicker' => 'Quality without compromise',
        'field_rpm_page_heading' => 'Quality, safety and accreditations',
        'field_rpm_page_intro' => 'Structured quality management and safe working practices underpin every RPM project.',
        'field_rpm_page_image' => $images['airport'],
        'field_rpm_page_section_heading' => 'Dependable building-envelope performance',
        'field_rpm_page_section_copy' => '<p>Our systems, checks and installation processes help contractors reduce risk and achieve consistent results. Project-specific inspection, testing and handover records provide a clear line of assurance.</p>',
        'field_rpm_page_highlights' => array(
            array('title' => 'ISO 9001', 'text' => 'Quality-management processes supporting consistent project delivery.', 'link' => ''),
            array('title' => 'CSCS', 'text' => 'Accredited and appropriately trained installation teams.', 'link' => ''),
            array('title' => 'Health & Safety', 'text' => 'Project-specific planning, risk assessment and safe systems of work.', 'link' => ''),
            array('title' => 'Technical control', 'text' => 'Reviews, approvals and quality checks from design through handover.', 'link' => ''),
        ),
        'field_rpm_page_cta_heading' => 'Request compliance information',
        'field_rpm_page_cta_text' => 'Contact us for current certificates and project-specific quality documentation.',
        'field_rpm_page_cta_kicker' => 'Need project documentation?',
        'field_rpm_page_cta_button' => 'Make an enquiry',
        'field_rpm_page_cta_phone' => 'Speak directly to RPM',
    ));

    $contact_id = rpm_create_page('Contact', 'contact');
    rpm_update_acf_fields($contact_id, array(
        'field_rpm_page_kicker' => 'Start your enquiry',
        'field_rpm_page_heading' => 'Tell us about your glazing project',
        'field_rpm_page_intro' => 'Share the scope, drawings or programme and the RPM team will review what you need.',
        'field_rpm_page_image' => $images['lister'],
        'field_rpm_page_section_heading' => 'Speak to a specialist team',
        'field_rpm_page_section_copy' => '<p>Provide as much information as you have available. We will review the project requirements and respond with the next practical step.</p>',
    ));

    $legal_pages = array(
        'privacy-policy' => array(
            'Privacy Policy',
            'How we handle personal information',
            '<p>RPM Glazing Systems uses the information submitted through this website to respond to enquiries, prepare project communications and operate the website.</p><p>We do not sell personal information. To ask about the information we hold or request a correction, contact us using the details on this website.</p>',
        ),
        'cookie-policy' => array(
            'Cookie Policy',
            'How this website uses cookies',
            '<p>This website may use essential cookies required for security, administration and reliable operation. Optional analytics or marketing cookies should only be enabled where an appropriate consent mechanism is in place.</p>',
        ),
        'terms-of-use' => array(
            'Terms of Use',
            'Using the RPM Glazing Systems website',
            '<p>The information on this website is provided as a general introduction to RPM Glazing Systems and its services. Project requirements, specifications, programmes and commercial terms are confirmed separately in writing.</p>',
        ),
    );
    $legal_page_ids = array();
    foreach ($legal_pages as $legal_slug => $legal_data) {
        $legal_page_ids[$legal_slug] = rpm_create_page($legal_data[0], $legal_slug);
        rpm_update_acf_fields($legal_page_ids[$legal_slug], array(
            'field_rpm_page_kicker' => 'Website information',
            'field_rpm_page_heading' => $legal_data[1],
            'field_rpm_page_intro' => 'Information about this website and how RPM Glazing Systems handles your use of it.',
            'field_rpm_page_section_heading' => $legal_data[0],
            'field_rpm_page_section_copy' => $legal_data[2],
            'field_rpm_page_cta_heading' => 'Have a question for RPM?',
            'field_rpm_page_cta_text' => 'Contact our team if you need more information.',
            'field_rpm_page_cta_kicker' => 'Contact RPM',
            'field_rpm_page_cta_button' => 'Make an enquiry',
            'field_rpm_page_cta_phone' => 'Speak directly to RPM',
        ));
    }

    rpm_update_acf_fields($home_id, array(
        'field_rpm_home_eyebrow' => 'Commercial glazing contractor · South Wales · Nationwide',
        'field_rpm_home_heading' => "Complete commercial glazing\nfrom one specialist team",
        'field_rpm_home_summary' => 'Design, manufacture and installation of aluminium glazing packages for commercial construction projects.',
        'field_rpm_home_image' => $images['terminal'],
        'field_rpm_home_cta_heading' => 'Quick project enquiry',
        'field_rpm_home_cta_text' => 'Our commercial glazing team will review the requirements and help you identify the right route forward.',
        'field_rpm_home_cta_kicker' => 'Have a live project or tender?',
        'field_rpm_home_cta_button' => 'Make an enquiry',
        'field_rpm_home_cta_phone' => 'Speak directly to RPM',
        'field_rpm_home_team_heading' => 'One team',
        'field_rpm_home_team_text' => "Design · Manufacture\nInstallation",
        'field_rpm_home_proof_intro' => 'Commercial glazing expertise from our purpose-built facility in Bridgend.',
        'field_rpm_home_proof_items' => array(
            array('value' => '1970', 'label' => 'Established'),
            array('value' => 'ISO 9001', 'label' => 'Quality assured'),
            array('value' => 'CSCS', 'label' => 'Accredited installers'),
            array('value' => 'UK-wide', 'label' => 'Project delivery'),
        ),
        'field_rpm_overview_kicker' => 'Built around your project',
        'field_rpm_overview_heading' => 'Business and service overview',
        'field_rpm_overview_left' => 'RPM Glazing Systems designs, manufactures and installs complete aluminium glazing packages for commercial construction projects.',
        'field_rpm_overview_right' => 'Based in Bridgend, we support contractors, architects and building owners across South Wales and deliver projects throughout the UK.',
        'field_rpm_overview_link_label' => 'How we deliver projects',
        'field_rpm_overview_link' => get_permalink($about_id),
        'field_rpm_services_kicker' => 'Our capabilities',
        'field_rpm_services_heading' => 'Commercial glazing services',
        'field_rpm_services' => array_values(array_map(function($slug, $data) use ($service_ids) {
            static $number = 0;
            $number++;
            return array('number' => str_pad((string) $number, 2, '0', STR_PAD_LEFT), 'title' => $data[0], 'description' => $data[1], 'link' => get_permalink($service_ids[$slug]));
        }, array_keys($service_data), $service_data)),
        'field_rpm_portfolio_heading' => 'Completed project portfolio',
        'field_rpm_portfolio_intro' => 'Explore commercial glazing projects delivered across transport, healthcare, automotive and industrial environments.',
        'field_rpm_portfolio_kicker' => 'Selected work',
        'field_rpm_portfolio_button' => 'Explore projects',
        'field_rpm_spotlight_kicker' => 'Recently secured · Healthcare',
        'field_rpm_spotlight_title' => 'Creating a new welcome for Lister Hospital',
        'field_rpm_spotlight_text' => 'A coordinated commercial glazing package for the hospital’s new main entrance building in Stevenage.',
        'field_rpm_spotlight_image' => $images['lister'],
        'field_rpm_process_heading' => 'Project delivery process',
        'field_rpm_process_intro' => 'RPM stays involved from initial review and design development through manufacture, installation and handover.',
        'field_rpm_process_kicker' => 'A controlled process',
        'field_rpm_process' => array(
            array('title' => 'Design input', 'text' => 'Early engagement to improve buildability, performance and value.'),
            array('title' => 'Technical development', 'text' => 'Coordinated drawings, system selection and project planning.'),
            array('title' => 'Manufacture', 'text' => 'Precision fabrication and quality control at our Bridgend facility.'),
            array('title' => 'Installation', 'text' => 'Safe, programmed installation by experienced accredited teams.'),
            array('title' => 'Testing & handover', 'text' => 'Thorough inspection, documentation and a confident handover.'),
        ),
        'field_rpm_quality_heading' => 'Quality, safety and accreditations',
        'field_rpm_quality_text' => 'Our quality management and installation processes help contractors reduce risk and deliver dependable building-envelope performance.',
        'field_rpm_quality_kicker' => 'Quality without compromise',
        'field_rpm_quality_link_label' => 'Explore our standards',
        'field_rpm_quality_link' => get_permalink($accreditations_id),
        'field_rpm_quality_items' => array(
            array('value' => 'ISO', 'label' => '9001 certified quality management'),
            array('value' => 'CSCS', 'label' => 'Accredited site installation teams'),
            array('value' => 'H&S', 'label' => 'Project-specific risk assessment'),
            array('value' => '50+', 'label' => 'Years of specialist industry experience'),
        ),
    ));

    $project_data = array(
        array('Volvo, Bristol', 'volvo-bristol', 'Commercial / Automotive', 'Bristol', 'A high-performance glazed facade for a landmark automotive facility.', 'Curtain walling · Windows · Entrances', 'volvo'),
        array('Bristol Airport', 'bristol-airport', 'Transport', 'Bristol', 'Commercial glazing and entrance systems delivered for a demanding live transport environment.', 'Commercial glazing · Entrance systems', 'airport'),
        array('BMW, Cardiff', 'bmw-cardiff', 'Commercial / Automotive', 'Cardiff', 'A crisp aluminium and glass envelope supporting a premium automotive showroom.', 'Glazed facade · Aluminium systems', 'bmw'),
        array('Cubex, Bristol', 'cubex-bristol', 'Industrial', 'Bristol', 'Windows, doors and glazed screens for a modern commercial and industrial development.', 'Windows · Doors · Glazed screens', 'cubex'),
    );
    $project_ids = array();
    foreach ($project_data as $project_index => $project) {
        // Passing an array prevents get_page_by_path() from matching media attachments
        // whose filenames share a project slug.
        $existing = get_page_by_path($project[1], OBJECT, array('rpm_project'));
        $project_id = $existing ? (int) $existing->ID : (int) wp_insert_post(array(
            'post_type' => 'rpm_project', 'post_status' => 'publish', 'post_title' => $project[0], 'post_name' => $project[1],
            'post_content' => '<p>' . esc_html($project[4]) . '</p><p>RPM coordinated the glazing package from technical review through manufacture, installation, inspection and handover.</p>',
            'post_excerpt' => $project[4], 'menu_order' => $project_index,
        ));
        if ($project_id) {
            wp_update_post(array('ID' => $project_id, 'menu_order' => $project_index));
            $project_ids[] = $project_id;
        }
        if ($images[$project[6]]) {
            set_post_thumbnail($project_id, $images[$project[6]]);
        }
        rpm_update_acf_fields($project_id, array(
            'field_rpm_project_sector' => $project[2],
            'field_rpm_project_location' => $project[3],
            'field_rpm_project_summary' => $project[4],
            'field_rpm_project_services' => $project[5],
            'field_rpm_project_image' => $images[$project[6]],
        ));
    }

    rpm_update_acf_fields('option', array(
        'field_rpm_phone' => '01656 724704',
        'field_rpm_email' => 'info@rpmglazing.com',
        'field_rpm_address' => "14 Millers Avenue\nBrynmenyn Industrial Estate\nBridgend, CF32 9TD",
        'field_rpm_header_cta' => 'Make an enquiry',
        'field_rpm_enquiry_recipient' => 'info@rpmglazing.com',
        'field_rpm_footer_legal' => 'RPM Shopfront Manufacturers Ltd trading as RPM Glazing Systems',
        'field_rpm_contact_kicker' => 'Start your enquiry',
        'field_rpm_contact_heading' => 'Tell us about your glazing project',
        'field_rpm_contact_intro' => 'Share the scope, drawings or programme and the RPM team will review what you need.',
        'field_rpm_contact_phone_label' => 'Prefer to speak now?',
        'field_rpm_contact_location' => 'Bridgend, South Wales · Projects delivered nationwide',
        'field_rpm_form_submit_label' => 'Send project enquiry',
        'field_rpm_projects_archive_kicker' => 'Completed work',
        'field_rpm_projects_archive_heading' => 'Commercial glazing projects',
        'field_rpm_projects_archive_intro' => 'Explore building-envelope and entrance projects delivered for transport, healthcare, automotive, commercial and industrial environments.',
    ));

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    update_option('blogname', 'RPM Glazing Systems');
    update_option('blogdescription', 'Commercial glazing design, manufacture and installation');
    if (!empty($legal_page_ids['privacy-policy'])) {
        update_option('wp_page_for_privacy_policy', $legal_page_ids['privacy-policy']);
    }

    $menu_name = 'Primary Navigation';
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_result = $menu ? (int) $menu->term_id : wp_create_nav_menu($menu_name);
    $menu_id = is_wp_error($menu_result) ? 0 : (int) $menu_result;
    if ($menu_id && !wp_get_nav_menu_items($menu_id)) {
        $links = array(
            array('Services', get_permalink($services_id)),
            array('Projects', get_post_type_archive_link('rpm_project')),
            array('About', get_permalink($about_id)),
        );
        foreach ($links as $link) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => $link[0], 'menu-item-url' => $link[1], 'menu-item-status' => 'publish', 'menu-item-type' => 'custom',
            ));
        }
    }
    $locations = get_theme_mod('nav_menu_locations', array());
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);

    flush_rewrite_rules();
    $seed_complete = $home_id && $services_id && $about_id && $accreditations_id && $contact_id && $menu_id;
    $seed_complete = $seed_complete && count(array_filter($images)) === count($images);
    $seed_complete = $seed_complete && count(array_filter($service_ids)) === count($service_data);
    $seed_complete = $seed_complete && count(array_filter($legal_page_ids)) === count($legal_pages);
    $seed_complete = $seed_complete && count(array_unique($project_ids)) === count($project_data);
    if ($seed_complete) {
        delete_option('rpm_seed_pending');
        update_option('rpm_theme_seeded_version', RPM_SEED_VERSION);
    } else {
        update_option('rpm_seed_pending', 1);
    }
}
add_action('init', 'rpm_seed_site', 99);

function rpm_enquiry_result($status) {
    $messages = array(
        'sent' => 'Thank you — your enquiry has been sent.',
        'failed' => 'The enquiry could not be sent. Your entries are still here. Please try again or call RPM.',
        'invalid' => 'Please complete all required fields with a valid email address.',
        'rate' => 'Please wait a minute before sending another enquiry.',
        'security' => 'This form has expired. Please copy your project details, reload the page and try again.',
    );
    if (isset($_POST['rpm_async']) && sanitize_text_field(wp_unslash($_POST['rpm_async'])) === '1') {
        wp_send_json(array('status' => $status, 'message' => $messages[$status] ?? $messages['failed']));
    }
    $return_url = preg_replace('/#.*$/', '', wp_get_referer() ?: home_url('/contact/'));
    wp_safe_redirect(add_query_arg('enquiry', $status, $return_url) . '#enquiry');
    exit;
}

function rpm_handle_enquiry() {
    if (!isset($_POST['rpm_enquiry_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rpm_enquiry_nonce'])), 'rpm_enquiry')) {
        rpm_enquiry_result('security');
    }
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $telephone = isset($_POST['telephone']) ? sanitize_text_field(wp_unslash($_POST['telephone'])) : '';
    $location = isset($_POST['project_location']) ? sanitize_text_field(wp_unslash($_POST['project_location'])) : '';
    $service = isset($_POST['required_service']) ? sanitize_text_field(wp_unslash($_POST['required_service'])) : '';
    $description = isset($_POST['project_description']) ? sanitize_textarea_field(wp_unslash($_POST['project_description'])) : '';
    $website = isset($_POST['website']) ? sanitize_text_field(wp_unslash($_POST['website'])) : '';
    if ($website !== '') {
        rpm_enquiry_result('sent');
    }
    if (!$name || !is_email($email) || !$location || !$service || !$description) {
        rpm_enquiry_result('invalid');
    }
    $remote_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $rate_key = 'rpm_enquiry_' . md5(strtolower($email) . '|' . $remote_address);
    if (get_transient($rate_key)) {
        rpm_enquiry_result('rate');
    }
    set_transient($rate_key, 1, MINUTE_IN_SECONDS);
    $recipient = rpm_get_field('enquiry_recipient', 'option', get_option('admin_email'));
    $subject = sprintf('New project enquiry from %s', $name);
    $message = "Name: {$name}\nCompany: {$company}\nEmail: {$email}\nTelephone: {$telephone}\nProject location: {$location}\nRequired service: {$service}\n\nProject description:\n{$description}";
    $sent = wp_mail($recipient, $subject, $message, array('Reply-To: ' . $name . ' <' . $email . '>'));
    rpm_enquiry_result($sent ? 'sent' : 'failed');
}
add_action('admin_post_nopriv_rpm_enquiry', 'rpm_handle_enquiry');
add_action('admin_post_rpm_enquiry', 'rpm_handle_enquiry');

function rpm_body_classes($classes) {
    $classes[] = 'rpm-theme';
    return $classes;
}
add_filter('body_class', 'rpm_body_classes');

/** Optional page sections: a detail list and numbered steps, shown only when filled. */
function rpm_register_page_section_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_rpm_page_sections',
        'title' => 'Additional Page Sections',
        'menu_order' => 10,
        'fields' => array(
            array('key' => 'field_rpm_sections_detail_tab', 'label' => 'Detail List', 'type' => 'tab'),
            array('key' => 'field_rpm_detail_kicker', 'label' => 'Detail Kicker', 'name' => 'detail_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_detail_heading', 'label' => 'Detail Heading', 'name' => 'detail_heading', 'type' => 'text'),
            array('key' => 'field_rpm_detail_text', 'label' => 'Detail Text', 'name' => 'detail_text', 'type' => 'textarea', 'rows' => 2),
            array(
                'key' => 'field_rpm_detail_items', 'label' => 'Detail Items', 'name' => 'detail_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add Item',
                'instructions' => 'Leave empty to hide this section.',
                'sub_fields' => array(
                    array('key' => 'field_rpm_detail_item_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                ),
            ),
            array('key' => 'field_rpm_sections_steps_tab', 'label' => 'Steps', 'type' => 'tab'),
            array('key' => 'field_rpm_steps_kicker', 'label' => 'Steps Kicker', 'name' => 'steps_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_steps_heading', 'label' => 'Steps Heading', 'name' => 'steps_heading', 'type' => 'text'),
            array('key' => 'field_rpm_steps_intro', 'label' => 'Steps Introduction', 'name' => 'steps_intro', 'type' => 'textarea', 'rows' => 2),
            array(
                'key' => 'field_rpm_steps', 'label' => 'Steps', 'name' => 'steps', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add Step',
                'instructions' => 'Leave empty to hide this section.',
                'sub_fields' => array(
                    array('key' => 'field_rpm_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                    array('key' => 'field_rpm_step_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 2),
                ),
            ),
        ),
        'location' => array(array(
            array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
            array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
        )),
    ));

    acf_add_local_field_group(array(
        'key' => 'group_rpm_home_trade',
        'title' => 'Homepage Trade Callout',
        'menu_order' => 10,
        'fields' => array(
            array('key' => 'field_rpm_trade_callout_kicker', 'label' => 'Callout Kicker', 'name' => 'trade_callout_kicker', 'type' => 'text'),
            array('key' => 'field_rpm_trade_callout_text', 'label' => 'Callout Text', 'name' => 'trade_callout_text', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Shown under the services grid. Leave empty to hide.'),
            array('key' => 'field_rpm_trade_callout_link_label', 'label' => 'Link Label', 'name' => 'trade_callout_link_label', 'type' => 'text'),
            array('key' => 'field_rpm_trade_callout_link', 'label' => 'Linked Page', 'name' => 'trade_callout_link', 'type' => 'page_link', 'post_type' => array('page'), 'allow_archives' => 0),
        ),
        'location' => array(array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'))),
    ));
}
add_action('acf/include_fields', 'rpm_register_page_section_fields');

/** Add the Manufacture Only service page once; existing editor content is never overwritten. */
function rpm_seed_manufacture_only() {
    if (get_option('rpm_manufacture_only_release') === '1' || !current_user_can('edit_theme_options') || !function_exists('update_field')) { return; }
    $services = get_page_by_path('services');
    if (!$services) { return; }
    $page_id = rpm_create_page('Manufacture Only', 'manufacture-only', $services->ID);
    if (!$page_id) { return; }
    if ((int) get_post_field('menu_order', $page_id) === 0) {
        wp_update_post(array('ID' => $page_id, 'menu_order' => 7));
    }

    $hero = get_posts(array(
        'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids',
        'meta_query' => array(array('key' => '_wp_attached_file', 'value' => 'project-april-12-original', 'compare' => 'LIKE')),
    ));
    $intro = 'Supply-only fabrication of curtain walling, windows, doors and shopfronts from our Bridgend facility, for general builders, contractors and installation-only companies.';
    rpm_update_acf_fields($page_id, array(
        'field_rpm_page_kicker' => 'Manufacture-only service',
        'field_rpm_page_heading' => 'Aluminium glazing manufactured for your installation team',
        'field_rpm_page_intro' => $intro,
        'field_rpm_page_image' => $hero ? (int) $hero[0] : 0,
        'field_rpm_page_section_kicker' => 'Trade manufacturing',
        'field_rpm_page_section_heading' => 'Our manufacturing, your installation',
        'field_rpm_page_section_copy' => '<p>Not every project needs a full design-and-install package. RPM’s manufacture-only service gives general builders, main contractors and installation-only companies access to the same controlled fabrication we use on our own commercial projects.</p><p>Send us your drawings, schedules or survey sizes and we will review the requirement, confirm the specification and manufacture to an agreed programme, ready for your own team to install.</p><p>We work mainly across South Wales and the South West, and can supply further afield depending on the job.</p>',
        'field_rpm_page_highlights' => array(
            array('title' => 'General builders', 'text' => 'Commercial-grade aluminium glazing for new-build, extension and refurbishment work, without subcontracting the installation.', 'link' => ''),
            array('title' => 'Installation-only companies', 'text' => 'Frames, doors and screens fabricated to your sizes, so your fitters can concentrate on site.', 'link' => ''),
            array('title' => 'Main contractors', 'text' => 'A dependable manufacturing partner for glazing packages you prefer to install with your own teams.', 'link' => ''),
        ),
        'field_rpm_detail_kicker' => 'Supply-only range',
        'field_rpm_detail_heading' => 'What we manufacture',
        'field_rpm_detail_text' => 'Manufactured in aluminium to your drawings or sizes, with the same quality checks as our full-service projects.',
        'field_rpm_detail_items' => array_map(function ($label) { return array('label' => $label); }, array('Curtain walling', 'Aluminium windows', 'Doors & entrances', 'Shopfronts', 'Glazed screens', 'Bespoke aluminium & glass')),
        'field_rpm_steps_kicker' => 'How it works',
        'field_rpm_steps_heading' => 'From enquiry to finished frames',
        'field_rpm_steps_intro' => 'A clear, controlled route from your drawings to manufactured items ready for your installers.',
        'field_rpm_steps' => array(
            array('title' => 'Send your enquiry', 'text' => 'Share drawings, schedules or survey sizes along with your programme.'),
            array('title' => 'Review & quotation', 'text' => 'We check the requirement, confirm the system and specification, and quote.'),
            array('title' => 'Sizes signed off', 'text' => 'Final sizes and details are confirmed with you before production begins.'),
            array('title' => 'Manufacture', 'text' => 'Precision fabrication and quality control at our Bridgend facility.'),
            array('title' => 'Ready for your team', 'text' => 'Finished items are checked and released for your installers.'),
        ),
        'field_rpm_page_cta_kicker' => 'Trade and supply-only enquiries',
        'field_rpm_page_cta_heading' => 'Need glazing manufactured for your next project?',
        'field_rpm_page_cta_text' => 'Send your drawings, schedules or sizes and our team will come back to you with a quotation.',
        'field_rpm_page_cta_button' => 'Make an enquiry',
        'field_rpm_page_cta_phone' => 'Speak directly to RPM',
    ));

    // The kicker field has a default value, which rpm_update_acf_fields() reads as already set.
    if (!metadata_exists('post', $page_id, 'section_kicker')) {
        update_field('field_rpm_page_section_kicker', 'Trade manufacturing', $page_id);
    }

    // List the page on the Services overview alongside the other service cards.
    $permalink = get_permalink($page_id);
    $cards = get_field('field_rpm_page_highlights', $services->ID, false) ?: array();
    $listed = false;
    foreach ($cards as $card) {
        if (untrailingslashit((string) ($card['field_rpm_highlight_link'] ?? '')) === untrailingslashit($permalink)) { $listed = true; }
    }
    if (!$listed) {
        $cards[] = array('field_rpm_highlight_title' => 'Manufacture Only', 'field_rpm_highlight_text' => $intro, 'field_rpm_highlight_link' => $permalink);
        update_field('field_rpm_page_highlights', $cards, $services->ID);
    }

    // Add the page under Services in the primary navigation.
    $locations = get_nav_menu_locations();
    $menu_id = isset($locations['primary']) ? (int) $locations['primary'] : 0;
    if ($menu_id) {
        $items = wp_get_nav_menu_items($menu_id) ?: array();
        $parent = 0;
        foreach ($items as $item) {
            if ((int) $item->object_id === $page_id) { $parent = -1; break; }
            if (!$item->menu_item_parent && untrailingslashit($item->url) === untrailingslashit(get_permalink($services))) { $parent = (int) $item->ID; }
        }
        if ($parent > 0) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => 'Manufacture Only', 'menu-item-status' => 'publish', 'menu-item-parent-id' => $parent,
                'menu-item-type' => 'post_type', 'menu-item-object' => 'page', 'menu-item-object-id' => $page_id,
            ));
        }
    }
    update_option('rpm_manufacture_only_release', '1');
}
add_action('init', 'rpm_seed_manufacture_only', 114);

/**
 * Focus the Manufacture Only page on the two audiences the client named and
 * signpost it from the homepage. Text is only replaced while it still matches
 * the first release, so editor changes are kept.
 */
function rpm_seed_manufacture_only_trade() {
    if (get_option('rpm_manufacture_only_release') !== '1' || get_option('rpm_manufacture_only_trade_release') === '1' || !current_user_can('edit_theme_options') || !function_exists('update_field')) { return; }
    $page = get_page_by_path('services/manufacture-only');
    $home_id = (int) get_option('page_on_front');
    if (!$page || !$home_id) { return; }

    $old_intro = 'Supply-only fabrication of curtain walling, windows, doors and shopfronts from our Bridgend facility, for general builders, contractors and installation-only companies.';
    $new_intro = 'Supply-only fabrication of curtain walling, windows, doors and shopfronts from our Bridgend facility, for general builders and installation-only companies.';
    if (get_field('field_rpm_page_intro', $page->ID, false) === $old_intro) {
        update_field('field_rpm_page_intro', $new_intro, $page->ID);
    }

    $copy = (string) get_field('field_rpm_page_section_copy', $page->ID, false);
    $old_phrase = 'gives general builders, main contractors and installation-only companies access';
    if (strpos($copy, $old_phrase) !== false) {
        update_field('field_rpm_page_section_copy', str_replace($old_phrase, 'gives general builders and installation-only companies access', $copy), $page->ID);
    }

    $cards = get_field('field_rpm_page_highlights', $page->ID, false) ?: array();
    foreach ($cards as $index => $card) {
        if (($card['field_rpm_highlight_title'] ?? '') === 'Main contractors') {
            $cards[$index]['field_rpm_highlight_title'] = 'Made to your programme';
            $cards[$index]['field_rpm_highlight_text'] = 'Manufactured to your drawings or survey sizes and scheduled around your installation programme.';
            update_field('field_rpm_page_highlights', $cards, $page->ID);
            break;
        }
    }

    $services = get_page_by_path('services');
    $service_cards = $services ? (get_field('field_rpm_page_highlights', $services->ID, false) ?: array()) : array();
    foreach ($service_cards as $index => $card) {
        if (($card['field_rpm_highlight_text'] ?? '') === $old_intro) {
            $service_cards[$index]['field_rpm_highlight_text'] = 'Supply-only aluminium glazing manufactured for general builders and installation-only companies.';
            update_field('field_rpm_page_highlights', $service_cards, $services->ID);
            break;
        }
    }

    rpm_update_acf_fields($home_id, array(
        'field_rpm_trade_callout_kicker' => 'For the trade',
        'field_rpm_trade_callout_text' => 'General builder or installation-only company? We also manufacture aluminium glazing for your own team to install.',
        'field_rpm_trade_callout_link_label' => 'Manufacture-only service',
        'field_rpm_trade_callout_link' => $page->ID,
    ));
    update_option('rpm_manufacture_only_trade_release', '1');
}
add_action('init', 'rpm_seed_manufacture_only_trade', 115);

require_once get_template_directory() . '/inc/original-media.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/inner-photography.php';
