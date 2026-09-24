<?php
/**
 * Default inner page: hero + ACF sections (falls back to editor content).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	clarity_page_hero();
	if ( post_password_required() || ! clarity_render_sections() ) :
		?>
	<section class="sec bg-white"><div class="wrap narrow rte"><?php the_content(); ?></div></section>
		<?php
		clarity_default_cta();
	endif;
endwhile;
get_footer();
