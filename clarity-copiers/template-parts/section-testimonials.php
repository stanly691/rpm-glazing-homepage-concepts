<?php
$s    = $args['s'];
$mk   = get_template_directory_uri() . '/assets/mockup/';
?>
<section class="sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="cgrid cols-3 tgrid">
			<?php foreach ( (array) ( $s['items'] ?? array() ) as $t ) : ?>
			<figure class="tcard is-auto">
				<img class="stars" src="<?php echo esc_url( $mk . 'stars.webp' ); ?>" alt="5 out of 5 stars" width="89" height="89" loading="lazy">
				<blockquote><?php echo esc_html( $t['quote'] ?? '' ); ?></blockquote>
				<figcaption><span class="who"><?php echo esc_html( $t['name'] ?? '' ); ?></span><span class="role"><?php echo esc_html( $t['role'] ?? '' ); ?></span></figcaption>
			</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
