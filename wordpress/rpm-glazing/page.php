<?php
get_header();
while (have_posts()) : the_post();
    $page_id = get_the_ID();
    $hero_image = rpm_image_url(rpm_get_field('hero_image', $page_id), 'full');
    $highlights = rpm_get_field('highlights', $page_id, array());
?>
<main id="main-content" class="rpm-shell<?php echo is_page('contact') ? ' contact-page' : ''; ?>">
    <section class="inner-hero"<?php if ($hero_image) : ?> style="background-image:url('<?php echo esc_url($hero_image); ?>')"<?php endif; ?>>
        <div class="rpm-wrap inner-hero__content">
            <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('hero_kicker', $page_id, 'RPM Glazing Systems')); ?></p>
            <h1 class="rpm-title"><?php echo esc_html(rpm_get_field('hero_heading', $page_id, get_the_title())); ?></h1>
            <p><?php echo esc_html(rpm_get_field('hero_intro', $page_id, get_the_excerpt())); ?></p>
        </div>
    </section>

    <?php if (!is_page('contact')) : ?>
    <section class="rpm-section rpm-section--pale">
        <div class="rpm-wrap inner-intro">
            <div>
                <p class="rpm-eyebrow"><?php echo esc_html(rpm_get_field('section_kicker', $page_id, 'Built around your project')); ?></p>
                <h2 class="rpm-section-title"><?php echo esc_html(rpm_get_field('section_heading', $page_id, get_the_title())); ?></h2>
            </div>
            <div class="inner-intro__body">
                <?php
                $copy = rpm_get_field('section_copy', $page_id, apply_filters('the_content', get_the_content()));
                echo wp_kses_post($copy);
                ?>
            </div>
        </div>
    </section>

    <?php if ($highlights) : ?>
        <section class="rpm-section rpm-section--white">
            <div class="rpm-wrap">
                <div class="highlights-grid">
                    <?php foreach ($highlights as $index => $item) :
                        $tag = !empty($item['link']) ? 'a' : 'article';
                        $link = !empty($item['link']) ? ' href="' . esc_url($item['link']) . '"' : '';
                    ?>
                        <<?php echo esc_html($tag); ?> class="highlight"<?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                            <small><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></small>
                            <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
                            <p><?php echo esc_html($item['text'] ?? ''); ?></p>
                        </<?php echo esc_html($tag); ?>>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php endif; ?>
    <?php if (is_page('contact')) : ?>
        <?php get_template_part('template-parts/contact-section'); ?>
    <?php else : ?>
        <?php get_template_part('template-parts/enquiry-strip'); ?>
    <?php endif; ?>
</main>
<?php endwhile; get_footer(); ?>
