<?php
/**
 * Template Name: Manufacture Only
 * Template Post Type: page
 *
 * Supply-only / manufacture-only service page for general builders and
 * installation-only companies. All copy comes from the "Manufacture Only page"
 * ACF field group (inc/acf-manufacture-only.php); empty fields and sections
 * are skipped. Markup reuses the theme's existing service-page classes.
 */

get_header();

$rpm_mo = static function ( $name ) {
	return function_exists( 'get_field' ) ? get_field( $name ) : null;
};

$rpm_mo_cards = static function ( $prefix, $count ) use ( $rpm_mo ) {
	$cards = array();
	for ( $i = 1; $i <= $count; $i++ ) {
		$card = $rpm_mo( $prefix . $i );
		if ( ! empty( $card['title'] ) ) {
			$cards[] = $card;
		}
	}
	return $cards;
};

$hero_image_id = $rpm_mo( 'mo_hero_image' );
$hero_image    = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : get_the_post_thumbnail_url( null, 'full' );
$audience      = $rpm_mo_cards( 'mo_audience_', 3 );
$steps         = $rpm_mo_cards( 'mo_step_', 5 );
$products      = array_filter( array_map( 'trim', preg_split( '/\R/', (string) $rpm_mo( 'mo_products_list' ) ) ) );

