<?php
$s      = $args['s'];
$parent = (int) ( $s['parent'] ?? 0 );
$range  = trim( (string) ( $s['range'] ?? '' ) );
if ( ! $parent ) { return; }
$q = array( 'post_type' => 'page', 'post_parent' => $parent, 'posts_per_page' => 60, 'orderby' => 'menu_order title', 'order' => 'ASC', 'no_found_rows' => true );
if ( $range ) { $q['meta_query'] = array( array( 'key' => 'model_range', 'value' => array_map( 'trim', explode( ',', $range ) ), 'compare' => 'IN' ) ); }
$models = get_posts( $q );
if ( ! $models ) { return; }
?>
<section class="sec model-grid-sec bg-<?php echo esc_attr( $s['bg'] ?? 'white' ); ?>">
	<div class="wrap">
		<?php clarity_section_head( $s['heading'] ?? '', $s['intro'] ?? '' ); ?>
		<?php $n = count( $models ); /* 4-up only when rows fill evenly; 5 or 6 read better as 3 + 2 / 3 + 3 */ ?>
		<div class="cgrid cols-3<?php echo ( 0 === $n % 4 || 7 === $n ) ? ' is-4up' : ''; ?><?php echo ( $range && false === strpos( $range, ',' ) ) ? ' is-filtered' : ''; ?>">
			<?php foreach ( $models as $m ) { get_template_part( 'template-parts/model', 'card', array( 'post' => $m ) ); } ?>
		</div>
	</div>
</section>
