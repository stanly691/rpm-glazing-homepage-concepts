<?php
$phone = rpm_get_field('phone', 'option', '01656 724704');
$email = rpm_get_field('email', 'option', 'info@rpmglazing.com');
$address = rpm_get_field('address', 'option', "14 Millers Avenue\nBrynmenyn Industrial Estate\nBridgend, CF32 9TD");
$contact_kicker = rpm_get_field('contact_kicker', 'option', 'Start your enquiry');
$contact_heading = rpm_get_field('contact_heading', 'option', 'Tell us about your glazing project');
$contact_intro = rpm_get_field('contact_intro', 'option', 'Share the scope, drawings or programme and the RPM team will review what you need.');
$contact_phone_label = rpm_get_field('contact_phone_label', 'option', 'Prefer to speak now?');
$contact_location = rpm_get_field('contact_location_text', 'option', 'Bridgend, South Wales · Projects delivered nationwide');
$form_submit_label = rpm_get_field('form_submit_label', 'option', 'Send project enquiry');
$services_page = get_page_by_path('services', OBJECT, array('page'));
$service_pages = $services_page ? get_pages(array(
    'parent' => $services_page->ID,
    'sort_column' => 'menu_order,post_title',
    'sort_order' => 'ASC',
)) : array();
$status = isset($_GET['enquiry']) ? sanitize_key(wp_unslash($_GET['enquiry'])) : '';
?>
<section class="rpm-section rpm-section--pale rpm-contact-section" id="enquiry">
    <div class="rpm-wrap contact-layout">
        <div class="contact-copy">
            <p class="rpm-eyebrow"><?php echo esc_html($contact_kicker); ?></p>
            <h2 class="rpm-section-title"><?php echo esc_html($contact_heading); ?></h2>
            <p><?php echo esc_html($contact_intro); ?></p>
            <a class="contact-callout" href="<?php echo esc_url(rpm_phone_href($phone)); ?>">
                <small><?php echo esc_html($contact_phone_label); ?></small>
                <strong>Call <?php echo esc_html($phone); ?></strong>
            </a>
            <p><a href="mailto:<?php echo esc_attr(antispambot($email)); ?>"><?php echo esc_html(antispambot($email)); ?></a></p>
            <p><?php echo esc_html($contact_location); ?></p>
        </div>
        <form class="rpm-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="rpm_enquiry">
            <?php wp_nonce_field('rpm_enquiry', 'rpm_enquiry_nonce'); ?>
            <div class="rpm-honeypot" aria-hidden="true"><label for="rpm-website">Website</label><input id="rpm-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
            <div class="rpm-field"><label for="rpm-name">Name *</label><input id="rpm-name" name="name" type="text" autocomplete="name" required></div>
            <div class="rpm-field"><label for="rpm-company">Company</label><input id="rpm-company" name="company" type="text" autocomplete="organization"></div>
            <div class="rpm-field"><label for="rpm-email">Email *</label><input id="rpm-email" name="email" type="email" autocomplete="email" required></div>
            <div class="rpm-field"><label for="rpm-telephone">Telephone</label><input id="rpm-telephone" name="telephone" type="tel" autocomplete="tel"></div>
            <div class="rpm-field"><label for="rpm-location">Project location *</label><input id="rpm-location" name="project_location" type="text" autocomplete="street-address" required></div>
            <div class="rpm-field">
                <label for="rpm-service">Required service *</label>
                <select id="rpm-service" name="required_service" required>
                    <option value="">Select a service</option>
                    <?php foreach ($service_pages as $service_page) : ?>
                        <option value="<?php echo esc_attr($service_page->post_title); ?>"><?php echo esc_html($service_page->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="rpm-field rpm-field--full"><label for="rpm-description">Project description *</label><textarea id="rpm-description" name="project_description" placeholder="For example: building type, project stage and required glazing scope…" required></textarea></div>
            <div class="rpm-form__footer">
                <button class="rpm-button rpm-form__submit" type="submit"><?php echo esc_html($form_submit_label); ?></button>
                <span class="rpm-form__status" aria-live="polite">
                    <?php
                    if ($status === 'sent') { echo esc_html__('Thank you — your enquiry has been sent.', 'rpm-glazing'); }
                    elseif ($status === 'failed') { echo esc_html__('The enquiry could not be sent. Please call or email us.', 'rpm-glazing'); }
                    elseif ($status === 'invalid') { echo esc_html__('Please complete all required fields.', 'rpm-glazing'); }
                    elseif ($status === 'rate') { echo esc_html__('Please wait a moment before sending another enquiry.', 'rpm-glazing'); }
                    elseif ($status === 'security') { echo esc_html__('This form has expired. Please reload the page and try again.', 'rpm-glazing'); }
                    else { echo esc_html__('Your details will be used to respond to your enquiry.', 'rpm-glazing'); }
                    ?>
                </span>
            </div>
        </form>
    </div>
</section>
