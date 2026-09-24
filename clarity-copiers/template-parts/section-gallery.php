<?php $s = $args['s']; ?>
<section class="sec bg-white">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="gallery-grid" data-lightbox>
			<?php foreach ( (array) ( $s['images'] ?? array() ) as $id ) :
				$full = wp_get_attachment_image_url( (int) $id, 'full' ); ?>
			<a class="gallery-item" href="<?php echo esc_url( $full ); ?>" aria-label="<?php echo esc_attr( trim( 'View larger image: ' . get_post_meta( (int) $id, '_wp_attachment_image_alt', true ), ': ' ) ); ?>"><?php echo clarity_img( $id, 'medium_large', array( 'sizes' => '(max-width:560px) 90vw, (max-width:1023px) 45vw, 30vw' ) ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