$cta_link = (string) $rpm_mo( 'mo_cta_button_link' );
if ( $cta_link && ! preg_match( '#^[a-z]+:#i', $cta_link ) ) {
	$cta_link = home_url( $cta_link );
}
$cta_phone = (string) $rpm_mo( 'mo_cta_phone' );
$cta_email = (string) $rpm_mo( 'mo_cta_email' );
?>
<main id="main-content" class="rpm-shell">
    <section class="inner-hero"<?php if ( $hero_image ) : ?> style="background-image:url('<?php echo esc_url( $hero_image ); ?>')"<?php endif; ?>>
        <div class="rpm-wrap inner-hero__content">
            <?php if ( $rpm_mo( 'mo_hero_eyebrow' ) ) : ?><p class="rpm-eyebrow"><?php echo esc_html( $rpm_mo( 'mo_hero_eyebrow' ) ); ?></p><?php endif; ?>
            <h1 class="rpm-title"><?php echo esc_html( $rpm_mo( 'mo_hero_title' ) ?: get_the_title() ); ?></h1>
            <?php if ( $rpm_mo( 'mo_hero_text' ) ) : ?><p><?php echo esc_html( $rpm_mo( 'mo_hero_text' ) ); ?></p><?php endif; ?>
        </div>
    </section>

    <?php if ( $rpm_mo( 'mo_intro_title' ) || $rpm_mo( 'mo_intro_body' ) ) : ?>
    <section class="rpm-section rpm-section--pale">
        <div class="rpm-wrap inner-intro">
            <div>
                <?php if ( $rpm_mo( 'mo_intro_eyebrow' ) ) : ?><p class="rpm-eyebrow"><?php echo esc_html( $rpm_mo( 'mo_intro_eyebrow' ) ); ?></p><?php endif; ?>
                <?php if ( $rpm_mo( 'mo_intro_title' ) ) : ?><h2 class="rpm-section-title"><?php echo esc_html( $rpm_mo( 'mo_intro_title' ) ); ?></h2><?php endif; ?>
            </div>
            <div class="inner-intro__body">
                <?php echo wp_kses_post( $rpm_mo( 'mo_intro_body' ) ); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $audience ) : ?>
    <section class="rpm-section rpm-section--white">
        <div class="rpm-wrap">
            <div class="highlights-grid">
                <?php foreach ( $audience as $i => $card ) : ?>
                    <article class="highlight">
                        <small><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></small>
                        <h3><?php echo esc_html( $card['title'] ); ?></h3>
                        <?php if ( ! empty( $card['text'] ) ) : ?><p><?php echo esc_html( $card['text'] ); ?></p><?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $products ) : ?>
    <section class="rpm-section rpm-section--pale">
        <div class="rpm-wrap service-detail-grid">
            <div>
                <?php if ( $rpm_mo( 'mo_products_eyebrow' ) ) : ?><p class="rpm-eyebrow"><?php echo esc_html( $rpm_mo( 'mo_products_eyebrow' ) ); ?></p><?php endif; ?>
                <?php if ( $rpm_mo( 'mo_products_title' ) ) : ?><h2 class="rpm-section-title"><?php echo esc_html( $rpm_mo( 'mo_products_title' ) ); ?></h2><?php endif; ?>
                <?php if ( $rpm_mo( 'mo_products_text' ) ) : ?><p><?php echo esc_html( $rpm_mo( 'mo_products_text' ) ); ?></p><?php endif; ?>
            </div>
            <ul class="service-detail-list">
                <?php foreach ( $products as $product ) : ?>
                    <li><?php echo esc_html( $product ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $steps ) : ?>
    <section class="rpm-section rpm-section--white home-process">
        <div class="rpm-wrap">
            <?php if ( $rpm_mo( 'mo_process_eyebrow' ) ) : ?><p class="rpm-eyebrow"><?php echo esc_html( $rpm_mo( 'mo_process_eyebrow' ) ); ?></p><?php endif; ?>
            <div class="process-heading">
                <?php if ( $rpm_mo( 'mo_process_title' ) ) : ?><h2 class="rpm-section-title"><?php echo esc_html( $rpm_mo( 'mo_process_title' ) ); ?></h2><?php endif; ?>
                <?php if ( $rpm_mo( 'mo_process_text' ) ) : ?><p><?php echo esc_html( $rpm_mo( 'mo_process_text' ) ); ?></p><?php endif; ?>
            </div>
            <div class="process-grid">
                <?php foreach ( $steps as $i => $step ) : ?>
                    <article class="process-step">
                        <small><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></small>
                        <h3><?php echo esc_html( $step['title'] ); ?></h3>
                        <?php if ( ! empty( $step['text'] ) ) : ?><p><?php echo esc_html( $step['text'] ); ?></p><?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $rpm_mo( 'mo_cta_title' ) ) : ?>
    <section class="rpm-section rpm-section--green enquiry-strip">
        <div class="rpm-wrap enquiry-strip__inner">
            <div>
                <?php if ( $rpm_mo( 'mo_cta_eyebrow' ) ) : ?><p class="rpm-eyebrow"><?php echo esc_html( $rpm_mo( 'mo_cta_eyebrow' ) ); ?></p><?php endif; ?>
                <h2 class="rpm-section-title"><?php echo esc_html( $rpm_mo( 'mo_cta_title' ) ); ?></h2>
                <?php if ( $rpm_mo( 'mo_cta_text' ) ) : ?><p><?php echo esc_html( $rpm_mo( 'mo_cta_text' ) ); ?></p><?php endif; ?>
            </div>
            <div class="enquiry-strip__action">
                <?php if ( $cta_link && $rpm_mo( 'mo_cta_button_label' ) ) : ?>
                    <a class="rpm-button" href="<?php echo esc_url( $cta_link ); ?>"><?php echo esc_html( $rpm_mo( 'mo_cta_button_label' ) ); ?></a>
                <?php endif; ?>
                <?php if ( $cta_phone ) : ?>
                    <?php if ( $rpm_mo( 'mo_cta_phone_label' ) ) : ?><small><?php echo esc_html( $rpm_mo( 'mo_cta_phone_label' ) ); ?></small><?php endif; ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $cta_phone ) ); ?>"><strong><?php echo esc_html( $cta_phone ); ?></strong></a>
                <?php endif; ?>
                <?php if ( $cta_email ) : ?>
                    <a href="mailto:<?php echo esc_attr( antispambot( $cta_email ) ); ?>"><?php echo esc_html( antispambot( $cta_email ) ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>
<?php
get_footer();
