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
	if ( false !== strpos( $who, 'joining' ) && $age < 16 ) {
		return 'As you’re under 16, a parent or guardian needs to finish this sign-up.';
	}
	$rules = array();
	if ( false !== stripos( $plan, 'junior' ) ) {
		$rules[] = array( 9, 15, 'Junior plans are for ages 9–15' );
	} elseif ( false !== strpos( $plan, 'Children' ) ) {
		$rules[] = array( 1, 9, 'The Children’s Wellbeing Gym is for ages 1–9' );
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
 * Age group (field 35) for the consent rules a parent sees: the children’s
 * gym, 9–12, 13–15, other under-9s, or 16+. Mirrors ageGroup() in join.js.
 */
function bgwc_age_group( $age, $plan ) {
	if ( null === $age ) {
		return '';
	}
	if ( false !== strpos( $plan, 'Children' ) ) {
		return 'Children’s gym';
	}
	if ( $age >= 16 ) {
		return '16+';
	}
	if ( $age >= 13 ) {
		return '13–15';
	}
	return $age >= 9 ? '9–12' : 'Under 9';
}

/**
 * "Member is under 16" (field 31) and "Age group" (field 35) always come from
 * the date of birth and plan on the server, before validation and saving, so
 * the parent consent and signature can’t be skipped by editing the page.
 */
function bgwc_set_minor_flag( $form ) {
	$age  = bgwc_age_from( rgpost( 'input_10' ) );
	$join = (string) wp_unslash( rgpost( 'input_2' ) );
	$plan = '';
	if ( false === strpos( $join, 'GP referral' ) ) {
		$plan = explode( '|', (string) wp_unslash( rgpost( false !== strpos( $join, 'Pay as you go' ) ? 'input_4' : 'input_3' ) ) )[0];
	}
	$_POST['input_31'] = null === $age ? '' : ( $age < 16 ? 'yes' : 'no' );
	$_POST['input_35'] = bgwc_age_group( $age, $plan );
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

/**
 * Wording that depends on how they joined and whether payment went through.
 * Returns array( 'title' => ..., 'line' => ..., 'email' => ... ) or null.
 */
function bgwc_join_copy( $entry ) {
	$join = (string) rgar( $entry, '2' );
	$name = false !== strpos( (string) rgar( $entry, '8' ), 'parent' ) && rgar( $entry, '15' ) ? rgar( $entry, '15' ) : rgar( $entry, '9.3' );
	$first = esc_html( strtok( trim( (string) $name ), ' ' ) );
	$paid  = in_array( rgar( $entry, 'payment_status' ), array( 'Paid', 'Active' ), true );
	$amount = str_replace( array( ' ', "\xc2\xa0" ), '', GFCommon::to_money( rgar( $entry, 'payment_amount' ) ) );

	if ( false !== strpos( $join, 'GP referral' ) ) {
		return array(
			'title' => "Thanks, {$first}.",
			'line'  => 'We’ve got your details and we’ll be in touch about your free wellbeing course.',
			'email' => 'We’ve got your details and we’ll be in touch about your free wellbeing course.',
		);
	}
	if ( ! $paid ) {
		return null;
	}
	if ( false !== strpos( $join, 'Monthly' ) ) {
		return array(
			'title' => "Thanks, {$first}.",
			'line'  => 'You’re in. Your first month is paid and we’ve emailed you the details.',
			'email' => "Your first payment of {$amount} has gone through. Your membership renews each month.",
		);
	}
	return array(
		'title' => "Thanks, {$first}.",
		'line'  => 'Your pass is paid and we’ve emailed you the details.',
		'email' => "Your payment of {$amount} has gone through.",
	);
}

add_filter( 'gform_confirmation_1', function ( $confirmation, $form, $entry ) {
	$copy = bgwc_join_copy( $entry );
	if ( ! $copy || ! is_string( $confirmation ) ) {
		return $confirmation;
	}
	$inner = '<h2>' . $copy['title'] . '</h2><p>' . $copy['line'] . '</p>';
	return preg_replace( "#(class='gform_confirmation_message_1 gform_confirmation_message'[^>]*>).*?(</div>)#s", '${1}' . $inner . '${2}', $confirmation, 1 );
}, 10, 3 );

add_filter( 'gform_notification_1', function ( $notification, $form, $entry ) {
	if ( 'Welcome email (member)' !== rgar( $notification, 'name' ) ) {
		return $notification;
	}
	$copy = bgwc_join_copy( $entry );
	if ( $copy ) {
		$notification['message'] = str_replace( 'We’ve got your details and we’ll be in touch soon.', $copy['email'], $notification['message'] );
	}
	return $notification;
}, 20, 3 );

/**
 * Stripe amounts are sent in pence. 19.99 * 100 is 1998.9999… in floating
 * point, and the Stripe add-on truncates it, so a £19.99 subscription was
 * created at £19.98. A millionth of a pound on top makes it convert to
 * exactly 1999p whether the add-on truncates or rounds.
 */
add_filter( 'gform_submission_data_pre_process_payment', function ( $submission_data, $feed, $form ) {
	if ( 1 !== (int) rgar( $form, 'id' ) ) {
		return $submission_data;
	}
	foreach ( array( 'payment_amount', 'setup_fee', 'trial' ) as $key ) {
		if ( isset( $submission_data[ $key ] ) && is_numeric( $submission_data[ $key ] ) && $submission_data[ $key ] > 0 ) {
			$submission_data[ $key ] = round( (float) $submission_data[ $key ], 2 ) + 0.000001;
		}
	}
	return $submission_data;
}, 10, 3 );
