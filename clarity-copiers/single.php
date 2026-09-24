<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	?>
<section class="page-hero">
	<div class="wrap">
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">News</a></div>
		<h1><?php the_title(); ?></h1>
	</div>
</section>
<article class="entry">
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'large' );
	}
	the_content();
	?>
</article>
	<?php
endwhile;
get_footer();
