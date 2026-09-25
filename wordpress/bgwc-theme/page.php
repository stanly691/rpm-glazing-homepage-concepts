<?php
/**
 * Inner pages (e.g. Join). Same brand header as the holding page, with the
 * page content below on the dark background.
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bgwc-page' ); ?>>
	<?php wp_body_open(); ?>
	<div class="page-wrap">
		<img class="page-bg" src="<?php echo esc_url( bgwc_image( 'hero_image', 'hero.jpg' ) ); ?>" alt="">
		<header class="top">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="logo"
					src="<?php echo esc_url( bgwc_image( 'logo', 'logo.jpg' ) ); ?>"
					alt="<?php echo esc_attr( bgwc_field( 'logo_alt' ) ); ?>">
				<div class="brand-copy"><strong><?php echo esc_html( bgwc_field( 'brand_name' ) ); ?></strong><span><?php echo esc_html( bgwc_field( 'brand_tagline' ) ); ?></span></div>
			</a>
			<a class="status back" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <span>Back to </span>home</a>
		</header>
		<?php while ( have_posts() ) : the_post(); ?>
			<main class="page-content">
				<div class="page-intro">
					<p class="eyebrow"><?php echo esc_html( bgwc_field( 'eyebrow' ) ); ?></p>
					<h1 class="page-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<?php if ( bgwc_field( 'social_url' ) ) : ?>
						<p class="page-help">Questions first? <a href="<?php echo esc_url( bgwc_field( 'social_url' ) ); ?>">Message us on Instagram ↗</a></p>
					<?php endif; ?>
				</div>
				<div class="entry">
					<?php the_content(); ?>
				</div>
			</main>
		<?php endwhile; ?>
		<footer class="bottom">
			<div class="caption"><?php echo esc_html( bgwc_field( 'location_text' ) ); ?></div>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>

</html>
