<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
clarity_page_hero( array( 'title' => 'Page not found', 'subtitle' => 'The page you were looking for has moved or no longer exists.', 'image' => 0, 'crumbs' => array( array( 'Home', home_url( '/' ) ), array( 'Page not found', '' ) ) ) );
?>
<section class="sec bg-white">
	<div class="wrap narrow is-center-text">
		<p class="intro">Try one of these instead:</p>
		<div class="btns is-center">
			<a class="btn btn-red" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">Products</a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
		</div>
	</div>
</section>
<?php
get_footer();
