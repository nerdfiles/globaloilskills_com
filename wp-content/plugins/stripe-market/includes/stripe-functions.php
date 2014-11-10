<?php

/**
 * Display the Stripe Form in a Thickbox Pop-up
 *
 * @param $atts array Undefined, have not found any use yet
 * @return string Form Pop-up Link (wrapped in <a></a>)
 *
 * @since 1.3
 *
 */
function stripe_market_shortcode( $atts ) {

	$options = get_option( 'stripe_market_options' );

  $a = shortcode_atts( array(
    'product' => 'stripe-market',
    'payouts' => 'stripe-market',
    'amount' => 'stripe-market',
    'pay-parent' => 'stripe-market',
    'cards' => 'true',
  ), $atts );

	extract( $a );

  $header = esc_attr($a['product']) ? esc_attr($a['product']) : esc_attr( $options['stripe_header'] );
  $payouts = base64_encode(esc_attr($a['payouts']));
  $amount = base64_encode(esc_attr($a['amount']));

  $url     = add_query_arg( array(
    'stripe-market-iframe' => 'true',
    'keepThis'             => 'true',
    'pr'                   => $header,
    'pa'                   => $payouts,
    'am'                   => $amount,
    'height'               => 580,
    'width'                => 400,
    'TB_iframe'            => 'true',), // Must be last.
    home_url()
  );
	$count   = 1;

	if ( isset( $options['stripe_modal_ssl'] ) && $options['stripe_modal_ssl'] === 'Yes' ) {
		$url = str_replace( 'http://', 'https://', $url, $count );
	}

	if ( $cards === 'true' )  {
		$payments = '<div id="stripe-market-types"></div>';
	}

  return '<a class="thickbox" id="stripe-market-modal-button" title="' . $header . '" href="' . esc_url( $url ) . '"><span>' . $header . '</span></a>' . $payments;

}
add_shortcode( 'stripe-market', 'stripe_market_shortcode' );

/**
 * Stripe Market OAuth Redirect
 * See https://github.com/onassar/PHP-StripeOAuth
 */
function stripe_market_oauth_redirect() {

  $oauth = (new StripeOAuth(
    STRIPE_MARKET_CLIENT,
    STRIPE_MARKET_KEY
  ));

  // Redirect w/ code
  $url = $oauth->getAuthorizeUri();
  echo "<a href='$url'>Connect with Stripe</a>";
}
add_shortcode( 'stripe-connect', 'stripe_market_oauth_redirect' );

/**
 * Stripe Market Authorized Partner Check
 *
 * See https://github.com/onassar/PHP-StripeOAuth
 * @example http://localhost/?scope=read_write&code=ac_4w77pCALh2lPFfv2ducNjKCfXlk7woK2
 */
function stripe_market_authorized_partner_check() {

  $oauth = (new StripeOAuth(
    STRIPE_MARKET_CLIENT,
    STRIPE_MARKET_API
  ));

  // OAuth callback code
  $code = $_GET['code'];
  if ( ! $code ) {
    $code = $_REQUEST['code'];
  }

  // Get credentials
  $token = $oauth->getAccessToken($code);
  $key = $oauth->getPublishableKey($code);

  // Set labels for meta data
  $key__pub_key = 'STRIPE_PUB_KEY';
  $key__access_token = 'STRIPE_ACCESS_TOKEN';

  // Some WP fluff
  $single = $unique = true;
  $errorMessage = 'An error has occurred.';

  // Get user
  $user_id = get_current_user_id();

  // Get user details — PUBLISHABLE KEY
  $user_pub_key = get_user_meta(
    $user_id,
    $key__pub_key,
    $single
  );

  // Get user details — ACCESS TOKEN
  $user_access_token = get_user_meta(
    $user_id,
    $key__access_token,
    $single
  );

  // User has yet to store an access token or publishable key.
  if ( !$user_pub_key && !$user_access_token && isset($code)) {

    // Neither property exits, add.
    if ( ! $user_access_token && ! $user_pub_key ) {
      add_user_meta( $user_id, $key__pub_key, $key, $unique );
      add_user_meta( $user_id, $key__access_token, $token, $unique );
    } else {
      // Both properties exist, update and check if new.
      update_user_meta( $user_id, $key__pub_key, $user_pub_key );
      update_user_meta( $user_id, $key__access_token, $user_access_token );

      if ( $user_pub_key != $key ) {
        wp_die( $errorMessage );
      }

      if ( $user_access_token != $token ) {
        wp_die( $errorMessage );
      }
    }

    header('Location: ' . get_home_url());
  }
}
add_action( 'admin_init', 'stripe_market_authorized_partner_check' );
add_action( 'init', 'stripe_market_authorized_partner_check' );


/**
 * Display Legacy Stripe form in-line
 *
 * @param $atts array Undefined, have not found any use yet
 * @return string Form / DOM Content
 *
 * @since 1.3
 *
 */
function stripe_market_shortcode_legacy( $atts ){
	return stripe_market_form();
}
add_shortcode( 'wp-legacy-stripe', 'stripe_market_shortcode_legacy' );

