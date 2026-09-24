<?php
/**
 * Template helpers shared by page templates and sections.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Responsive image from an attachment ID (or a plain URL fallback).
 * Lazy + async by default; pass array( 'loading' => 'eager', 'fetchpriority' => 'high' ) for LCP images.
 */
function clarity_img( $img, $size = 'large', $attrs = array() ) {
	$attrs = array_merge( array( 'loading' => 'lazy', 'decoding' => 'async' ), $attrs );
	if ( is_numeric( $img ) && (int) $img > 0 ) {
		return wp_get_attachment_image( (int) $img, $size, false, $attrs );
	}
	if ( is_string( $img ) && $img ) {
		$html = '';
		foreach ( $attrs as $k => $v ) {
			$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
		return '<img src="' . esc_url( $img ) . '"' . $html . '>';
	}
	return '';
}

/** Attachment URL helper (ID or URL in, URL out). */
function clarity_img_url( $img, $size = 'full' ) {
	if ( is_numeric( $img ) ) {
		$src = wp_get_attachment_image_url( (int) $img, $size );
		return $src ? $src : '';
	}
	return is_string( $img ) ? $img : '';
}

/** Render a row of buttons from an ACF buttons repeater. */
function clarity_buttons( $buttons, $class = '' ) {
	if ( empty( $buttons ) || ! is_array( $buttons ) ) { return; }
	echo '<div class="btns ' . esc_attr( $class ) . '">';
	foreach ( $buttons as $b ) {
		if ( empty( $b['label'] ) ) { continue; }
		$style = ( isset( $b['style'] ) && 'outline' === $b['style'] ) ? 'btn-outline' : 'btn-red';
		printf( '<a class="btn %s" href="%s">%s</a>', esc_attr( $style ), esc_url( $b['url'] ?? '#' ), esc_html( $b['label'] ) );
	}
	echo '</div>';
}

/** Section heading + intro. */
function clarity_section_head( $heading, $intro = '', $center = true ) {
	if ( ! $heading && ! $intro ) { return; }
	echo '<div class="sec-head' . ( $center ? ' is-center' : '' ) . '">';
	if ( $heading ) { echo '<h2 class="h2">' . esc_html( $heading ) . '</h2>'; }
	if ( $intro ) { echo '<p class="intro">' . esc_html( $intro ) . '</p>'; }
	echo '</div>';
}

/** Breadcrumb trail items for the current singular page/post. */
function clarity_breadcrumb_items( $post = null ) {
	$post  = get_post( $post );
	$items = array( array( 'Home', home_url( '/' ) ) );
	if ( ! $post ) { return $items; }
	if ( 'post' === $post->post_type ) {
		$blog = (int) get_option( 'page_for_posts' );
		if ( $blog ) { $items[] = array( get_the_title( $blog ), get_permalink( $blog ) ); }
	}
	foreach ( array_reverse( get_post_ancestors( $post ) ) as $a ) {
		$items[] = array( get_the_title( $a ), get_permalink( $a ) );
	}
	$items[] = array( get_the_title( $post ), get_permalink( $post ) );
	return $items;
}

/**
 * Inner-page hero: background photo under the brand overlay, breadcrumbs, H1, subtitle.
 * The header floats over it exactly like the homepage hero.
 */
function clarity_page_hero( $args = array() ) {
	$post     = is_singular() ? get_queried_object() : null;
	$explicit = array_key_exists( 'image', $args );
	$args  = wp_parse_args( $args, array(
		'title'    => $post ? get_the_title( $post ) : '',
		'subtitle' => $post && function_exists( 'get_field' ) ? get_field( 'hero_subtitle', $post->ID ) : '',
		'image'    => $post && function_exists( 'get_field' ) ? get_field( 'hero_image', $post->ID ) : 0,
		'crumbs'   => $post ? clarity_breadcrumb_items( $post ) : array( array( 'Home', home_url( '/' ) ) ),
	) );
	if ( ! $explicit && ! $args['image'] && $post && 'page' === $post->post_type && 'template-model.php' !== get_page_template_slug( $post ) && has_post_thumbnail( $post ) ) {
		$args['image'] = get_post_thumbnail_id( $post );
	}
	$bg_attrs = array( 'class' => 'page-hero-bg', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' );
	$bg_html  = is_numeric( $args['image'] ) && (int) $args['image'] > 0
		? clarity_img( $args['image'], 'full', $bg_attrs )
		: clarity_img( get_template_directory_uri() . '/assets/mockup/hero-poster.jpg', 'full', $bg_attrs );
	?>
	<section class="page-hero">
		<?php echo $bg_html; // phpcs:ignore WordPress.Security.EscapeOutput -- built by wp_get_attachment_image / escaped in clarity_img(). ?>
		<div class="wrap page-hero-inner">
			<nav class="crumbs" aria-label="Breadcrumb"><ol>
				<?php
				$last = count( $args['crumbs'] ) - 1;
				foreach ( $args['crumbs'] as $i => $c ) {
					echo '<li>' . ( $i === $last ? '<span aria-current="page">' . esc_html( $c[0] ) . '</span>' : '<a href="' . esc_url( $c[1] ) . '">' . esc_html( $c[0] ) . '</a>' ) . '</li>';
				}
				?>
			</ol></nav>
			<h1><?php echo esc_html( $args['title'] ); ?></h1>
			<?php if ( $args['subtitle'] ) : ?><p class="page-hero-sub"><?php echo esc_html( $args['subtitle'] ); ?></p><?php endif; ?>
		</div>
	</section>
	<?php
}

/** Render every flexible-content section of a page. Returns false when none. */
function clarity_render_sections( $post_id = null ) {
	if ( ! function_exists( 'have_rows' ) ) { return false; }
	$post_id = $post_id ? $post_id : get_the_ID();
	$rows    = get_field( 'sections', $post_id );
	if ( empty( $rows ) || ! is_array( $rows ) ) { return false; }
	foreach ( $rows as $i => $s ) {
		$layout = sanitize_key( $s['acf_fc_layout'] ?? '' );
		if ( ! $layout ) { continue; }
		$s['_index'] = $i;
		get_template_part( 'template-parts/section', $layout, array( 's' => $s ) );
	}
	return true;
}

/** Default closing call-to-action used when a page has no sections of its own. */
function clarity_default_cta() {
	get_template_part( 'template-parts/section', 'cta', array( 's' => array(
		'heading' => 'Ready to cut your print costs?',
		'text'    => 'Talk to our local team about the right Sharp solution, a free print audit or a no-obligation quote.',
		'buttons' => array(
			array( 'label' => 'Get a Quote', 'url' => '/contact/', 'style' => 'red' ),
			array( 'label' => 'Call ' . clarity_opt( 'phone' ), 'url' => 'tel:' . preg_replace( '/\s+/', '', clarity_opt( 'phone' ) ), 'style' => 'outline' ),
		),
	) ) );
}
