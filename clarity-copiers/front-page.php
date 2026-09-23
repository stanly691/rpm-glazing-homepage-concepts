<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$uri = get_template_directory_uri();

$def_sectors = array(
	array( 'icon' => 'legal',  'title' => 'Legal',         'description' => 'Data security and compliance solutions for law firms and legal practices.' ),
	array( 'icon' => 'hotel',  'title' => 'Hospitality',   'description' => 'Seamless, guest-focused technology experiences for hotels and venues.' ),
	array( 'icon' => 'estate', 'title' => 'Estate Agents', 'description' => 'Cloud solutions enabling on-the-go operations for property professionals.' ),
	array( 'icon' => 'council','title' => 'Local Councils', 'description' => 'Secure connectivity and communications for public sector organizations.' ),
);
$def_why = array(
	array( 'icon' => 'clock',   'title' => 'Prompt Repairs',      'description' => 'Quick response times to minimize your business downtime.' ),
	array( 'icon' => 'award',   'title' => '30 Years Experience', 'description' => 'Serving businesses since 1995 with unmatched expertise.' ),
	array( 'icon' => 'pin',     'title' => 'Local Service',       'description' => 'Based locally with engineers ready to respond quickly.' ),
	array( 'icon' => 'printer', 'title' => 'Sharp Main Dealer',   'description' => 'Official Sharp partner providing genuine products and support.' ),
);
$def_products = array(
	array( 'image' => $uri . '/assets/colour-mfp.png', 'title' => 'Colour MFPs',         'description' => 'High-performance multifunction printers delivering vibrant colour documents.', 'link' => '/colour-mfps/' ),
	array( 'image' => $uri . '/assets/mono-mfp.png',   'title' => 'Mono MFPs',           'description' => 'Cost-effective black and white printing solutions ideal for high-volume document workflows.', 'link' => '/mono-mfps/' ),
	array( 'image' => $uri . '/assets/bigpad.png',     'title' => 'Interactive Displays', 'description' => 'Sharp Big Pad touchscreen displays transform collaboration with intuitive tools.', 'link' => '/sharp-big-pad/' ),
);
$def_tests = array(
	array( 'quote' => 'Fantastic service! Would highly recommend. The team are knowledgeable and responsive to our needs.', 'name' => 'Sarah Johnson', 'role' => 'Operations Director', 'rating' => 5 ),
	array( 'quote' => 'We have used this company for over 10 years. Excellent support and they really understand our business.', 'name' => 'Michael Chen', 'role' => 'IT Manager', 'rating' => 5 ),
	array( 'quote' => 'Professional service from start to finish. The installation was seamless and the ongoing support is excellent.', 'name' => 'Emma Williams', 'role' => 'Finance Director', 'rating' => 5 ),
);

$team_img = clarity_field( 'team_image', $uri . '/assets/team.jpg' );
?>

