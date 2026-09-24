<?php
$s     = $args['s'];
$count = count( array_filter( (array) ( $s['items'] ?? array() ), function ( $v ) { return ! empty( $v['youtube_id'] ); } ) );
?>
<section class="sec">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="cgrid cols-3 video-grid<?php echo 1 === $count ? ' is-single' : ''; ?>">
			<?php foreach ( (array) ( $s['items'] ?? array() ) as $v ) :
				$id = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) ( $v['youtube_id'] ?? '' ) );
				if ( ! $id ) { continue; } ?>
			<figure class="video">
				<button class="video-facade" type="button" data-yt="<?php echo esc_attr( $id ); ?>" aria-label="Play video: <?php echo esc_attr( $v['title'] ?? '' ); ?>">
					<img src="https://i.ytimg.com/vi/<?php echo esc_attr( $id ); ?>/hqdefault.jpg" alt="" loading="lazy" decoding="async" width="480" height="360">
					<span class="play" aria-hidden="true"></span>
				</button>
				<figcaption><?php echo esc_html( $v['title'] ?? '' ); ?></figcaption>
			</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
