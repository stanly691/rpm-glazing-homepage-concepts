<?php $s = $args['s']; ?>
<section class="sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="cgrid cols-3">
			<?php foreach ( (array) ( $s['cards'] ?? array() ) as $c ) : ?>
			<a class="pcard is-auto" href="<?php echo esc_url( $c['link'] ?? '#' ); ?>">
				<div class="thumb"><?php echo clarity_img( $c['image'] ?? '', 'large', array( 'sizes' => '(max-width:1023px) 100vw, 28vw', 'alt' => $c['title'] ?? '' ) ); ?></div>
				<h3><?php echo esc_html( $c['title'] ?? '' ); ?></h3>
				<?php if ( ! empty( $c['text'] ) ) : ?><p><?php echo esc_html( $c['text'] ); ?></p><?php endif; ?>
				<span class="more"><?php echo esc_html( ! empty( $c['link_label'] ) ? $c['link_label'] : 'Explore more' ); ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
