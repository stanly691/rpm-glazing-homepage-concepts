<?php
$s    = $args['s'];
$cols = in_array( (string) ( $s['columns'] ?? '4' ), array( '2', '3', '4' ), true ) ? (string) $s['columns'] : '4';
?>
<section class="sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="cgrid cols-<?php echo esc_attr( $cols ); ?>">
			<?php foreach ( (array) ( $s['cards'] ?? array() ) as $c ) : ?>
			<div class="icard is-auto">
				<?php if ( ! empty( $c['icon'] ) ) : ?><div class="ic"><img src="<?php echo esc_url( clarity_mock_icon( $c['icon'] ) ); ?>" alt="" width="43" height="42" loading="lazy" decoding="async"></div><?php endif; ?>
				<h3><?php echo esc_html( $c['title'] ?? '' ); ?></h3>
				<?php if ( ! empty( $c['text'] ) ) : ?><p><?php echo esc_html( $c['text'] ); ?></p><?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
