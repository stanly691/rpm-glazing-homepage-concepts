<?php
$m     = $args['post'];
$code  = clarity_get( 'model_code', $m->ID ) ?: get_the_title( $m );
$ppm   = clarity_get( 'speed_ppm', $m->ID );
$paper = clarity_get( 'paper_size', $m->ID );
$mode  = clarity_get( 'colour_mode', $m->ID );
$range = clarity_get( 'model_range', $m->ID );
$sum   = clarity_get( 'summary', $m->ID );
?>
<a class="mcard" href="<?php echo esc_url( get_permalink( $m ) ); ?>">
	<div class="mcard-img"><?php echo get_the_post_thumbnail( $m, 'medium_large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => 'Sharp ' . $code, 'sizes' => '(max-width:1023px) 90vw, 26vw' ) ); ?></div>
	<div class="mcard-body">
		<?php if ( $range ) : ?><p class="eyebrow"><?php echo esc_html( $range ); ?></p><?php endif; ?>
		<h3><?php echo esc_html( $code ); ?></h3>
		<ul class="badges">
			<?php if ( $ppm ) : ?><li><?php echo (int) $ppm; ?> ppm</li><?php endif; ?>
			<?php if ( $paper && false === stripos( (string) $range, (string) $paper ) ) : ?><li><?php echo esc_html( $paper ); ?></li><?php endif; ?>
			<?php if ( $mode ) : ?><li><?php echo 'colour' === $mode ? 'Colour' : 'Mono'; ?></li><?php endif; ?>
		</ul>
		<?php if ( $sum ) : ?><p class="mcard-sum"><?php echo esc_html( $sum ); ?></p><?php endif; ?>
		<span class="more">View details</span>
	</div>
</a>
