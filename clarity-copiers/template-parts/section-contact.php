<?php
$s      = $args['s'];
$status = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
$model  = isset( $_GET['model'] ) ? sanitize_text_field( wp_unslash( $_GET['model'] ) ) : '';
$msgs   = array(
	'sent'    => array( 'ok', 'Thank you — your enquiry has been sent. A member of our team will be in touch shortly.' ),
	'invalid' => array( 'err', 'Please complete your name, a valid email address and a message.' ),
	'limited' => array( 'err', 'We have received several enquiries from you recently. Please call us on ' . clarity_opt( 'phone' ) . '.' ),
	'error'   => array( 'err', 'Sorry, something went wrong. Please try again or call us on ' . clarity_opt( 'phone' ) . '.' ),
);
?>
<section class="sec contact-sec" id="enquiry">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="contact-grid<?php echo empty( $s['show_form'] ) ? ' no-form' : ''; ?>">
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

			<?php if ( ! empty( $s['show_form'] ) ) : ?>
			<form class="enquiry" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
				<h3>Send us an enquiry</h3>
				<?php if ( isset( $msgs[ $status ] ) ) : ?>
				<p class="form-msg is-<?php echo esc_attr( $msgs[ $status ][0] ); ?>" role="status"><?php echo esc_html( $msgs[ $status ][1] ); ?></p>
				<?php endif; ?>
				<input type="hidden" name="action" value="clarity_enquiry">
				<input type="hidden" name="ts" value="<?php echo esc_attr( time() ); ?>">
				<input type="hidden" name="back" value="<?php echo esc_url( get_permalink() ); ?>">
				<?php wp_nonce_field( 'clarity_enquiry', 'clarity_nonce', false ); ?>
				<div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
				<div class="f-row">
					<label>Name <span aria-hidden="true">*</span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
					<label>Company<input type="text" name="company" maxlength="120" autocomplete="organization"></label>
				</div>
				<div class="f-row">
					<label>Email <span aria-hidden="true">*</span><input type="email" name="email" required maxlength="150" autocomplete="email"></label>
					<label>Phone<input type="tel" name="phone" maxlength="40" autocomplete="tel"></label>
				</div>
				<label>I'm interested in
					<select name="topic">
						<?php foreach ( array( 'Quote for a new printer / MFP', 'Free print audit', 'Service / repair', 'Toner & supplies', 'Interactive displays', 'Something else' ) as $t ) : ?>
						<option><?php echo esc_html( $t ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label>Message <span aria-hidden="true">*</span><textarea name="message" rows="5" required maxlength="3000"><?php echo $model ? esc_textarea( 'I would like a quote for the Sharp ' . $model . '.' ) : ''; ?></textarea></label>
				<p class="f-note">We'll only use your details to respond to this enquiry. See our <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">privacy policy</a>.</p>
				<button class="btn btn-red" type="submit">Send enquiry</button>
			</form>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $s['show_map'] ) && ! empty( $s['map_query'] ) ) : ?>
		<div class="map">
			<iframe title="Map: <?php echo esc_attr( $s['map_query'] ); ?>" src="https://maps.google.com/maps?q=<?php echo rawurlencode( $s['map_query'] ); ?>&amp;z=15&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
		<?php endif; ?>
	</div>
</section>
