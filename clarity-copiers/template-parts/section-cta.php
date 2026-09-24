<?php $s = $args['s']; ?>
<section class="cta-band">
	<div class="wrap cta-inner">
		<div>
			<h2><?php echo esc_html( $s['heading'] ?? '' ); ?></h2>
			<?php if ( ! empty( $s['text'] ) ) : ?><p><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
		</div>
		<?php clarity_buttons( $s['buttons'] ?? array(), 'on-red' ); ?>
	</div>
</section>
