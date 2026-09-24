<?php $s = $args['s']; ?>
<section class="sec">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="gallery-grid" data-lightbox>
			<?php foreach ( (array) ( $s['images'] ?? array() ) as $id ) :
				$full = wp_get_attachment_image_url( (int) $id, 'full' ); ?>
			<a class="gallery-item" href="<?php echo esc_url( $full ); ?>"><?php echo clarity_img( $id, 'medium_large', array( 'sizes' => '(max-width:560px) 90vw, (max-width:1023px) 45vw, 30vw' ) ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
