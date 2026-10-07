<?php
/**
 * Holding page. Markup mirrors the original design; all content comes from
 * the ACF fields in inc/acf-fields.php.
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<main class="hero"><img class="bg"
			src="<?php echo esc_url( bgwc_image( 'hero_image', 'hero.jpg' ) ); ?>"
			alt="<?php echo esc_attr( bgwc_field( 'hero_alt' ) ); ?>">
		<header class="top">
			<div class="brand"><img class="logo"
					src="<?php echo esc_url( bgwc_image( 'logo', 'logo.jpg' ) ); ?>"
					alt="<?php echo esc_attr( bgwc_field( 'logo_alt' ) ); ?>">
				<div class="brand-copy"><strong><?php echo esc_html( bgwc_field( 'brand_name' ) ); ?></strong><span><?php echo esc_html( bgwc_field( 'brand_tagline' ) ); ?></span></div>
			</div>
			<?php if ( bgwc_field( 'status_text' ) ) : ?>
				<div class="status"><?php echo esc_html( bgwc_field( 'status_text' ) ); ?></div>
			<?php endif; ?>
		</header>
		<section class="content">
			<p class="eyebrow"><?php echo esc_html( bgwc_field( 'eyebrow' ) ); ?></p>
			<h1><?php echo esc_html( bgwc_field( 'heading_line_1' ) ); ?><br><?php echo esc_html( bgwc_field( 'heading_line_2' ) ); ?><span><?php echo esc_html( bgwc_field( 'heading_subline' ) ); ?></span></h1>
			<p class="intro"><?php echo esc_html( bgwc_field( 'intro' ) ); ?></p>
			<div class="actions">
				<?php if ( bgwc_show_cta() ) : ?><a class="cta" href="<?php echo esc_url( bgwc_field( 'cta_url' ) ); ?>"><?php echo esc_html( bgwc_field( 'cta_text' ) ); ?> <span>↗</span></a><?php endif; ?><?php if ( bgwc_field( 'phone' ) ) : ?><a class="<?php echo bgwc_show_cta() ? 'social-link' : 'cta'; ?> call-link" href="<?php echo esc_attr( bgwc_tel_href( bgwc_field( 'phone' ) ) ); ?>"><svg class="call-icon" aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M6.6 10.8a15.2 15.2 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1z"/></svg><span class="call-text">Call <?php echo esc_html( bgwc_field( 'phone' ) ); ?></span></a><?php endif; ?><a class="social-link" href="<?php echo esc_url( bgwc_field( 'social_url' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( bgwc_field( 'social_text' ) ); ?></a>
			</div>
		</section>
		<footer class="bottom">
			<div class="caption"><?php echo esc_html( bgwc_field( 'caption' ) ); ?></div>
			<div class="location"><?php echo bgwc_contact_markup(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
		</footer>
	</main>
	<?php wp_footer(); ?>
</body>

</html>
