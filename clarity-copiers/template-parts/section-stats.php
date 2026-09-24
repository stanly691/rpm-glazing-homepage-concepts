<?php $s = $args['s']; ?>
<section class="stats">
	<div class="wrap stats-grid">
		<?php foreach ( (array) ( $s['items'] ?? array() ) as $it ) : ?>
		<div class="stat"><strong><?php echo esc_html( $it['value'] ?? '' ); ?></strong><span><?php echo esc_html( $it['label'] ?? '' ); ?></span></div>
		<?php endforeach; ?>
	</div>
</section>
