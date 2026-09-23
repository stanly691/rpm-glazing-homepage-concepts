<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$logo = get_template_directory_uri() . '/assets/logo.png';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="utilitybar">
	<div class="container">
		<a href="#"><?php echo clarity_icon( 'showroom' ); ?><span>Virtual Showroom</span></a>
		<a href="#"><?php echo clarity_icon( 'support' ); ?><span>Remote Support</span></a>
		<a class="spacer" href="tel:<?php echo esc_attr( str_replace( ' ', '', clarity_opt( 'phone' ) ) ); ?>">
			<?php echo clarity_icon( 'phone' ); ?><span><?php echo esc_html( clarity_opt( 'phone' ) ); ?></span></a>
		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo clarity_icon( 'audit' ); ?><span>Request Print Audit</span></a>
	</div>
</div>

<header class="site-header">
	<div class="container">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		</a>
		<span class="brand-badge"><span class="num">30</span><small>YEARS<br>EST. 1995</small></span>

		<button class="nav-toggle" aria-label="Menu" onclick="document.getElementById('primary-nav').classList.toggle('open')"><span></span></button>
		<nav class="main-nav" id="primary-nav">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) );
			} else {
				echo '<ul>';
				$fallback = array(
					'Home' => home_url( '/' ), 'About' => home_url( '/about/' ),
					'Our Team' => home_url( '/our-team/' ), 'Products' => home_url( '/products/' ),
					'Support' => home_url( '/support/' ), 'Gallery' => home_url( '/gallery/' ),
					'Contact' => home_url( '/contact/' ),
				);
				foreach ( $fallback as $label => $url ) {
					echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</nav>
		<a class="btn btn--outline-red header-cta" style="border-color:var(--red);color:#fff" href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><?php echo clarity_icon( 'support' ); ?> Remote Support</a>
	</div>
</header>
