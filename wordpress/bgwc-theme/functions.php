<?php
/**
 * BGWC Coming Soon theme.
 */

defined( 'ABSPATH' ) || exit;

require_once get_theme_file_path( 'inc/acf-fields.php' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'page', 'excerpt' );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bgwc', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Keep the page identical to the design: no block editor styles on the holding page.
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );

	if ( is_page() && ! is_front_page() ) {
		wp_enqueue_script( 'bgwc-join', get_theme_file_uri( 'assets/join.js' ), array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
	}
}, 20 );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter( 'pre_get_document_title', function () {
	return bgwc_field( 'meta_title' );
} );

add_action( 'wp_head', function () {
	printf( "<meta name=\"theme-color\" content=\"#121713\">\n" );
	printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( bgwc_field( 'meta_description' ) ) );
	if ( ! has_site_icon() ) {
		$icon = esc_url( get_theme_file_uri( 'assets/images/favicon.svg' ) );
		printf( "<link rel=\"icon\" type=\"image/svg+xml\" href=\"%s\">\n", $icon );
	}
}, 1 );

/**
 * On activation, create a "Home" page and make it the static front page so
 * the ACF fields appear straight away.
 */
add_action( 'after_switch_theme', function () {
	$front_id = (int) get_option( 'page_on_front' );

	if ( ! $front_id || 'page' !== get_post_type( $front_id ) ) {
		$existing = get_page_by_path( 'home' );
		$front_id = $existing ? $existing->ID : wp_insert_post(
			array(
				'post_title'  => 'Home',
				'post_name'   => 'home',
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);
	}

	if ( $front_id && ! is_wp_error( $front_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
	}
} );

/**
 * Nudge admins to install a custom fields plugin if none is active.
 */
add_action( 'admin_notices', function () {
	if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>BGWC theme:</strong> activate Advanced Custom Fields (or Secure Custom Fields) to edit the homepage content. The page still shows the default design without it.</p></div>';
} );

/**
 * Show the price on each membership card, e.g. "£25.99 / month".
 * Monthly plans are the product fields with the "bgwc-monthly" class.
 */
add_filter( 'gform_field_choice_markup_pre_render', function ( $markup, $choice, $field ) {
	if ( 'product' !== $field->type || false === strpos( (string) $field->cssClass, 'bgwc-cards' ) || '' === rgar( $choice, 'price' ) ) {
		return $markup;
	}
	$price = GFCommon::to_money( GFCommon::to_number( $choice['price'] ) );
	$per   = false !== strpos( $field->cssClass, 'bgwc-monthly' ) ? '<small> / month</small>' : '';

	return str_replace( '</label>', '<span class="bgwc-price">' . esc_html( str_replace( array( ' ', "\xc2\xa0" ), '', $price ) ) . $per . '</span></label>', $markup );
}, 10, 3 );

/**
 * UK-style prices: "£19.99", not "£ 19.99".
 */
add_filter( 'gform_currencies', function ( $currencies ) {
	if ( isset( $currencies['GBP'] ) ) {
		$currencies['GBP']['symbol_padding'] = '';
	}
	return $currencies;
} );

/**
 * The host firewall blocks Gravity Forms previews on front-end URLs
 * (/?gf_page=preview) with a 403, but allows the same preview under
 * /wp-admin/. Send the editor's Preview button there instead.
 */
add_action( 'after_setup_theme', function () {
	if ( is_admin() || 'preview' !== ( $_GET['gf_page'] ?? '' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	wp_safe_redirect( add_query_arg( array( 'gf_page' => 'preview', 'id' => absint( $_GET['id'] ?? 0 ) ), admin_url( 'index.php' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	exit;
} );

/**
 * Date of birth is typed (DD/MM/YYYY), so no calendar pop-up:
 * dropping the datepicker class stops Gravity Forms attaching one.
 */
add_filter( 'gform_field_content_1_10', function ( $content ) {
	return preg_replace_callback(
		"/class='([^']*)'/",
		function ( $m ) {
			$classes = array_diff( preg_split( '/\s+/', trim( $m[1] ) ), array( 'datepicker' ) );
			return "class='" . implode( ' ', $classes ) . "'";
		},
		$content
	);
} );

/**
 * Age rules for the Join form (mirrors checkAge() in assets/join.js).
 *
 * @param int    $age  Member's age in years.
 * @param string $join "How would you like to join?" value.
 * @param string $plan Chosen plan name (no price), '' for GP referral.
 * @param string $who  "Who's filling this in?" value.
 * @return string Error message, or '' when the age fits.
 */
function bgwc_age_problem( $age, $join, $plan, $who ) {
	if ( false !== strpos( $who, 'joining' ) && $age < 13 ) {
		return 'As you’re under 13, a parent or guardian needs to fill this in.';
	}
	$rules = array();
	if ( false !== stripos( $plan, 'junior' ) ) {
		$rules[] = array( 11, 15, 'Junior plans are for ages 11–15' );
	} elseif ( false !== strpos( $plan, 'Children' ) ) {
		$rules[] = array( 0, 15, 'The children’s wellbeing gym is for under-16s' );
	} elseif ( false !== strpos( $plan, '16+' ) ) {
		$rules[] = array( 16, 200, 'Adult plans are for ages 16 and over' );
	} elseif ( false !== strpos( $plan, 'concession' ) ) {
		$rules[] = array( 16, 200, 'Concession memberships are for ages 16 and over' );
	} elseif ( false !== strpos( $plan, 'Dual' ) ) {
		$rules[] = array( 16, 200, 'The dual BJJ + gym membership is for ages 16 and over' );
	}
	if ( false !== strpos( $join, 'GP referral' ) ) {
		$rules[] = array( 16, 25, 'GP referrals are for ages 16–25' );
	}
	foreach ( $rules as $rule ) {
		if ( $age < $rule[0] || $age > $rule[1] ) {
			return sprintf( '%s, but this date of birth makes them %d. Please change the plan or check the date.', $rule[2], $age );
		}
	}
	return '';
}

/**
 * Age in years from a DD/MM/YYYY date, in the site's timezone; null if not a date.
 */
function bgwc_age_from( $value ) {
	if ( ! is_string( $value ) || ! preg_match( '#^\d{2}/\d{2}/\d{4}$#', trim( $value ) ) ) {
		return null;
	}
	$tz  = wp_timezone();
	$dob = DateTime::createFromFormat( '!d/m/Y', trim( $value ), $tz );
	return $dob ? $dob->diff( new DateTime( 'today', $tz ) )->y : null;
}

/**
 * "Member is under 18" (field 31) always comes from the date of birth on the
 * server, before validation and saving, so the parent or guardian fields
 * cannot be skipped by editing the page.
 */
function bgwc_set_minor_flag( $form ) {
	$age                = bgwc_age_from( rgpost( 'input_10' ) );
	$_POST['input_31'] = ( null !== $age && $age < 18 ) ? 'yes' : ( null === $age ? '' : 'no' );
	return $form;
}
add_filter( 'gform_pre_validation_1', 'bgwc_set_minor_flag' );
add_filter( 'gform_pre_submission_filter_1', 'bgwc_set_minor_flag' );

/**
 * Welcome email: copy the parent or guardian, and greet them when they filled it in.
 */
add_filter( 'gform_notification_1', function ( $notification, $form, $entry ) {
	if ( 'Welcome email (member)' !== rgar( $notification, 'name' ) ) {
		return $notification;
	}
	$guardian_email = sanitize_email( rgar( $entry, '32' ) );
	if ( $guardian_email ) {
		$notification['cc'] = $guardian_email;
	}
	if ( false !== strpos( (string) rgar( $entry, '8' ), 'parent' ) && rgar( $entry, '15' ) ) {
		$first                   = strtok( trim( rgar( $entry, '15' ) ), ' ' );
		$notification['message'] = preg_replace( '#<p>Hi [^<]*,</p>#', '<p>Hi ' . esc_html( $first ) . ',</p>', $notification['message'], 1 );
	}
	return $notification;
}, 10, 3 );

add_filter( 'gform_field_validation_1_10', function ( $result, $value, $form ) {
	if ( ! $result['is_valid'] ) {
		return $result;
	}
	$age = bgwc_age_from( $value );
	if ( null === $age ) {
		return $result;
	}

	$join = (string) rgpost( 'input_2' );
	$plan = '';
	if ( false === strpos( $join, 'GP referral' ) ) {
		$raw  = (string) rgpost( false !== strpos( $join, 'Pay as you go' ) ? 'input_4' : 'input_3' );
		$plan = explode( '|', $raw )[0];
	}
	$who = (string) rgpost( 'input_8' );

	$problem = bgwc_age_problem( $age, wp_unslash( $join ), wp_unslash( $plan ), wp_unslash( $who ) );
	if ( $problem ) {
		$result['is_valid'] = false;
		$result['message']  = $problem;
	}
	return $result;
}, 10, 3 );