/**
 * Create Customer for re-use
 *
 * @param $amount int transaction amount in cents (i.e. $1 = '100')
 * @param $card string
 * @param $description string
 * @return array
 *
 * @since 1.0
 */
function stripe_market_create_customer($card, $description) {

	$options = get_option( 'stripe_market_options' );

	/*
	 * Card - Token from stripe.js is provided (not individual card elements)
   * Create Recipient using
   */
	$customer = array(
		"card" => $card
	);

	if ( $description ) {
		$customer['description'] = $description;
	}

  try {
    $response = Stripe_Customer::create( $customer );
  } catch ( Exception $e ) {
    $response = $e->getMessage();
    error_log($response);
    do_action( 'stripe_market_post_fail_charge', $card, $e->getMessage() );
  }

	return $response;

}

/**
 * Create Token using Stripe PHP Library
 *
 * @param $access_token string A token binding customer to the application.
 * @param $card string Card for the customer.
 * @param $customer_id Customer ID.
 */
function stripe_market_create_token($access_token, $card, $customer_id) {

	$options = get_option( 'stripe_market_options' );

  try {
    $response = Stripe_Token::create(
      array("customer" => $customer_id, "card" => $card),
      $access_token
    );

  } catch ( Exception $e ) {
    $response = $e->getMessage();
    error_log($response);
    do_action(
      'stripe_market_post_fail_charge',
      $customer_id,
      $response
    );
  }

  return $response;
}

/**
 * Create Charge using Stripe PHP Library
 *
 * @param $amount int transaction amount in cents (i.e. $1 = '100')
 * @param $card string
 * @param $description string
 * @return array
 *
 * @since 1.0
 *
 */
function stripe_market_charge($amount, $card, $name, $description, $currency, $access_token) {

	$options = get_option( 'stripe_market_options' );

	/*
	 * Card - Token from stripe.js is provided (not individual card elements)
   * Create Recipient using
   */
	$charge = array(
		'card'     => $card,
		'amount'   => $amount,
		'currency' => $currency,
	);

	if ( $description ) {
		$charge['description'] = $description;
	}

	$response = Stripe_Charge::create( $charge, $access_token );

	return $response;

}

/**
 * 3-step function to Process & Save Transaction
 *
 * 1) Capture POST
 * 2) Create Charge using stripe_market_charge()
 * 3) Store Transaction in Custom Post Type
 *
 * @since 1.0
 *
 */
