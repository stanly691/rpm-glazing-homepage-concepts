<?php
$s         = $args['s'];
$shortcode = ! empty( $s['show_form'] ) ? clarity_enquiry_shortcode( $s['form_shortcode'] ?? '' ) : '';
?>
<section class="sec contact-sec" id="enquiry">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="contact-grid<?php echo $shortcode ? '' : ' no-form'; ?>">
			<div class="offices">
				<?php foreach ( (array) ( $s['offices'] ?? array() ) as $o ) : ?>
				<div class="office">
					<h3><?php echo esc_html( $o['name'] ?? '' ); ?></h3>
					<?php if ( ! empty( $o['address'] ) ) : ?><p class="o-row"><img src="<?php echo esc_url( clarity_mock_icon( 'pin' ) ); ?>" alt="" width="20" height="26" loading="lazy"><span><?php echo nl2br( esc_html( $o['address'] ) ); ?></span></p><?php endif; ?>
					<?php if ( ! empty( $o['phone'] ) ) : ?><p class="o-row"><img src="<?php echo esc_url( clarity_mock_icon( 'phone' ) ); ?>" alt="" width="20" height="20" loading="lazy"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $o['phone'] ) ); ?>"><?php echo esc_html( $o['phone'] ); ?></a></p><?php endif; ?>
					<?php if ( ! empty( $o['email'] ) ) : ?><p class="o-row"><img src="<?php echo esc_url( clarity_mock_icon( 'audit' ) ); ?>" alt="" width="20" height="20" loading="lazy"><a href="mailto:<?php echo esc_attr( $o['email'] ); ?>"><?php echo esc_html( $o['email'] ); ?></a></p><?php endif; ?>
					<?php if ( ! empty( $o['hours'] ) ) : ?><p class="o-hours"><?php echo nl2br( esc_html( $o['hours'] ) ); ?></p><?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $shortcode ) : ?>
			<div class="enquiry">
				<h3>Send us an enquiry</h3>
				<?php echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput -- Contact Form 7 output. ?>
			</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $s['show_map'] ) && ! empty( $s['map_query'] ) ) : ?>
		<div class="map">
			<iframe title="Map: <?php echo esc_attr( $s['map_query'] ); ?>" src="https://maps.google.com/maps?q=<?php echo rawurlencode( $s['map_query'] ); ?>&amp;z=15&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
		<?php endif; ?>
	</div>
</section>