<!-- HERO -->
<section class="hero">
	<div class="container">
		<div class="hero-inner">
			<span class="pill"><span class="dot"></span> <?php echo esc_html( clarity_field( 'hero_pill', '24/7 Remote Support Available' ) ); ?></span>
			<h1><?php echo esc_html( clarity_field( 'hero_heading', 'Empowering Productivity Through Sharp Technology' ) ); ?></h1>
			<p><?php echo esc_html( clarity_field( 'hero_text', 'Managed print and document solutions that save time, reduce waste and keep your business moving. Trusted by businesses across the UK for 30 years.' ) ); ?></p>
			<div class="hero-actions">
				<a class="btn btn--red" href="<?php echo esc_url( clarity_field( 'hero_btn1_url', home_url( '/contact/' ) ) ); ?>"><?php echo esc_html( clarity_field( 'hero_btn1_label', 'Get a Quote' ) ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( clarity_field( 'hero_btn2_url', home_url( '/support/' ) ) ); ?>"><?php echo esc_html( clarity_field( 'hero_btn2_label', 'Get Remote Support' ) ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- EXPERTISE ACROSS SECTORS -->
<section class="section">
	<div class="container">
		<div class="section-head">
			<h2><?php echo esc_html( clarity_field( 'sectors_heading', 'Expertise Across Sectors' ) ); ?></h2>
			<p><?php echo esc_html( clarity_field( 'sectors_intro', 'We understand that every industry has unique challenges. Our tailored managed print solutions address sector-specific needs with precision and expertise.' ) ); ?></p>
		</div>
		<div class="grid grid-4">
			<?php foreach ( clarity_rows( 'sectors', $def_sectors ) as $s ) : ?>
			<div class="icard">
				<div class="ic"><?php echo clarity_icon( $s['icon'] ); ?></div>
				<h3><?php echo esc_html( $s['title'] ); ?></h3>
				<p><?php echo esc_html( $s['description'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- WHY CHOOSE CLARITY -->
<section class="section bg-beige">
	<div class="container">
		<div class="section-head">
			<h2><?php echo esc_html( clarity_field( 'why_heading', 'Why Choose Clarity?' ) ); ?></h2>
			<p><?php echo esc_html( clarity_field( 'why_intro', 'Three decades of trusted service, genuine Sharp products, and a local team ready to respond.' ) ); ?></p>
		</div>
		<div class="grid grid-4">
			<?php foreach ( clarity_rows( 'why_items', $def_why ) as $w ) : ?>
			<div class="icard">
				<div class="ic"><?php echo clarity_icon( $w['icon'] ); ?></div>
				<h3><?php echo esc_html( $w['title'] ); ?></h3>
				<p><?php echo esc_html( $w['description'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- MEET OUR TEAM -->
<section class="section">
	<div class="container">
		<div class="split">
			<img src="<?php echo esc_url( $team_img ); ?>" alt="The Clarity Copiers team">
			<div class="txt">
				<h2><?php echo esc_html( clarity_field( 'team_heading', 'Meet Our Team' ) ); ?></h2>
				<p><?php echo esc_html( clarity_field( 'team_text', "Led by Warren Dryden, our dedicated team combines decades of experience with a passion for exceptional customer service. We're not just your supplier — we're your technology partner." ) ); ?></p>
				<a class="btn btn--outline" href="<?php echo esc_url( clarity_field( 'team_btn_url', home_url( '/our-team/' ) ) ); ?>"><?php echo esc_html( clarity_field( 'team_btn_label', 'Learn More' ) ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- PRODUCTS & SERVICES -->
<section class="section bg-beige">
	<div class="container">
		<div class="head-row">
			<div class="l">
				<h2><?php echo esc_html( clarity_field( 'products_heading', 'Products & Services' ) ); ?></h2>
				<p><?php echo esc_html( clarity_field( 'products_intro', 'Comprehensive Sharp technology solutions designed to meet every business need. From multifunction printers to interactive displays and cloud services.' ) ); ?></p>
			</div>
			<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">View All</a>
		</div>
		<div class="grid grid-3">
			<?php foreach ( clarity_rows( 'products', $def_products ) as $p ) :
				$img = is_array( $p['image'] ) ? ( $p['image']['url'] ?? '' ) : $p['image'];
			?>
			<a class="pcard" href="<?php echo esc_url( home_url( $p['link'] ) ); ?>">
				<div class="thumb"><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>"></div>
				<div class="body">
					<h3><?php echo esc_html( $p['title'] ); ?></h3>
					<p><?php echo esc_html( $p['description'] ); ?></p>
					<span class="link-more">Explore more →</span>
				</div>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
	<div class="container">
		<div class="section-head">
			<h2><?php echo esc_html( clarity_field( 'testimonials_heading', 'What Our Clients Say' ) ); ?></h2>
			<p><?php echo esc_html( clarity_field( 'testimonials_intro', "Don't just take our word for it. Here's what our satisfied clients have to say about our service." ) ); ?></p>
		</div>
		<div class="grid grid-3">
			<?php foreach ( clarity_rows( 'testimonials', $def_tests ) as $t ) :
				$stars = max( 1, min( 5, (int) ( $t['rating'] ?? 5 ) ) );
			?>
			<div class="tcard">
				<div class="stars"><?php echo str_repeat( '★', $stars ); ?></div>
				<blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
				<div class="who"><?php echo esc_html( $t['name'] ); ?></div>
				<div class="role"><?php echo esc_html( $t['role'] ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- LATEST NEWS -->
<section class="section bg-gray">
	<div class="container">
		<div class="head-row">
			<div class="l">
				<h2><?php echo esc_html( clarity_field( 'news_heading', 'Latest News & Insights' ) ); ?></h2>
				<p><?php echo esc_html( clarity_field( 'news_intro', 'Stay updated with the latest product releases, industry insights, and company news.' ) ); ?></p>
			</div>
			<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">View All News</a>
		</div>
		<div class="grid grid-3">
			<?php
			$news = get_posts( array( 'numberposts' => 3 ) );
			if ( $news ) :
				foreach ( $news as $post ) : setup_postdata( $post );
			?>
			<a class="ncard" href="<?php the_permalink(); ?>">
				<div class="thumb"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( null, 'medium_large' ) : '<img src="' . esc_url( $uri . '/assets/bigpad.png' ) . '" alt="">'; ?></div>
				<div class="body">
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
					<span class="link-more">Read More →</span>
				</div>
			</a>
			<?php endforeach; wp_reset_postdata();
			else :
				$fallback_news = array(
					array( 'Celebrating 30 Years of Excellence', 'Join us as we celebrate three decades of serving businesses across the UK with Sharp technology solutions.' ),
					array( 'New Sharp MFP Range Now Available', 'Discover the latest Sharp multifunction printers with enhanced security features and improved efficiency.' ),
					array( '5 Ways to Reduce Print Costs', 'Our expert tips for managing your print environment and achieving significant cost savings.' ),
				);
				foreach ( $fallback_news as $n ) :
			?>
			<div class="ncard">
				<div class="thumb"><img src="<?php echo esc_url( $uri . '/assets/colour-mfp.png' ); ?>" alt=""></div>
				<div class="body">
					<h3><?php echo esc_html( $n[0] ); ?></h3>
					<p><?php echo esc_html( $n[1] ); ?></p>
					<span class="link-more">Read More →</span>
				</div>
			</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
