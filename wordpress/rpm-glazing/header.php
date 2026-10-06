<?php
if (!defined('ABSPATH')) { exit; }
$phone = rpm_get_field('phone', 'option', '01656 724704');
$email = sanitize_email(rpm_get_field('email', 'option', 'info@rpmglazing.com'));
$cta = rpm_get_field('header_cta_text', 'option', 'Make an enquiry');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#main-content"><?php esc_html_e('Skip to content', 'rpm-glazing'); ?></a>
<header class="site-header rpm-shell">
    <div class="site-header__inner">
        <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="RPM Glazing Systems home">
            <?php rpm_brand_logo('header'); ?>
        </a>
        <button class="site-header__toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" aria-label="Open navigation">
            <span></span><span></span>
        </button>
        <nav class="site-nav" id="primary-navigation" aria-label="Primary navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => 'rpm_navigation_fallback',
                'depth' => 2,
                'walker' => new RPM_Navigation_Walker(),
            ));
            ?>
            <div class="site-nav__mobile-actions">
                <a class="rpm-button rpm-button--green" href="<?php echo esc_url(rpm_get_field('header_cta_url', 'option', home_url('/contact/'))); ?>"><?php echo esc_html($cta); ?></a>
                <a href="<?php echo esc_url(rpm_phone_href($phone)); ?>"><?php echo esc_html(rpm_get_field('call_label', 'option', 'Call RPM') . ' ' . $phone); ?></a>
                <?php if ($email) : ?>
                    <a class="site-header__email" href="<?php echo esc_url('mailto:' . $email); ?>"><?php echo esc_html($email); ?></a>
                <?php endif; ?>
            </div>
        </nav>
        <div class="site-header__contact">
            <a class="site-header__phone" href="<?php echo esc_url(rpm_phone_href($phone)); ?>">
                <span><?php echo esc_html(rpm_get_field('call_label', 'option', 'Call RPM')); ?></span>
                <strong><?php echo esc_html($phone); ?></strong>
            </a>
            <?php if ($email) : ?>
                <a class="site-header__email" href="<?php echo esc_url('mailto:' . $email); ?>"><?php echo esc_html($email); ?></a>
            <?php endif; ?>
        </div>
        <a class="rpm-button rpm-button--green site-header__cta" href="<?php echo esc_url(rpm_get_field('header_cta_url', 'option', home_url('/contact/'))); ?>"><?php echo esc_html($cta); ?></a>
    </div>
</header>

<?php
function rpm_navigation_fallback() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/services/')) . '">Services</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('rpm_project')) . '">Projects</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about-rpm/')) . '">About</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">Contact</a></li>';
    echo '</ul>';
}
?>
