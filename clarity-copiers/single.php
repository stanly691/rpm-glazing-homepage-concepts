<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	clarity_page_hero( array( 'subtitle' => get_the_date(), 'image' => 0 ) );
	?>
<article class="sec bg-white">
	<div class="wrap narrow rte post-body">
		<?php if ( has_post_thumbnail() ) : ?>
		<figure class="post-feature"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'sizes' => '(max-width:1100px) 92vw, 1100px' ) ); ?></figure>
		<?php endif; ?>
		<?php the_content(); ?>
	</div>
</article>
	<?php
	$more = get_posts( array( 'numberposts' => 3, 'post__not_in' => array( get_the_ID() ), 'no_found_rows' => true ) );
	if ( $more ) :
		?>
<section class="sec bg-beige news">
	<div class="wrap">
		<?php clarity_section_head( 'More news & insights' ); ?>
		<div class="cgrid cols-3">
			<?php foreach ( $more as $p ) : ?>
			<a class="ncard is-auto" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
				<div class="thumb"><?php echo has_post_thumbnail( $p ) ? get_the_post_thumbnail( $p, 'medium_large', array( 'loading' => 'lazy', 'sizes' => '(max-width:1023px) 90vw, 28vw' ) ) : clarity_img( get_template_directory_uri() . '/assets/mockup/news-mfp.webp', 'full', array( 'alt' => '' ) ); ?></div>
				<h3><?php echo esc_html( get_the_title( $p ) ); ?></h3>
				<p><?php echo esc_html( get_the_excerpt( $p ) ); ?></p>
				<span class="more">Read More</span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
		<?php
	endif;
	clarity_default_cta();
endwhile;
get_footer();
