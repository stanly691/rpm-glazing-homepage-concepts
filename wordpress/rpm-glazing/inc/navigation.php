<?php
if (!defined('ABSPATH')) { exit; }

/** Ordinary navigation links with separate accessible submenu disclosures. */
class RPM_Navigation_Walker extends Walker_Nav_Menu {
    private $submenu_parent_id = 0;

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        parent::start_el($output, $item, $depth, $args, $id);
        if ($depth === 0 && in_array('menu-item-has-children', (array) $item->classes, true)) {
            $this->submenu_parent_id = (int) $item->ID;
            $output .= '<button class="submenu-toggle" type="button" aria-expanded="false" aria-controls="rpm-submenu-' . $this->submenu_parent_id . '" aria-label="' . esc_attr(sprintf('Show %s pages', wp_strip_all_tags($item->title))) . '"><img class="submenu-icon" src="' . esc_url(get_theme_file_uri('assets/images/chevron-down.svg')) . '" width="14" height="14" alt="" aria-hidden="true"></button>';
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu" id="rpm-submenu-' . $this->submenu_parent_id . '">';
    }
}

/** Populate missing navigation links once; leave existing editor choices intact. */
function rpm_complete_navigation() {
    if (get_option('rpm_navigation_release') === '1' || !current_user_can('edit_theme_options')) { return; }
    $locations = get_nav_menu_locations();
    $menu_id = isset($locations['primary']) ? (int) $locations['primary'] : 0;
    if (!$menu_id) { return; }
    $items = wp_get_nav_menu_items($menu_id) ?: array();
    $urls = array();
    foreach ($items as $item) { $urls[untrailingslashit($item->url)] = (int) $item->ID; }
    $add = function ($title, $url, $parent = 0, $post = null) use ($menu_id, &$urls) {
        $key = untrailingslashit($url);
        if (isset($urls[$key])) { return $urls[$key]; }
        $data = array('menu-item-title' => $title, 'menu-item-url' => $url, 'menu-item-status' => 'publish', 'menu-item-parent-id' => $parent, 'menu-item-type' => 'custom');
        if ($post) { $data['menu-item-type'] = 'post_type'; $data['menu-item-object'] = $post->post_type; $data['menu-item-object-id'] = $post->ID; }
        $id = wp_update_nav_menu_item($menu_id, 0, $data);
        if (is_wp_error($id)) { return 0; }
        $urls[$key] = (int) $id;
        return (int) $id;
    };
    $services = get_page_by_path('services');
    $about = get_page_by_path('about-rpm');
    $contact = get_page_by_path('contact');
    if (!$services || !$about || !$contact) { return; }
    $services_parent = $add('Services', get_permalink($services), 0, $services);
    $projects_parent = $add('Projects', get_post_type_archive_link('rpm_project'));
    $about_parent = $add('About', get_permalink($about), 0, $about);
    if (!$services_parent || !$projects_parent || !$about_parent) { return; }
    foreach (get_pages(array('parent' => $services->ID, 'sort_column' => 'menu_order,post_title')) as $page) {
        if (!$add($page->post_title, get_permalink($page), $services_parent, $page)) { return; }
    }
    foreach (get_posts(array('post_type' => 'rpm_project', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC')) as $project) {
        if (!$add($project->post_title, get_permalink($project), $projects_parent, $project)) { return; }
    }
    $accreditations = get_page_by_path('accreditations');
    if ($accreditations && !$add($accreditations->post_title, get_permalink($accreditations), $about_parent, $accreditations)) { return; }
    if (!$add('Contact', get_permalink($contact), 0, $contact)) { return; }
    if (function_exists('get_field') && get_field('hero_heading', $contact->ID, false) === 'Tell us about your glazing project') {
        update_field('field_rpm_page_heading', 'Contact RPM', $contact->ID);
    }
    update_option('rpm_navigation_release', '1');
}
add_action('init', 'rpm_complete_navigation', 110);
