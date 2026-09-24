<?php
$s    = $args['s'];
$side = ( $s['side'] ?? 'left' ) === 'right' ? ' img-right' : '';
$fit  = ( $s['fit'] ?? 'cover' ) === 'contain' ? ' fit-contain' : '';
?>
<section class="sec split bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap split-grid<?php echo esc_attr( $side . $fit ); ?>">
		<?php if ( ! empty( $s['image'] ) ) : ?>
		<div class="split-media"><?php echo clarity_img( $s['image'], 'large', array( 'sizes' => '(max-width:1023px) 100vw, 44vw' ) ); ?></div>
		<?php endif; ?>
		<div class="split-txt">
			<?php if ( ! empty( $s['eyebrow'] ) ) : ?><p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $s['heading'] ) ) : ?><h2 class="h2"><?php echo esc_html( $s['heading'] ); ?></h2><?php endif; ?>
			<div class="rte"><?php echo wp_kses_post( $s['text'] ?? '' ); ?></div>
			<?php clarity_buttons( $s['buttons'] ?? array() ); ?>
		</div>
	</div>
</section>
