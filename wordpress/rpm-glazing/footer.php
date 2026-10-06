<?php
if (!defined('ABSPATH')) { exit; }
$phone = rpm_get_field('phone', 'option', '01656 724704');
$email = rpm_get_field('email', 'option', 'info@rpmglazing.com');
$address = rpm_get_field('address', 'option', "14 Millers Avenue\nBrynmenyn Industrial Estate\nBridgend, CF32 9TD");
$legal = rpm_get_field('footer_legal', 'option', 'RPM Shopfront Manufacturers Ltd trading as RPM Glazing Systems');
$services_page = get_page_by_path('services', OBJECT, array('page'));
$footer_services = $services_page ? array_slice(get_pages(array(
    'parent' => $services_page->ID,
    'sort_column' => 'menu_order,post_title',
    'sort_order' => 'ASC',
)), 0, 4) : array();
$privacy_url = get_privacy_policy_url();
?>
<footer class="site-footer rpm-shell">
    <div class="site-footer__grid">
        <div class="site-footer__brand">
            <a class="site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="RPM Glazing Systems home">
                <?php rpm_brand_logo('footer'); ?>
            </a>
        </div>
        <div>
            <h2 class="site-footer__heading"><?php esc_html_e('Services', 'rpm-glazing'); ?></h2>
            <ul>
                <?php foreach ($footer_services as $service_page) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($service_page)); ?>"><?php echo esc_html($service_page->post_title); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h2 class="site-footer__heading"><?php esc_html_e('Company', 'rpm-glazing'); ?></h2>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/about-rpm/')); ?>">About RPM</a></li>
                <li><a href="<?php echo esc_url(get_post_type_archive_link('rpm_project')); ?>">Projects</a></li>
                <li><a href="<?php echo esc_url(home_url('/accreditations/')); ?>">Accreditations</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
            </ul>
        </div>
        <div>
            <h2 class="site-footer__heading"><?php esc_html_e('Visit', 'rpm-glazing'); ?></h2>
            <address><?php echo nl2br(esc_html($address)); ?></address>
        </div>
    </div>
    <div class="site-footer__bottom">
        <span><?php echo esc_html($legal); ?></span>
        <span><?php if ($privacy_url) : ?><a href="<?php echo esc_url($privacy_url); ?>">Privacy</a> · <?php endif; ?><a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookies</a> · <a href="<?php echo esc_url(home_url('/terms-of-use/')); ?>">Terms</a></span>
        <span>© <?php echo esc_html(wp_date('Y')); ?> RPM</span>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
