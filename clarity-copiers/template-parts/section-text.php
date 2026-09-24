<?php $s = $args['s']; ?>
<section class="sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap narrow">
		<?php if ( ! empty( $s['heading'] ) ) : ?><h2 class="h2"><?php echo esc_html( $s['heading'] ); ?></h2><?php endif; ?>
		<div class="rte"><?php echo wp_kses_post( $s['text'] ?? '' ); ?></div>
	</div>
</section>
