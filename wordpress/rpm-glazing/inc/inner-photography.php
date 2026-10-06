<?php
/** Source-recorded photographs for the approved inner-page photo pass. */
if (!defined('ABSPATH')) { exit; }

function rpm_register_photography_fields() {
    acf_add_local_field_group(array(
        'key' => 'group_rpm_photography', 'title' => 'Project Photography',
        'fields' => array(
            array('key' => 'field_rpm_banner_image', 'label' => 'Project Banner Image', 'name' => 'banner_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large', 'instructions' => 'Project page only. Does not change the homepage or project cards. Use the largest available original photograph.'),
            array('key' => 'field_rpm_banner_position', 'label' => 'Banner Focal Point', 'name' => 'banner_position', 'type' => 'select', 'choices' => array('center center' => 'Centre', 'left center' => 'Left', 'right center' => 'Right', 'center top' => 'Top', 'center bottom' => 'Bottom'), 'default_value' => 'center center'),
            array('key' => 'field_rpm_banner_source', 'label' => 'Banner Photo Source', 'name' => 'banner_source', 'type' => 'url'),
            array('key' => 'field_rpm_detail_photo', 'label' => 'Detail Photograph', 'name' => 'detail_photo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'large'),
            array('key' => 'field_rpm_detail_photo_caption', 'label' => 'Detail Photo Caption', 'name' => 'detail_photo_caption', 'type' => 'text'),
            array('key' => 'field_rpm_detail_photo_source', 'label' => 'Detail Photo Source', 'name' => 'detail_photo_source', 'type' => 'url'),
            array('key' => 'field_rpm_project_gallery', 'label' => 'Additional Project Photographs', 'name' => 'project_gallery', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add Photograph', 'sub_fields' => array(
                array('key' => 'field_rpm_gallery_image', 'label' => 'Photograph', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'required' => 1),
                array('key' => 'field_rpm_gallery_caption', 'label' => 'Caption', 'name' => 'caption', 'type' => 'text'),
                array('key' => 'field_rpm_gallery_source', 'label' => 'Source URL', 'name' => 'source', 'type' => 'url'),
            )),
        ),
        'location' => array(
            array(array('param' => 'post_type', 'operator' => '==', 'value' => 'rpm_project')),
        ),
    ));
}
add_action('acf/include_fields', 'rpm_register_photography_fields');

function rpm_photo_caption($kind = 'detail', $post_id = false) {
    $caption = rpm_get_field($kind . '_photo_caption', $post_id);
    if (!$caption) { return; }
    $source = rpm_get_field($kind . '_photo_source', $post_id);
    echo '<p class="rpm-photo-caption">' . esc_html($caption);
    if ($source) { echo ' <a href="' . esc_url($source) . '">Photo source</a>'; }
    echo '</p>';
}

function rpm_seed_inner_photography() {
    if (get_option('rpm_inner_photography_release') === '1' || !current_user_can('edit_theme_options') || !function_exists('update_field')) { return; }
    // User requested publication after the source/rights limitations were disclosed.
    // This records provenance and user direction, not independent licence clearance.
    $assets = array(
        'volvo' => array('volvo-interior-detail.jpg', 'Volvo Bristol showroom interior', 'https://mcs-ltd.com/projects/volvo-bristol/', 'Project photography sourced from MCS Group; reuse rights not independently verified.'),
        'bmw' => array('bmw-glazing-detail.jpg', 'BMW Cardiff showroom glazing', 'https://mcs-ltd.com/projects/bmw-cardiff/', 'Project photography sourced from MCS Group; reuse rights not independently verified.'),
        'skyline' => array('skyline-entrance-detail.jpg', 'Skyline Bristol glazed entrance and stairwell', 'https://www.skyline-bristol.com/#gallery', 'Project photography sourced from Skyline Bristol; reuse rights not independently verified.'),
        'airport' => array('airport-stair-detail.jpg', 'Bristol Airport car-park stair-tower glazing', 'https://www.goldbeck.co.uk/projects/projects-detail/bristol-airport-ltd', 'Project photography sourced from GOLDBECK; reuse rights not independently verified.'),
    );
    $ids = array();
    foreach ($assets as $key => $asset) {
        $ids[$key] = rpm_import_theme_asset($asset[0], $asset[1]);
        if (!$ids[$key]) { return; }
        update_post_meta($ids[$key], '_wp_attachment_image_alt', $asset[1]);
        update_post_meta($ids[$key], '_rpm_asset_source', $asset[2]);
        update_post_meta($ids[$key], '_rpm_asset_reuse_notes', $asset[3]);
    }
    $projects = array('volvo-bristol' => 'volvo', 'bmw-cardiff' => 'bmw', 'cubex-bristol' => 'skyline', 'bristol-airport' => 'airport');
    foreach ($projects as $slug => $key) {
        $project = get_page_by_path($slug, OBJECT, array('rpm_project'));
        if ($project && !get_field('detail_photo', $project->ID, false)) {
            update_field('field_rpm_detail_photo', $ids[$key], $project->ID);
            update_field('field_rpm_detail_photo_caption', $assets[$key][1] . '.', $project->ID);
            update_field('field_rpm_detail_photo_source', $assets[$key][2], $project->ID);
        }
    }
    update_option('rpm_inner_photography_release', '1');
}
add_action('init', 'rpm_seed_inner_photography', 112);

