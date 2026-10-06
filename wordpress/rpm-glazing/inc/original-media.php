<?php
/** Original RPM assets, imported once without rerunning the initial content seed. */
if (!defined('ABSPATH')) { exit; }

function rpm_brand_logo($location = 'header') {
    $field = $location === 'footer' ? 'footer_logo' : 'header_logo';
    $image = rpm_get_field($field, 'option');
    if ($image) {
        echo wp_get_attachment_image((int) $image, 'full', false, array(
            'class' => 'rpm-brand-image', 'alt' => 'RPM Glazing Systems',
            'loading' => 'eager', 'decoding' => 'async',
        ));
        return;
    }
    echo '<img class="rpm-brand-image" src="' . esc_url(get_template_directory_uri() . '/assets/images/rpm-logo-original.png') . '" width="592" height="237" alt="RPM Glazing Systems">';
}

function rpm_replace_reference_image($post_id, $field_key, $original_id, $old_filenames) {
    $current = (int) get_field($field_key, $post_id, false);
    $file = $current ? get_attached_file($current) : '';
    // Preserve custom images selected since the initial build.
    if (!$current || ($file && in_array(basename($file), $old_filenames, true))) {
        update_field($field_key, $original_id, $post_id);
    }
}

function rpm_import_original_media() {
    if (get_option('rpm_original_media_version') === '2026-09-07' || !current_user_can('edit_theme_options') || !function_exists('update_field')) {
        return;
    }
    $media = array(
        'logo' => array('rpm-logo-original.png', 'RPM official logo — original', 'RPM Glazing Systems'),
        'footer_logo' => array('rpm-logo-light-original.png', 'RPM official footer logo — original', 'RPM Glazing Systems'),
        'icon' => array('rpm-icon-original.png', 'RPM official site icon — original', 'RPM'),
        'volvo' => array('volvo-original.jpg', 'Volvo Bristol — original RPM photograph', 'Glazed facade at Volvo Bristol'),
        'terminal' => array('airport-original.jpg', 'Bristol Airport terminal — original RPM photograph', 'Bristol Airport terminal glazing and automatic entrance'),
        'facade' => array('project-april-12-original.jpg', 'RPM glazed facade installation — original', 'Aluminium glazed facade under installation'),
        'doors' => array('project-april-10-original.jpg', 'RPM aluminium doors installation — original', 'Aluminium glazed doors installed by RPM'),
    );
    $ids = array();
    foreach ($media as $name => $asset) {
        $ids[$name] = rpm_import_theme_asset($asset[0], $asset[1]);
        if (!$ids[$name]) { return; }
        update_post_meta($ids[$name], '_wp_attachment_image_alt', $asset[2]);
        update_post_meta($ids[$name], '_rpm_asset_source', 'https://www.rpmglazing.com/');
    }
    rpm_update_acf_fields('option', array(
        'field_rpm_header_logo' => $ids['logo'],
        'field_rpm_footer_logo' => $ids['footer_logo'],
    ));
    if (!get_option('site_icon')) { update_option('site_icon', $ids['icon']); }
    $home_id = (int) get_option('page_on_front');
    rpm_replace_reference_image($home_id, 'field_rpm_home_image', $ids['terminal'], array('terminal-hero.jpg'));
    $pages = array(
        'services' => 'volvo',
        'services/curtain-walling' => 'facade',
        'services/aluminium-windows' => 'facade',
        'services/doors-entrances' => 'terminal',
        'services/shopfronts' => 'volvo',
        'services/rooflights-aovs' => 'facade',
        'services/bespoke-glazing' => 'doors',
        'about-rpm' => 'volvo',
        'accreditations' => 'facade',
        'contact' => 'terminal',
    );
    foreach ($pages as $path => $asset) {
        $page = get_page_by_path($path, OBJECT, array('page'));
        if ($page) {
            rpm_replace_reference_image($page->ID, 'field_rpm_page_image', $ids[$asset], array('terminal-hero.jpg','cubex-bristol.jpg','bristol-airport.jpg','lister-hospital.jpg'));
        }
    }
    $volvo = get_page_by_path('volvo-bristol', OBJECT, array('rpm_project'));
    if ($volvo) {
        rpm_replace_reference_image($volvo->ID, 'field_rpm_project_image', $ids['volvo'], array('volvo-bristol.jpg'));
        $featured = get_post_thumbnail_id($volvo->ID);
        $featured_file = $featured ? get_attached_file($featured) : '';
        if (!$featured || ($featured_file && basename($featured_file) === 'volvo-bristol.jpg')) {
            set_post_thumbnail($volvo->ID, $ids['volvo']);
        }
    }
    update_option('rpm_original_media_version', '2026-09-07');
}
add_action('init', 'rpm_import_original_media', 101);

/** Replace every seeded screenshot crop with a standalone project-source image. */
function rpm_refine_reference_media() {
    if (get_option('rpm_reference_media_version') === '2' || !current_user_can('edit_theme_options') || !function_exists('update_field')) { return; }
    $assets = array(
        'bmw-cardiff' => array('bmw-original.jpg', 'BMW Cardiff — original MCS photograph', array('bmw-cardiff.jpg', 'portfolio-bmw-reference.jpg'), 'https://mcs-ltd.com/projects/bmw-cardiff/'),
        'bristol-airport' => array('bristol-carpark-original.jpg', 'Bristol Airport — original GOLDBECK photograph', array('bristol-airport.jpg', 'portfolio-airport-reference.jpg'), 'https://www.goldbeck.co.uk/projects/projects-detail/bristol-airport-ltd'),
        'cubex-bristol' => array('cubex-original.jpg', 'Cubex Skyline — original developer photograph', array('cubex-bristol.jpg', 'portfolio-cubex-reference.jpg'), 'https://cubexre.com/developments/skyline/'),
    );
    foreach ($assets as $slug => $asset) {
        $project = get_page_by_path($slug, OBJECT, array('rpm_project'));
        if (!$project) { continue; }
        $image_id = rpm_import_theme_asset($asset[0], $asset[1]);
        if (!$image_id) { return; }
        update_post_meta($image_id, '_wp_attachment_image_alt', get_the_title($project));
        update_post_meta($image_id, '_rpm_asset_source', $asset[3]);
        rpm_replace_reference_image($project->ID, 'field_rpm_project_image', $image_id, $asset[2]);
        $featured = get_post_thumbnail_id($project->ID);
        $file = $featured ? get_attached_file($featured) : '';
        if (!$featured || ($file && in_array(basename($file), $asset[2], true))) { set_post_thumbnail($project->ID, $image_id); }
    }
    $lister = rpm_import_theme_asset('lister-original.jpg', 'Lister Hospital — original NHS visualisation');
    if (!$lister) { return; }
    update_post_meta($lister, '_wp_attachment_image_alt', 'Architectural visualisation of the new Lister Hospital entrance');
    update_post_meta($lister, '_rpm_asset_source', 'https://www.enherts-tr.nhs.uk/hospitals/lister/a-new-welcome-transforming-the-entrance-to-lister-hospital/');
    rpm_replace_reference_image((int) get_option('page_on_front'), 'field_rpm_spotlight_image', $lister, array('lister-hospital.jpg'));
    update_option('rpm_reference_media_version', '2');
}
add_action('init', 'rpm_refine_reference_media', 102);
