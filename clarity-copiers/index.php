<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$title = is_home() ? 'News & Insights' : ( is_search() ? 'Search Results' : ( is_archive() ? get_the_archive_title() : 'Blog' ) );
?>
<section class="page-hero">
	<div class="container">
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( wp_strip_all_tags( $title ) ); ?></div>
		<h1><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></h1>
	</div>
</section>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
		<div class="grid grid-3">
			<?php while ( have_posts() ) : the_post(); ?>
			<a class="ncard" href="<?php the_permalink(); ?>">
				<div class="thumb"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( null, 'medium_large' ) : '<img src="' . esc_url( get_template_directory_uri() . '/assets/colour-mfp.png' ) . '" alt="">'; ?></div>
				<div class="body">
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
					<span class="link-more">Read More →</span>
				</div>
			</a>
			<?php endwhile; ?>
		</div>
		<div style="margin-top:40px;text-align:center"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
		<p>No posts found.</p>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
