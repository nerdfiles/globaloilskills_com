<?php

/**
 * Stripe Market Rewrite
 */
function stripe_market_rewrite() {
	// Register a rewrite rule for our stripe template
	add_rewrite_rule( '^stripe-market-iframe/?$', 'index.php?stripe-market-iframe=true', 'top' );
}
add_action( 'init', 'stripe_market_rewrite' );


/**
 * Stripe Market Rewrite Add Var
 */
function stripe_market_rewrite_add_var( $vars ) {
		$vars[] = 'stripe-market-iframe';
		return $vars;
}
add_filter( 'query_vars', 'stripe_market_rewrite_add_var' );


/**
 * Stripe Market Rewrite Load Iframe
 */
function stripe_market_rewrite_load_iframe() {
	if ( get_query_var( 'stripe-market-iframe' ) ) {
		require_once( STRIPE_MARKET_PATH . 'includes/stripe-iframe.php' );
		exit;
	}
}
add_action( 'template_redirect', 'stripe_market_rewrite_load_iframe' );
