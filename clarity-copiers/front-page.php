<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$uri = get_template_directory_uri();
$mk  = $uri . '/assets/mockup/';

$def_sectors = array(
	array( 'icon' => 'legal',   'title' => 'Legal',          'description' => 'Data security and compliance solutions for law firms and legal practices.' ),
	array( 'icon' => 'hotel',   'title' => 'Hospitality',    'description' => 'Seamless, guest-focused technology experiences for hotels and venues.' ),
	array( 'icon' => 'estate',  'title' => 'Estate Agents',  'description' => 'Cloud solutions enabling on-the-go operations for property professionals.' ),
	array( 'icon' => 'council', 'title' => 'Local Councils', 'description' => 'Secure connectivity and communications for public sector organizations.' ),
);
$def_why = array(
	array( 'icon' => 'clock',   'title' => 'Prompt Repairs',      'description' => 'Quick response times to minimize your business downtime' ),
	array( 'icon' => 'award',   'title' => '30 Years Experience', 'description' => 'Serving businesses since 1995 with unmatched expertise' ),
	array( 'icon' => 'pin',     'title' => 'Local Service',       'description' => 'Based locally with engineers ready to respond quickly' ),
	array( 'icon' => 'printer', 'title' => 'Sharp Main Dealer',   'description' => 'Official Sharp partner providing genuine products and support' ),
);
$def_products = array(
	array( 'image' => $mk . 'product-colour.webp',  'title' => 'Colour MFPs',          'description' => 'High-performance multifunction printers delivering vibrant colour documents', 'link' => '/colour-mfps/' ),
	array( 'image' => $mk . 'product-mono.webp',    'title' => 'Mono MFPs',            'description' => 'Cost-effective black and white printing solutions ideal for high-volume document workflows.', 'link' => '/mono-mfps/' ),
	array( 'image' => $mk . 'product-display.webp', 'title' => 'Interactive Displays', 'description' => 'Sharp Big Pad touchscreen displays transform collaboration with intuitive, interactive meeting-room technology.', 'link' => '/sharp-big-pad/' ),
);
$def_tests = array(
	array( 'quote' => '"I would recommend Clarity Copiers Glamorgan as a supplier and working partner to maintain a good level of service and support."', 'name' => 'Paul Nott', 'role' => 'Technical Projects Manager, HooverCandy Group' ),
	array( 'quote' => '"If we have any problems with our copiers they are always quick to respond and do so in a friendly and professional manner."', 'name' => 'Pauline Williams', 'role' => 'The Welsh Whisky Company' ),
	array( 'quote' => '"Staff are always helpful and courteous and any issues resolved quickly. We have not been disappointed."', 'name' => 'Marged Griffiths', 'role' => 'CEO, Y Bont' ),
);

$img_url = function ( $v ) {
	if ( is_array( $v ) ) { return $v['url'] ?? ''; }
	if ( is_numeric( $v ) ) { return wp_get_attachment_url( (int) $v ); }
	return $v;
};
$hero_video = $img_url( clarity_field( 'hero_video', '' ) );
$poster     = $mk . 'hero-poster.jpg';
?>

