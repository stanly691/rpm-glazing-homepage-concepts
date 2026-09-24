<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$mk    = get_template_directory_uri() . '/assets/mockup/';
$phone = clarity_opt( 'phone' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<div class="utilitybar">
	<div class="wrap">
		<a class="ub-item ub-showroom" href="<?php echo esc_url( clarity_field( 'showroom_url', home_url( '/products/' ) ) ); ?>"><img src="<?php echo esc_url( $mk . 'u-showroom.svg' ); ?>" alt="" width="28" height="24"><span>Virtual Showroom</span></a>
		<a class="ub-item ub-remote" href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><img src="<?php echo esc_url( $mk . 'u-remote.svg' ); ?>" alt="" width="32" height="32"><span>Remote Support</span></a>
		<a class="ub-item ub-phone" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><img src="<?php echo esc_url( $mk . 'u-phone.svg' ); ?>" alt="" width="25" height="25"><span><?php echo esc_html( $phone ); ?></span></a>
		<a class="ub-item ub-audit" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><img src="<?php echo esc_url( $mk . 'u-audit.svg' ); ?>" alt="" width="28" height="26"><span>Request Print Audit</span></a>
	</div>
</div>

<header class="site-header">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
			<img class="logo" src="<?php echo esc_url( $mk . 'logo.webp' ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="231" height="68">
			<span class="badge">
				<img src="<?php echo esc_url( $mk . 'badge-30.svg' ); ?>" alt="30th" width="94" height="68">
				<span class="badge-txt"><span>ANNIVERSARY</span><b>EST,1995</b></span>
			</span>
		</a>

		<nav class="main-nav" id="primary-nav" aria-label="Primary">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'depth' => 2 ) );
			} else {
				echo '<ul>';
				foreach ( array( 'Home' => '/', 'About' => '/about/', 'Our Team' => '/our-team/', 'Products' => '/products/', 'Support' => '/support/', 'Gallery' => '/gallery/', 'Contact' => '/contact/' ) as $l => $u ) {
					echo '<li><a href="' . esc_url( home_url( $u ) ) . '">' . esc_html( $l ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</nav>

		<a class="header-cta" href="<?php echo esc_url( home_url( '/support/' ) ); ?>">Remote Support</a>
		<button class="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="primary-nav"><span></span></button>
	</div>
</header>
<main id="main">
