<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<section class="page-hero">
	<div class="container">
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php the_title(); ?></div>
		<h1><?php the_title(); ?></h1>
	</div>
</section>
<article class="entry container">
	<?php
	if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); }
	the_content();
	?>
</article>
<?php endwhile; get_footer(); ?>