function stripe_market_charge_initiate() {

		// Security Check
		if ( ! wp_verify_nonce( $_POST['nonce'], 'stripe-market-nonce' ) ) {
			wp_die( __( 'Nonce verification failed!', 'stripe-market' ) );
		}

    $key__access_token = 'STRIPE_ACCESS_TOKEN';

		// Define/Extract Variables
		$public = sanitize_text_field( $_POST['stripe_market_public'] );
		$name   = sanitize_text_field( $_POST['stripe_market_name'] );
		$email  = sanitize_email( $_POST['stripe_market_email'] );

    $currency = sanitize_text_field( $_POST['stripe_market_currency'] );

    $payParent = sanitize_text_field( $_POST['stripe_market_pay_parent'] );

    // As Product
    $payouts = sanitize_text_field( $_POST['stripe_market_payouts'] );

		// Strip any comments from the amount
		$amount = str_replace( ',', '', sanitize_text_field( $_POST['stripe_market_amount'] ) );
		$amount = str_replace( '$', '', $amount ) * 100;

    // If this card is passed to Stripe_Token::create, the error will read
    // that the card field is "empty", rather than a more specific error.
		$card = sanitize_text_field( $_POST['stripeToken'] );

		$widget_comment = '';

		if ( empty( $_POST['stripe_market_comment'] ) ) {
			$stripe_comment = __( 'E-mail: ', 'wp-stipe') . sanitize_text_field( $_POST['stripe_market_email'] ) . ' - ' . __( 'This transaction has no additional details', 'stripe-market' );
		} else {
			$stripe_comment = __( 'E-mail: ', 'wp-stipe' ) . sanitize_text_field( $_POST['stripe_market_email'] ) . ' - ' . sanitize_text_field( $_POST['stripe_market_comment'] );
			$widget_comment = sanitize_text_field( $_POST['stripe_market_comment'] );
		}

    // Create Customer for re-use
    $customer_response = stripe_market_create_customer(
      $card,
      $stripe_comment
    );

    $customer_id = $customer_response->id;
    $customer_cards = $customer_response->cards;
    $customer_cards_data = $customer_cards->data;
    $firstCard = $customer_cards_data[0];
    $firstCard_id = $firstCard->id;

		// Create Charge
		try {

      // Spread Stripe payment(s).
      if ( $payouts ) {

        // Create customer object to re-use in charges
        // Iterate through payouts and charge until difference of amount is cleared
        $payees = explode(',', $payouts);

        foreach($payees as $payee) {

          $payee_attrs = explode(':', $payee);
          $payee_username = $payee_attrs[0];
          $payee_amount = $payee_attrs[1];

          if ($payee_username == 'self') {

          }

          $user = get_user_by( 'login', $payee_username );

          $payee_access_token = esc_attr(get_user_meta($user->id, $key__access_token, true));

          $token = stripe_market_create_token(
            $payee_access_token,
            $firstCard_id,
            $customer_id);

          // Pull out the id property
          $token_id = esc_attr($token->id);

          $payee_response = stripe_market_charge(
            $payee_amount * 100,
            $token_id,
            $name,
            $stripe_comment,
            $currency,
            $payee_access_token);

          $payee_response_id       = $payee_response->id;
          $payee_response_amount   = $payee_response->amount / 100;
          $payee_response_currency = $payee_response->currency;
          $payee_response_created  = $payee_response->created;
          $payee_response_live     = $payee_response->livemode;
          $payee_response_paid     = $payee_response->paid;

          if ( isset( $payee_response->fee ) ) {
            $payee_fee  = $payee_response->fee;
          }

          // Save Charge
          if ( $payee_response_paid === true ) {
            $post_id = wp_insert_post(array(
                'post_type'	   => 'stripe-market-trx',
                'post_author'  => 1,
                'post_content' => $widget_comment,
                'post_title'   => $payee_response_id,
                'post_status'  => 'publish',
            ));

            // Define Livemode
            if ( $payee_response_live ) {
              $payee_response_live = 'LIVE';
            } else {
              $payee_response_live = 'TEST';
            }

            // Define Public (for Widget)
            if ( $public === 'public' ) {
              $public = 'YES';
            } else {
              $public = 'NO';
            }

            // Update Meta
            update_post_meta( $post_id, 'stripe-market-public', $public );
            update_post_meta( $post_id, 'stripe-market-name', $name );
            update_post_meta( $post_id, 'stripe-market-email', $email );

            update_post_meta( $post_id, 'stripe-market-live', $payee_response_live );
            update_post_meta( $post_id, 'stripe-market-date', $payee_response_created );
            update_post_meta( $post_id, 'stripe-market-amount', $payee_response_amount );
            update_post_meta( $post_id, 'stripe-market-currency', strtoupper( $payee_response_currency ) );

            if ( isset( $fee ) )
              update_post_meta( $post_id, 'stripe-market-fee', $payee_response_fee );

            do_action( 'stripe_market_post_successful_charge', $payee_response, $email, $stripe_comment );
          }
        }

      } else {
        // Normal Stripe payment.

        $response = stripe_market_charge(
          $amount,
          $card,
          $name,
          $stripe_comment,
          $currency
        );

        $id       = $response->id;
        $amount   = $response->amount / 100;
        $currency = $response->currency;
        $created  = $response->created;
        $live     = $response->livemode;
        $paid     = $response->paid;

        if ( isset( $response->fee ) ) {
          $fee = $response->fee;
        }

        $result =  '<div class="stripe-market-notification stripe-market-success"> ' . sprintf( __( 'Success, you just transferred %s', 'stripe-market' ), '<span class="stripe-market-currency">' . esc_html( $currency ) . '</span> ' . esc_html( $amount ) ) . ' !</div>';

        // Save Charge
        if ( $paid === true ) {
          $post_id = wp_insert_post(array(
            'post_type'	   => 'stripe-market-trx',
            'post_author'  => 1,
            'post_content' => $widget_comment,
            'post_title'   => $id,
            'post_status'  => 'publish',
          ));

          // Define Livemode
          if ( $live ) {
            $live = 'LIVE';
          } else {
            $live = 'TEST';
          }

          // Define Public (for Widget)
          if ( $public === 'public' ) {
            $public = 'YES';
          } else {
            $public = 'NO';
          }

          // Update Meta
          update_post_meta( $post_id, 'stripe-market-public', $public );
          update_post_meta( $post_id, 'stripe-market-name', $name );
          update_post_meta( $post_id, 'stripe-market-email', $email );

          update_post_meta( $post_id, 'stripe-market-live', $live );
          update_post_meta( $post_id, 'stripe-market-date', $created );
          update_post_meta( $post_id, 'stripe-market-amount', $amount );
          update_post_meta( $post_id, 'stripe-market-currency', strtoupper( $currency ) );

          if ( isset( $fee ) )
            update_post_meta( $post_id, 'stripe-market-fee', $fee );

          do_action( 'stripe_market_post_successful_charge', $response, $email, $stripe_comment );

          // Update Project
          // stripe_market_update_project_transactions( 'add', $project_id , $post_id );
        }

			}

		// Error
		} catch ( Exception $e ) {
      error_log($e->getMessage());
			$result = '<div class="stripe-market-notification stripe-market-failure">' . sprintf( __( 'Oops, something went wrong (%s)', 'stripe-market' ), $e->getMessage() ) . '</div>';
			do_action( 'stripe_market_post_fail_charge', $email, $e->getMessage() );
		}

		// Return Results to JS
		header( 'Content-Type: application/json' );
		echo json_encode( $result );
		exit;

}
add_action('wp_ajax_stripe_market_charge_initiate', 'stripe_market_charge_initiate');
add_action('wp_ajax_nopriv_stripe_market_charge_initiate', 'stripe_market_charge_initiate');
