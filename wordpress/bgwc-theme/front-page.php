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
				<a class="cta" href="<?php echo esc_url( bgwc_field( 'cta_url' ) ); ?>"><?php echo esc_html( bgwc_field( 'cta_text' ) ); ?> <span>↗</span></a><a class="social-link" href="<?php echo esc_url( bgwc_field( 'social_url' ) ); ?>"><?php echo esc_html( bgwc_field( 'social_text' ) ); ?></a>
			</div>
		</section>
		<footer class="bottom">
			<div class="caption"><?php echo esc_html( bgwc_field( 'caption' ) ); ?></div>
			<div class="location"><?php echo esc_html( bgwc_field( 'location_text' ) ); ?></div>
		</footer>
	</main>
	<?php wp_footer(); ?>
</body>

</html>
