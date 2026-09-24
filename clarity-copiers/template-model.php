<?php
/**
 * Template Name: Sharp model
 * Template Post Type: page
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	$id    = get_the_ID();
	$code  = clarity_get( 'model_code' ) ?: get_the_title();
	$range = clarity_get( 'model_range' );
	$mode  = clarity_get( 'colour_mode' );
	$ppm   = clarity_get( 'speed_ppm' );
	$paper = clarity_get( 'paper_size' );
	$sum   = clarity_get( 'summary' );
	$fns   = array_filter( (array) ( clarity_get( 'functions' ) ?: array() ) );
	$feats = array_filter( (array) ( clarity_get( 'features' ) ?: array() ), function ( $f ) { return ! empty( $f['text'] ); } );
	$phone = clarity_opt( 'phone' );
	clarity_page_hero( array( 'subtitle' => $range ? 'Sharp ' . $range . ( 'colour' === $mode ? ' · Colour' : ' · Black & white' ) : '' ) );
	?>
<section class="sec bg-white model-top">
	<div class="wrap model-grid">
		<div class="model-media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => 'Sharp ' . $code, 'sizes' => '(max-width:1023px) 90vw, 40vw' ) );
			}
			?>
		</div>
		<div class="model-info">
			<?php if ( $range ) : ?><p class="eyebrow"><?php echo esc_html( $range ); ?></p><?php endif; ?>
			<p class="h2 model-title">Sharp <?php echo esc_html( $code ); ?></p>
			<ul class="badges is-lg">
				<?php if ( $ppm ) : ?><li><?php echo (int) $ppm; ?> ppm</li><?php endif; ?>
				<?php if ( $paper ) : ?><li>Up to <?php echo esc_html( $paper ); ?></li><?php endif; ?>
				<?php if ( $mode ) : ?><li><?php echo 'colour' === $mode ? 'Colour' : 'Black &amp; white'; ?></li><?php endif; ?>
			</ul>
			<?php if ( $sum ) : ?><p class="model-sum"><?php echo esc_html( $sum ); ?></p><?php endif; ?>
			<?php if ( $fns ) : ?>
			<ul class="fn-list">
				<?php foreach ( array( 'print' => 'Print', 'copy' => 'Copy', 'scan' => 'Scan', 'fax' => 'Fax' ) as $k => $l ) : ?>
				<li class="<?php echo in_array( $k, $fns, true ) ? 'on' : 'off'; ?>"><?php echo esc_html( $l ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<div class="btns">
				<a class="btn btn-red" href="<?php echo esc_url( add_query_arg( 'your-message', rawurlencode( 'I would like a quote for the Sharp ' . $code . '.' ), home_url( '/contact/' ) ) . '#enquiry' ); ?>">Request a Quote</a>
				<a class="btn btn-outline" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>">Call <?php echo esc_html( $phone ); ?></a>
			</div>
		</div>
	</div>
</section>

<section class="sec bg-beige">
	<div class="wrap model-detail">
		<div class="rte">
			<h2 class="h2">Overview</h2>
			<?php the_content(); ?>
		</div>
		<?php if ( $feats ) : ?>
		<aside class="spec-card">
			<h3>Key features</h3>
			<ul class="checks">
				<?php foreach ( $feats as $f ) : ?><li><?php echo esc_html( $f['text'] ?? '' ); ?></li><?php endforeach; ?>
			</ul>
			<table class="spec-table">
				<?php if ( $ppm ) : ?><tr><th>Speed</th><td><?php echo (int) $ppm; ?> pages per minute</td></tr><?php endif; ?>
				<?php if ( $paper ) : ?><tr><th>Paper size</th><td>Up to <?php echo esc_html( $paper ); ?></td></tr><?php endif; ?>
				<?php if ( $mode ) : ?><tr><th>Output</th><td><?php echo 'colour' === $mode ? 'Colour &amp; mono' : 'Black &amp; white'; ?></td></tr><?php endif; ?>
				<?php if ( $fns ) : ?><tr><th>Functions</th><td><?php echo esc_html( implode( ', ', array_map( 'ucfirst', $fns ) ) ); ?></td></tr><?php endif; ?>
			</table>
		</aside>
		<?php endif; ?>
	</div>
</section>

	<?php
	$parent = wp_get_post_parent_id( $id );
	if ( $parent ) {
		$siblings = get_posts( array( 'post_type' => 'page', 'post_parent' => $parent, 'post__not_in' => array( $id ), 'posts_per_page' => 3, 'orderby' => 'rand', 'no_found_rows' => true ) );
		if ( $siblings ) {
			echo '<section class="sec bg-white"><div class="wrap">';
			clarity_section_head( 'You may also like', 'Other models in the ' . get_the_title( $parent ) . ' range.' );
			echo '<div class="cgrid cols-3">';
			foreach ( $siblings as $m ) { get_template_part( 'template-parts/model', 'card', array( 'post' => $m ) ); }
			echo '</div></div></section>';
		}
	}
	clarity_default_cta();
endwhile;
get_footer();
