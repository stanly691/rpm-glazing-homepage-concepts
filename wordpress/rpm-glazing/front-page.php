<?php
get_header();
$home_id = get_queried_object_id();
$phone = rpm_get_field('phone', 'option', '01656 724704');
$hero_image = rpm_image_url(rpm_get_field('home_hero_image', $home_id), 'full');
$hero_heading = rpm_get_field('home_hero_heading', $home_id, "Complete commercial glazing\nfrom one specialist team");
$heading_lines = preg_split('/\r\n|\r|\n/', $hero_heading);
$services = rpm_get_field('services', $home_id, array());
$process = rpm_get_field('process', $home_id, array());
$quality = rpm_get_field('quality_items', $home_id, array());
$proof_items = rpm_get_field('proof_items', $home_id, array());
$header_cta = rpm_get_field('header_cta_text', 'option', 'Make an enquiry');
?>
<main id="main-content" class="rpm-shell">
    <section class="home-hero"<?php if ($hero_image) : ?> style="background-image:url('<?php echo esc_url($hero_image); ?>')"<?php endif; ?>>
        <div class="home-hero__inner">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('home_hero_eyebrow', $home_id, 'Commercial glazing contractor · South Wales · Nationwide')); ?></p>
            <h1 class="rpm-display home-hero__title">
                <strong><?php echo esc_html($heading_lines[0] ?? 'Complete commercial glazing'); ?></strong>
                <?php foreach (array_slice($heading_lines, 1) as $heading_line) : ?>
                    <?php if (trim($heading_line) !== '') : ?><span><?php echo esc_html($heading_line); ?></span><?php endif; ?>
                <?php endforeach; ?>
            </h1>
            <div class="home-hero__bottom">
                <p class="home-hero__summary"><?php echo esc_html(rpm_get_field('home_hero_summary', $home_id)); ?></p>
                <a class="rpm-button" href="<?php echo esc_url(rpm_get_field('header_cta_url', 'option', home_url('/contact/'))); ?>"><?php echo esc_html($header_cta); ?></a>
                <a class="home-hero__phone" href="<?php echo esc_url(rpm_phone_href($phone)); ?>">
                    <span><?php echo esc_html(rpm_get_field('call_label', 'option', 'Call RPM')); ?></span>
                    <strong><?php echo esc_html($phone); ?></strong>
                </a>
                <div class="home-hero__team">
                    <strong><?php echo esc_html(rpm_get_field('home_team_heading', $home_id, 'One team')); ?></strong>
                    <span><?php echo nl2br(esc_html(rpm_get_field('home_team_text', $home_id, "Design · Manufacture\nInstallation"))); ?></span>
                </div>
            </div>
        </div>
    </section>

    <section class="rpm-proofbar">
        <div class="rpm-wrap rpm-proofbar__inner">
            <p><?php echo esc_html(rpm_get_field('proof_intro', $home_id, 'Commercial glazing expertise from our purpose-built facility in Bridgend.')); ?></p>
            <?php foreach ($proof_items as $item) : ?>
                <div><strong><?php echo esc_html($item['value'] ?? ''); ?></strong><small><?php echo esc_html($item['label'] ?? ''); ?></small></div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="rpm-section rpm-section--pale overview">
        <div class="rpm-wrap overview__grid">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('overview_kicker', $home_id, 'Built around your project')); ?></p>
            <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('overview_heading', $home_id, 'Business and service overview')); ?></h2>
            <div class="overview__spacer"></div>
            <div class="overview__copy">
                <p><?php echo esc_html(rpm_get_field('overview_left', $home_id)); ?></p>
                <a class="rpm-text-link" href="<?php echo esc_url(rpm_get_field('overview_link', $home_id, home_url('/about-rpm/'))); ?>"><?php echo esc_html(rpm_get_field('overview_link_label', $home_id, 'How we deliver projects')); ?></a>
            </div>
            <div class="overview__copy"><p><?php echo esc_html(rpm_get_field('overview_right', $home_id)); ?></p></div>
        </div>
    </section>

    <section class="rpm-section rpm-section--dark">
        <div class="rpm-wrap">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('services_kicker', $home_id, 'Our capabilities')); ?></p>
            <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('services_heading', $home_id, 'Commercial glazing services')); ?></h2>
            <?php if ($services) : ?>
                <div class="services-grid">
                    <?php foreach ($services as $service) : ?>
                        <a class="service-card" href="<?php echo esc_url($service['link'] ?? home_url('/services/')); ?>">
                            <span class="service-card__number"><?php echo esc_html($service['number'] ?? ''); ?></span>
                            <h3><?php echo esc_html($service['title'] ?? ''); ?></h3>
                            <p><?php echo esc_html($service['description'] ?? ''); ?></p>
                            <span class="service-card__arrow" aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="rpm-section rpm-section--pale home-portfolio">
        <div class="rpm-wrap">
            <div class="portfolio-heading">
                <div>
                    <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('portfolio_kicker', $home_id, 'Selected work')); ?></p>
                    <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('portfolio_heading', $home_id, 'Completed project portfolio')); ?></h2>
                </div>
                <p><?php echo esc_html(rpm_get_field('portfolio_intro', $home_id)); ?></p>
            </div>
            <?php
            $projects = new WP_Query(array(
                'post_type' => 'rpm_project',
                'post_status' => 'publish',
                'posts_per_page' => 4,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ));
            if ($projects->have_posts()) :
            ?>
                <div class="projects-grid">
                    <?php $index = 0; while ($projects->have_posts()) : $projects->the_post(); $index++; ?>
                        <a class="project-card<?php echo $index === 1 ? ' project-card--large' : ''; ?>" href="<?php the_permalink(); ?>">
                            <div class="project-card__image"><?php echo rpm_project_card_image(get_the_ID()); ?></div>
                            <div class="project-card__meta">
                                <small><?php echo esc_html(rpm_get_field('sector', get_the_ID())); ?></small>
                                <h3><?php the_title(); ?></h3>
                                <p><?php echo esc_html(rpm_get_field('services_text', get_the_ID())); ?></p>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <p class="portfolio-action"><a class="rpm-button rpm-button--dark" href="<?php echo esc_url(get_post_type_archive_link('rpm_project')); ?>"><?php echo esc_html(rpm_get_field('portfolio_button_label', $home_id, 'Explore projects')); ?></a></p>
            <?php endif; ?>
        </div>
    </section>

    <?php get_template_part('template-parts/enquiry-strip'); ?>

    <?php $spotlight_image = rpm_image_url(rpm_get_field('spotlight_image', $home_id), 'full'); ?>
    <section class="spotlight"<?php if ($spotlight_image) : ?> style="background-image:url('<?php echo esc_url($spotlight_image); ?>')"<?php endif; ?>>
        <div class="rpm-wrap spotlight__content">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('spotlight_kicker', $home_id, 'Recently secured · Healthcare')); ?></p>
            <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('spotlight_title', $home_id)); ?></h2>
            <p><?php echo esc_html(rpm_get_field('spotlight_text', $home_id)); ?></p>
            <small class="spotlight__caption"><?php echo esc_html(rpm_get_field('spotlight_caption', $home_id, 'Project visualisation')); ?></small>
        </div>
    </section>

    <section class="rpm-section rpm-section--pale home-process">
        <div class="rpm-wrap">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('process_kicker', $home_id, 'A controlled process')); ?></p>
            <div class="process-heading">
                <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('process_heading', $home_id, 'Project delivery process')); ?></h2>
                <p><?php echo esc_html(rpm_get_field('process_intro', $home_id)); ?></p>
            </div>
            <?php if ($process) : ?>
                <div class="process-grid">
                    <?php foreach ($process as $index => $step) : ?>
                        <article class="process-step">
                            <small><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></small>
                            <h3><?php echo esc_html($step['title'] ?? ''); ?></h3>
                            <p><?php echo esc_html($step['text'] ?? ''); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="rpm-section rpm-section--green home-quality">
        <div class="rpm-wrap quality-grid">
            <div class="quality-copy">
                <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('quality_kicker', $home_id, 'Quality without compromise')); ?></p>
                <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('quality_heading', $home_id, 'Quality, safety and accreditations')); ?></h2>
                <p><?php echo esc_html(rpm_get_field('quality_text', $home_id)); ?></p>
                <a class="rpm-text-link" href="<?php echo esc_url(rpm_get_field('quality_link', $home_id, home_url('/accreditations/'))); ?>"><?php echo esc_html(rpm_get_field('quality_link_label', $home_id, 'Explore our standards')); ?></a>
            </div>
            <?php if ($quality) : ?>
                <div class="quality-metrics">
                    <?php foreach ($quality as $item) : ?>
                        <div class="quality-metric">
                            <strong><?php echo esc_html($item['value'] ?? ''); ?></strong>
                            <span><?php echo esc_html($item['label'] ?? ''); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php get_template_part('template-parts/contact-section'); ?>
</main>
<?php get_footer(); ?>