<section class="hero" style="background-image:url('<?php echo esc_url( $poster ); ?>')">
	<?php if ( $hero_video ) : ?>
	<video class="hero-video" autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $poster ); ?>">
		<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
	</video>
	<?php endif; ?>
	<div class="hero-inner wrap">
		<span class="pill"><img src="<?php echo esc_url( $mk . 'icon-customer-care.webp' ); ?>" alt=""><?php echo esc_html( clarity_field( 'hero_pill', '24/7 Remote Support Available' ) ); ?></span>
		<h1><?php echo nl2br( esc_html( clarity_field( 'hero_heading', "Empowering Productivity Through\nSharp Technology" ) ) ); ?></h1>
		<p><?php echo esc_html( clarity_field( 'hero_text', 'Managed print and document solutions that save time, reduce waste and keep your business moving. Trusted by businesses across the UK for 30 years.' ) ); ?></p>
		<div class="hero-actions">
			<a class="btn btn-red" href="<?php echo esc_url( clarity_field( 'hero_btn1_url', '/contact/' ) ); ?>"><?php echo esc_html( clarity_field( 'hero_btn1_label', 'Get a Quote' ) ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( clarity_field( 'hero_btn2_url', '/support/' ) ); ?>"><?php echo esc_html( clarity_field( 'hero_btn2_label', 'Get Remote Support' ) ); ?></a>
		</div>
	</div>
</section>

<section class="sectors center">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( clarity_field( 'sectors_heading', 'Expertise Across Sectors' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( clarity_field( 'sectors_intro', 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.' ) ); ?></p>
		<div class="grid4">
			<?php foreach ( clarity_rows( 'sectors', $def_sectors ) as $s ) : ?>
			<div class="icard">
				<div class="ic"><img src="<?php echo esc_url( clarity_mock_icon( $s['icon'] ) ); ?>" alt="" width="43" height="42" loading="lazy" decoding="async"></div>
				<h3><?php echo esc_html( $s['title'] ); ?></h3>
				<p><?php echo esc_html( $s['description'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="why center">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( clarity_field( 'why_heading', 'Why Choose Clarity?' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( clarity_field( 'why_intro', 'Local engineers, genuine Sharp equipment and three decades of experience. Here is why businesses across South Wales choose Clarity Copiers Glamorgan.' ) ); ?></p>
		<div class="grid4">
			<?php foreach ( clarity_rows( 'why_items', $def_why ) as $w ) : ?>
			<div class="icard">
				<div class="ic"><img src="<?php echo esc_url( clarity_mock_icon( $w['icon'] ) ); ?>" alt="" width="43" height="42" loading="lazy" decoding="async"></div>
				<h3><?php echo esc_html( $w['title'] ); ?></h3>
				<p><?php echo esc_html( $w['description'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="team">
	<div class="wrap">
		<?php $team_raw = clarity_field( 'team_image', $mk . 'team.webp' ); echo clarity_img( $team_raw, 'large', array( 'class' => 'team-img', 'alt' => 'The Clarity Copiers team', 'sizes' => '(max-width:1023px) 100vw, 44vw' ) ); ?>
		<div class="team-txt">
			<h2><?php echo esc_html( clarity_field( 'team_heading', 'Meet Our Team' ) ); ?></h2>
			<p><?php echo esc_html( clarity_field( 'team_text', "Led by Warren Dryden, our dedicated team combines decades of experience with a passion for exceptional customer service. We're not just your supplier – we're your technology partner." ) ); ?></p>
			<a class="btn btn-outline" href="<?php echo esc_url( clarity_field( 'team_btn_url', '/our-team/' ) ); ?>"><?php echo esc_html( clarity_field( 'team_btn_label', 'Learn More' ) ); ?></a>
		</div>
	</div>
</section>

<section class="products">
	<div class="wrap">
		<div class="head-row">
			<div>
				<h2 class="h2"><?php echo esc_html( clarity_field( 'products_heading', 'Products & Services' ) ); ?></h2>
				<p class="intro"><?php echo esc_html( clarity_field( 'products_intro', 'Comprehensive Sharp technology solutions designed to meet every business need. From multifunction printers to interactive displays and cloud services.' ) ); ?></p>
			</div>
			<a class="btn btn-outline tall" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">View All</a>
		</div>
		<div class="grid3">
			<?php foreach ( clarity_rows( 'products', $def_products ) as $p ) : ?>
			<a class="pcard" href="<?php echo esc_url( home_url( $p['link'] ) ); ?>">
				<div class="thumb"><?php echo clarity_img( is_array( $p['image'] ) ? ( $p['image']['ID'] ?? $p['image']['url'] ?? '' ) : $p['image'], 'medium_large', array( 'alt' => $p['title'], 'sizes' => '(max-width:1023px) 100vw, 28vw' ) ); ?></div>
				<h3><?php echo esc_html( $p['title'] ); ?></h3>
				<p><?php echo esc_html( $p['description'] ); ?></p>
				<span class="more">Explore more</span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="testimonials center">
	<div class="wrap">
		<h2 class="h2"><?php echo esc_html( clarity_field( 'testimonials_heading', 'What Our Clients Say' ) ); ?></h2>
		<p class="intro"><?php echo esc_html( clarity_field( 'testimonials_intro', "Don't just take our word for it. Here's what our satisfied clients have to say about our service." ) ); ?></p>
		<div class="grid3">
			<?php foreach ( clarity_rows( 'testimonials', $def_tests ) as $t ) : ?>
			<div class="tcard">
				<img class="stars" src="<?php echo esc_url( $mk . 'stars.webp' ); ?>" alt="5 out of 5 stars" width="89" height="89" loading="lazy" decoding="async">
				<blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
				<div class="who"><?php echo esc_html( $t['name'] ); ?></div>
				<div class="role"><?php echo esc_html( $t['role'] ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="news">
	<div class="wrap">
		<div class="head-row">
			<div>
				<h2 class="h2"><?php echo esc_html( clarity_field( 'news_heading', 'Latest News & Insights' ) ); ?></h2>
				<p class="intro"><?php echo esc_html( clarity_field( 'news_intro', 'Stay updated with the latest product releases, industry insights, and company news.' ) ); ?></p>
			</div>
			<a class="btn btn-outline tall" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/news/' ) ); ?>">View All News</a>
		</div>
		<div class="grid3">
			<?php
			$news = get_posts( array( 'numberposts' => 3 ) );
			if ( $news ) :
				foreach ( $news as $post ) :
					setup_postdata( $post );
					?>
			<a class="ncard" href="<?php the_permalink(); ?>">
				<div class="thumb"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( null, 'medium_large', array( 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width:1023px) 100vw, 28vw' ) ) : '<img src="' . esc_url( $mk . 'news-mfp.webp' ) . '" alt="" loading="lazy">'; ?></div>
				<h3><?php the_title(); ?></h3>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<span class="more">Read More</span>
			</a>
					<?php
				endforeach;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