/** Add galleries without overwriting editor selections or changing project cards. */
function rpm_seed_project_galleries() {
    if (get_option('rpm_project_gallery_release') === '1' || !current_user_can('edit_theme_options') || !function_exists('update_field')) { return; }
    $mcs_volvo = 'https://mcs-ltd.com/projects/volvo-bristol/';
    $mcs_bmw = 'https://mcs-ltd.com/projects/bmw-cardiff/';
    $skyline = 'https://www.skyline-bristol.com/#gallery';
    $airport = 'https://www.goldbeck.co.uk/projects/projects-detail/bristol-airport-ltd';
    $projects = array(
        'volvo-bristol' => array(
            'banner' => array('volvo-original.jpg', 'Volvo Bristol glazed showroom exterior', 'https://www.rpmglazing.com/'),
            'position' => 'right center',
            'gallery' => array(
                array('volvo-exterior-gallery.jpg', 'Volvo Bristol showroom exterior.', $mcs_volvo),
                array('volvo-reception-gallery.jpg', 'Reception area inside Volvo Bristol.', $mcs_volvo),
            ),
        ),
        'bmw-cardiff' => array(
            'banner' => array('bmw-original.jpg', 'BMW Cardiff showroom exterior', $mcs_bmw),
            'position' => 'center center',
            'gallery' => array(
                array('bmw-reception-gallery.jpg', 'BMW Cardiff drive-in service reception.', 'https://mdgarchitects.co.uk/bmw-cardiff/'),
                array('bmw-mezzanine-gallery.jpg', 'BMW Cardiff mezzanine lounge and glass balustrade.', $mcs_bmw),
            ),
        ),
        'cubex-bristol' => array(
            'banner' => array('cubex-original.jpg', 'Skyline Bristol unit 05 exterior', 'https://cubexre.com/developments/skyline/'),
            'position' => 'left center',
            'gallery' => array(
                array('skyline-exterior-gallery.jpg', 'Completed industrial units at Skyline Bristol.', $skyline),
                array('skyline-warehouse-gallery.jpg', 'Warehouse interior at Skyline Bristol.', $skyline),
            ),
        ),
        'bristol-airport' => array(
            'banner' => array('airport-entrance-banner.jpg', 'Bristol Airport multi-storey car-park entrance and facade', $airport),
            'position' => 'right center',
            'gallery' => array(
                array('bristol-carpark-original.jpg', 'Bristol Airport multi-storey car-park exterior.', $airport),
                array('airport-facade-gallery.jpg', 'Perforated facade panels at the Bristol Airport car park.', $airport),
            ),
        ),
    );
    foreach ($projects as $slug => $data) {
        $project = get_page_by_path($slug, OBJECT, array('rpm_project'));
        if (!$project) { continue; }
        if (!get_field('banner_image', $project->ID, false)) {
            $asset = $data['banner'];
            $id = $slug === 'bristol-airport' ? rpm_import_theme_asset($asset[0], $asset[1]) : (int) get_field('hero_image', $project->ID, false);
            if (!$id) { $id = rpm_import_theme_asset($asset[0], $asset[1]); }
            if (!$id) { return; }
            update_post_meta($id, '_wp_attachment_image_alt', $asset[1]);
            update_post_meta($id, '_rpm_asset_source', $asset[2]);
            if ($slug === 'bristol-airport') { update_post_meta($id, '_rpm_asset_reuse_notes', 'Exact-project photograph added at user direction for staging. Reuse rights not independently verified; review before launch.'); }
            update_field('field_rpm_banner_image', $id, $project->ID);
            update_field('field_rpm_banner_position', $data['position'], $project->ID);
            update_field('field_rpm_banner_source', $asset[2], $project->ID);
        }
        if (!get_field('project_gallery', $project->ID, false)) {
            $rows = array();
            foreach ($data['gallery'] as $asset) {
                $id = $asset[0] === 'bristol-carpark-original.jpg' ? (int) get_field('hero_image', $project->ID, false) : 0;
                if (!$id) { $id = rpm_import_theme_asset($asset[0], $asset[1]); }
                if (!$id) { return; }
                update_post_meta($id, '_wp_attachment_image_alt', rtrim($asset[1], '.'));
                update_post_meta($id, '_rpm_asset_source', $asset[2]);
                update_post_meta($id, '_rpm_asset_reuse_notes', 'Exact-project photograph added at user direction for staging. Reuse rights not independently verified; review before launch.');
                $rows[] = array('field_rpm_gallery_image' => $id, 'field_rpm_gallery_caption' => $asset[1], 'field_rpm_gallery_source' => $asset[2]);
            }
            update_field('field_rpm_project_gallery', $rows, $project->ID);
        }
    }
    update_option('rpm_project_gallery_release', '1');
}
add_action('init', 'rpm_seed_project_galleries', 113);
