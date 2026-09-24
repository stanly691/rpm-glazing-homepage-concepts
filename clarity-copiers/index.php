<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$mk    = get_template_directory_uri() . '/assets/mockup/';
$title = is_home() ? 'News & Insights' : ( is_search() ? 'Search Results' : ( is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : 'News' ) );
?>
<section class="page-hero">
	<div class="wrap">
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo esc_html( $title ); ?></div>
		<h1><?php echo esc_html( $title ); ?></h1>
	</div>
</section>
<section class="news index-grid">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<div class="grid3">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
			<a class="ncard" href="<?php the_permalink(); ?>">
				<div class="thumb"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( null, 'large' ) : '<img src="' . esc_url( $mk . 'news-mfp.webp' ) . '" alt="">'; ?></div>
				<h3><?php the_title(); ?></h3>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<span class="more">Read More</span>
			</a>
			<?php endwhile; ?>
		</div>
		<div style="margin-top:40px;text-align:center"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
		<p>No posts found.</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
