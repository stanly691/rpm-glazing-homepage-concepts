<?php $s = $args['s']; ?>
<section class="sec">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<?php foreach ( (array) ( $s['groups'] ?? array() ) as $g ) : ?>
		<div class="team-group">
			<?php if ( ! empty( $g['title'] ) ) : ?><h3 class="group-title"><?php echo esc_html( $g['title'] ); ?></h3><?php endif; ?>
			<div class="cgrid cols-4 team-cards">
				<?php foreach ( (array) ( $g['members'] ?? array() ) as $mb ) : ?>
				<figure class="member-card">
					<div class="member-photo">
						<?php
						if ( ! empty( $mb['photo'] ) ) {
							echo clarity_img( $mb['photo'], 'medium_large', array( 'alt' => $mb['name'] ?? '', 'sizes' => '(max-width:560px) 90vw, (max-width:1023px) 45vw, 20vw' ) );
						} else {
							$initials = implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, array_slice( preg_split( '/\s+/', trim( $mb['name'] ?? '' ) ), 0, 2 ) ) );
							echo '<span class="member-initials" aria-hidden="true">' . esc_html( mb_strtoupper( $initials ) ) . '</span>';
						}
						?>
					</div>
					<figcaption><strong><?php echo esc_html( $mb['name'] ?? '' ); ?></strong><span><?php echo esc_html( $mb['role'] ?? '' ); ?></span></figcaption>
				</figure>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
