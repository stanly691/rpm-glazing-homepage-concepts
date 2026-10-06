<?php
$phone = rpm_get_field('phone', 'option', '01656 724704');
$context_id = is_post_type_archive() ? 'option' : get_queried_object_id();
$cta_heading = rpm_get_field('cta_heading', $context_id, 'Quick project enquiry');
$cta_text = rpm_get_field('cta_text', $context_id, 'Our commercial glazing team will review the requirements and help you identify the right route forward.');
$cta_kicker = rpm_get_field('cta_kicker', $context_id, 'Have a live project or tender?');
$cta_button = rpm_get_field('cta_button_label', $context_id, 'Make an enquiry');
$cta_phone_label = rpm_get_field('cta_phone_label', $context_id, 'Speak directly to RPM');
$cta_url = rpm_get_field('cta_url', $context_id, rpm_get_field('header_cta_url', 'option', home_url('/contact/')));
?>
<section class="rpm-section rpm-section--green enquiry-strip">
    <div class="rpm-wrap enquiry-strip__inner">
        <div>
            <p class="rpm-eyebrow"><?php echo esc_html($cta_kicker); ?></p>
            <h2 class="rpm-section-title"><?php echo esc_html($cta_heading); ?></h2>
            <p><?php echo esc_html($cta_text); ?></p>
        </div>
        <div class="enquiry-strip__action">
            <a class="rpm-button" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_button); ?></a>
            <small><?php echo esc_html($cta_phone_label); ?></small>
            <a href="<?php echo esc_url(rpm_phone_href($phone)); ?>"><strong><?php echo esc_html($phone); ?></strong></a>
        </div>
    </div>
</section>
