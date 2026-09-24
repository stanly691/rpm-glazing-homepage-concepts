<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$mk       = get_template_directory_uri() . '/assets/mockup/';
$fsectors = clarity_rows( 'footer_sectors', array( array( 'label' => 'Education' ), array( 'label' => 'Legal' ), array( 'label' => 'Healthcare' ), array( 'label' => 'Manufacturing' ), array( 'label' => 'Public Sector' ) ) );
$socials  = clarity_rows( 'socials', array( array( 'network' => 'facebook', 'url' => '#' ), array( 'network' => 'linkedin', 'url' => '#' ), array( 'network' => 'x', 'url' => '#' ) ) );
$icons    = array( 'facebook' => 's-facebook.svg', 'linkedin' => 's-linkedin.svg', 'x' => 's-x.svg' );
$phone    = clarity_opt( 'phone' );
$email    = clarity_opt( 'email' );
?>
</main>
<footer class="site-footer">
	<div class="wrap">
		<div class="f-brand">
			<img class="flogo" src="<?php echo esc_url( $mk . 'logo.webp' ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="246" height="72" loading="lazy" decoding="async">
			<p><?php echo esc_html( clarity_field( 'footer_about', 'Your trusted Sharp technology partner since 1995. Delivering exceptional managed print and document solutions with local, personal service.' ) ); ?></p>
			<p class="f-247">24/7 Remote Support Available</p>
			<a class="f-remote" href="<?php echo esc_url( home_url( '/support/' ) ); ?>">Get Remote Assistance</a>
		</div>

		<div class="f-col f-links">
			<h4>Quick Links</h4>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1 ) );
			} else {
				echo '<ul>';
				foreach ( array( 'Home' => '/', 'About' => '/about/', 'Our Team' => '/our-team/', 'Products' => '/products/', 'Support' => '/support/' ) as $l => $u ) {
					echo '<li><a href="' . esc_url( home_url( $u ) ) . '">' . esc_html( $l ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</div>

		<div class="f-col f-sectors">
			<h4>Sectors</h4>
			<ul>
				<?php foreach ( $fsectors as $row ) : ?>
				<li><?php echo esc_html( is_array( $row ) ? ( $row['label'] ?? '' ) : $row ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="f-col f-contact">
			<h4>Contact</h4>
			<ul>
				<li><?php echo esc_html( clarity_opt( 'address' ) ); ?></li>
				<li>T: <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
				<li>E: <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
			</ul>
			<div class="socials">
				<?php
				foreach ( $socials as $s ) :
					$net = is_array( $s ) ? ( $s['network'] ?? '' ) : '';
					if ( ! isset( $icons[ $net ] ) ) { continue; }
					?>
				<a class="<?php echo $net === 'facebook' ? 'fb' : esc_attr( $net ); ?>" href="<?php echo esc_url( $s['url'] ?? '#' ); ?>" aria-label="<?php echo esc_attr( ucfirst( $net ) ); ?>" target="_blank" rel="noopener"><img src="<?php echo esc_url( $mk . $icons[ $net ] ); ?>" alt="" width="31" height="31" loading="lazy" decoding="async"></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="wrap f-legal">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
		<nav aria-label="Legal">
			<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy</a>
			<a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>">Cookies</a>
			<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms of use</a>
		</nav>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
