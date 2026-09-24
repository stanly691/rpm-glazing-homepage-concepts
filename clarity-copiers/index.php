<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
if ( is_home() ) {
	$t = get_the_title( get_option( 'page_for_posts' ) );
	$title = $t ? $t : 'News';
	$sub   = 'Product releases, industry insights and company news from Clarity Copiers Glamorgan.';
} elseif ( is_search() ) {
	$title = 'Search results';
	$sub   = 'Results for “' . get_search_query() . '”';
} else {
	$title = wp_strip_all_tags( get_the_archive_title() );
	$sub   = '';
}
clarity_page_hero( array( 'title' => $title, 'subtitle' => $sub, 'image' => 0, 'crumbs' => array( array( 'Home', home_url( '/' ) ), array( $title, '' ) ) ) );
?>
<section class="sec bg-white news">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<?php if ( is_home() ) { clarity_section_head( 'Latest articles', '' ); } ?>
		<div class="cgrid cols-3">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
			<a class="ncard is-auto" href="<?php the_permalink(); ?>">
				<div class="thumb"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( null, 'medium_large', array( 'loading' => 'lazy', 'sizes' => '(max-width:1023px) 90vw, 28vw' ) ) : clarity_img( $mk . 'news-mfp.webp', 'full', array( 'alt' => '' ) ); ?></div>
				<h3><?php the_title(); ?></h3>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<span class="more">Read More</span>
			</a>
			<?php endwhile; ?>
		</div>
		<div class="pager"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
		<?php else : ?>
		<p class="intro">Nothing found.</p>
		<?php endif; ?>
	</div>
</section>
<?php
clarity_default_cta();
get_footer();
