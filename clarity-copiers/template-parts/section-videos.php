<?php
$s     = $args['s'];
$items = array();
foreach ( (array) ( $s['items'] ?? array() ) as $v ) {
	$raw = trim( (string) ( $v['youtube_id'] ?? '' ) );
	if ( preg_match( '/^vimeo:(\d{5,12})$/i', $raw, $m ) ) {
		$items[] = array( 'provider' => 'vimeo', 'id' => $m[1], 'title' => $v['title'] ?? '' );
	} elseif ( $id = preg_replace( '/[^A-Za-z0-9_-]/', '', $raw ) ) {
		$items[] = array( 'provider' => 'youtube', 'id' => $id, 'title' => $v['title'] ?? '' );
	}
}
$count = count( $items );
if ( ! $count ) { return; }
?>
<section class="sec bg-white">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<div class="cgrid cols-3 video-grid<?php echo 1 === $count ? ' is-single' : ''; ?><?php echo $count >= 8 ? ' is-library' : ''; ?>">
			<?php foreach ( $items as $it ) :
				$single = 1 === $count;
				if ( 'vimeo' === $it['provider'] ) {
					$thumb = clarity_vimeo_thumb( $it['id'] );
					$w = 640; $h = 360;
				} else {
					$thumb = 'https://i.ytimg.com/vi/' . $it['id'] . '/' . ( $single ? 'maxresdefault' : 'hqdefault' ) . '.jpg';
					$w = $single ? 1280 : 480; $h = $single ? 720 : 360;
				}
				?>
			<figure class="video">
				<button class="video-facade" type="button" data-<?php echo 'vimeo' === $it['provider'] ? 'vimeo' : 'yt'; ?>="<?php echo esc_attr( $it['id'] ); ?>" aria-label="Play video: <?php echo esc_attr( $it['title'] ); ?>">
					<?php if ( $thumb ) : ?><img src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy" decoding="async" width="<?php echo (int) $w; ?>" height="<?php echo (int) $h; ?>"><?php endif; ?>
					<span class="play" aria-hidden="true"></span>
				</button>
				<figcaption><?php echo esc_html( $it['title'] ); ?></figcaption>
			</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
