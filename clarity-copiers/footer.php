<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$uri = get_template_directory_uri();
$def_fsectors = array( 'Education', 'Legal', 'Healthcare', 'Manufacturing', 'Public Sector' );
$fsectors = clarity_rows( 'footer_sectors', array() );
$def_social = array(
	array( 'network' => 'facebook', 'url' => '#' ),
	array( 'network' => 'linkedin', 'url' => '#' ),
	array( 'network' => 'x', 'url' => '#' ),
);
$socials = clarity_rows( 'socials', $def_social );
$svg = array(
	'facebook' => '<path d="M14 9h3V6h-3c-2 0-3 1-3 3v2H8v3h3v6h3v-6h2.5l.5-3H14v-1.5c0-.6.3-.5.7-.5H14z"/>',
	'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 10v7M7 7v.01M11 17v-4a2 2 0 014 0v4M11 10v7" stroke="#0e0e0e"/>',
	'x'        => '<path d="M4 4l16 16M20 4L4 20"/>',
);
?>
<footer class="site-footer">
	<div class="container">
		<div class="cols">
			<div>
				<img class="flogo" src="<?php echo esc_url( $uri . '/assets/logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
				<p class="muted"><?php echo esc_html( clarity_field( 'footer_about', 'Your trusted Sharp technology partner since 1995. Delivering exceptional managed print and document solutions with local, personal service.' ) ); ?></p>
				<p class="muted" style="margin-bottom:2px">24/7 Remote Support Available</p>
				<a class="footer-remote" href="<?php echo esc_url( home_url( '/support/' ) ); ?>">Get Remote Assistance →</a>
			</div>
			<div>
				<h4>Quick Links</h4>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>' ) );
				} else {
					$ql = array( 'Home' => '/', 'About' => '/about/', 'Our Team' => '/our-team/', 'Products' => '/products/', 'Support' => '/support/' );
					echo '<ul>';
					foreach ( $ql as $l => $u ) { echo '<li><a href="' . esc_url( home_url( $u ) ) . '">' . esc_html( $l ) . '</a></li>'; }
					echo '</ul>';
				}
				?>
			</div>
			<div>
				<h4>Sectors</h4>
				<ul>
				<?php
				if ( $fsectors ) {
					foreach ( $fsectors as $row ) { echo '<li>' . esc_html( is_array( $row ) ? ( $row['label'] ?? '' ) : $row ) . '</li>'; }
				} else {
					foreach ( $def_fsectors as $l ) { echo '<li>' . esc_html( $l ) . '</li>'; }
				}
				?>
				</ul>
			</div>
			<div>
				<h4>Contact</h4>
				<ul>
					<li class="muted"><?php echo esc_html( clarity_opt( 'address' ) ); ?></li>
					<li>T: <a href="tel:<?php echo esc_attr( str_replace( ' ', '', clarity_opt( 'phone' ) ) ); ?>"><?php echo esc_html( clarity_opt( 'phone' ) ); ?></a></li>
					<li>E: <a href="mailto:<?php echo esc_attr( clarity_opt( 'email' ) ); ?>"><?php echo esc_html( clarity_opt( 'email' ) ); ?></a></li>
				</ul>
				<div class="socials">
					<?php foreach ( $socials as $s ) :
						$net = is_array( $s ) ? ( $s['network'] ?? '' ) : '';
						if ( ! isset( $svg[ $net ] ) ) { continue; }
					?>
					<a href="<?php echo esc_url( $s['url'] ?? '#' ); ?>" aria-label="<?php echo esc_attr( $net ); ?>" target="_blank" rel="noopener">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $svg[ $net ]; ?></svg>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
			<span>
				<a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>">Cookies</a> ·
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a> ·
				<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms of Use</a>
			</span>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
