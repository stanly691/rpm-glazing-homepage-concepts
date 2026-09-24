<?php $s = $args['s']; ?>
<section class="sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<ol class="steps">
			<?php foreach ( (array) ( $s['steps'] ?? array() ) as $i => $st ) : ?>
			<li class="step"><span class="step-n"><?php echo (int) $i + 1; ?></span><h3><?php echo esc_html( $st['title'] ?? '' ); ?></h3><p><?php echo esc_html( $st['text'] ?? '' ); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
