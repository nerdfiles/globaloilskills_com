<?php
/**
 * stripe-market
 *
 * stripe-market
 *
 * @package   stripe-market
 * @author    nerdfiles <hello@nerdfiles.net>
 * @license   GPL-2.0+
 * @link      http://nerdfiles.net
 *
 * @wordpress-plugin
 * Plugin Name: stripe-market
 * Plugin URI:  http://nerdfiles.net
 * Description: stripe-market
 * Version:     1.0.0
 * Author:      nerdfiles
 * Author URI:  http://nerdfiles.net
 * Text Domain: stripe-market-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */


function debug_check() {
  $debug = $_GET['debug'];
  if (isset($debug) && $debug == true) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',0);
    error_reporting(-1);
  }
}
add_action('init', 'debug_check');
add_action('admin_init', 'debug_check');


// If this file is called directly, abort.
if (!defined("WPINC")) {
	die;
}


// Defines
// -----------------------------------------------------

define( 'STRIPE_MARKET_PATH',  plugin_dir_path( __FILE__ ) );
define( 'STRIPE_MARKET_URL', plugin_dir_url(  __FILE__  ) );
define( 'STRIPE_MARKET_VERSION', '1.0.0' );

define( 'STRIPE_MARKET_TOKEN_URI', 'https://connect.stripe.com/oauth/token' );
define( 'STRIPE_MARKET_AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize' );


// Load PHP Lib - https://github.com/stripe/stripe-php
// -----------------------------------------------------

if ( ! class_exists( 'Stripe' ) ) {
	require_once( STRIPE_MARKET_PATH . 'vendor/stripe-php/lib/Stripe.php' );
}

if ( ! class_exists('OAuth2Client') ) {
  require_once( STRIPE_MARKET_PATH . 'vendor/oauth2-php/lib/OAuth2Client.php' );
}

if ( ! class_exists( 'StripeOAuth') && ! class_exists ('StripeOAuth2Client') ) {
  require_once( STRIPE_MARKET_PATH . 'vendor/PHP-StripeOAuth/StripeOAuth.class.php' );
  require_once( STRIPE_MARKET_PATH . 'vendor/PHP-StripeOAuth/StripeOAuth2Client.class.php' );
}


// Load WordPress Files
// -----------------------------------------------------

require_once( STRIPE_MARKET_PATH . 'includes/stripe-cpt.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-options-products.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-options-transactions.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-options.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-functions.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-display.php' );
require_once( STRIPE_MARKET_PATH . 'includes/stripe-rewrite.php' );


// Load Plugin Core
// -----------------------------------------------------

require_once(plugin_dir_path(__FILE__) . "stripeMarket.php");


// Select correct API Key
// -----------------------------------------------------

$options = get_option( 'stripe_market_options' );

if ( ! empty( $options['stripe_api_switch'] ) ) {

	if ( $options['stripe_api_switch'] === 'Yes') {

		Stripe::setApiKey( $options['stripe_test_api'] );
    define( 'STRIPE_MARKET_API', $options['stripe_test_api'] );
		define( 'STRIPE_MARKET_KEY', $options['stripe_test_api_publish'] );
    define( 'STRIPE_MARKET_CLIENT', $options['stripe_test_client'] );

	} else {

		Stripe::setApiKey( $options['stripe_prod_api'] );
    define( 'STRIPE_MARKET_API', $options['stripe_prod_api'] );
		define( 'STRIPE_MARKET_KEY', $options['stripe_prod_api_publish'] );
    define( 'STRIPE_MARKET_CLIENT', $options['stripe_prod_client'] );

	}

}


// Enable Recent Donations/Payments Widget?
// -----------------------------------------------------

if ( $options['stripe_recent_switch'] === 'Yes' ) {
	require_once( STRIPE_MARKET_PATH . 'includes/stripe-widget-recent.php' );
}


// Register Settings ( & Defaults )
// -----------------------------------------------------

if ( get_option( 'stripe_market_options' ) === '' ) {
	register_activation_hook( __FILE__, 'stripe_market_defaults' );
}

function stripe_market_defaults() {

	flush_rewrite_rules();

	update_option( 'stripe_market_options', array(
		'stripe_header'           => 'Donate',
		'stripe_css_switch'       => 'Yes',
		'stripe_api_switch'       => 'Yes',
		'stripe_recent_switch'    => 'Yes',
		'stripe_modal_ssl'        => 'No',
		'stripe_currency'         => 'USD',
		'stripe_labels_on'        => 'No',
		'stripe_placeholders_on'  => 'Yes',
		'stripe_email_required'   => 'No'
	) );

}


// Actions (Overview)
// -----------------------------------------------------
add_action( 'admin_init', 'stripe_market_options_init' );
add_action( 'admin_menu', 'stripe_market_add_page' );


// JS & CSS
// -----------------------------------------------------

/**
 * Load Stripe Market JS
 */
function load_stripe_market_js() {
	//wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v2/', array( 'jquery' ), STRIPE_MARKET_VERSION );
	wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v1/', array( 'jquery' ), STRIPE_MARKET_VERSION );
	wp_enqueue_script( 'stripe-market-js', STRIPE_MARKET_URL . 'js/stripe-market.js', array( 'jquery' ), STRIPE_MARKET_VERSION );
	// Pass some variables to JS
	wp_localize_script( 'stripe-market-js', 'wpstripekey', STRIPE_MARKET_KEY );
	wp_localize_script( 'stripe-market-js', 'wpstripeclient', STRIPE_MARKET_CLIENT );
	wp_localize_script( 'stripe-market-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}
add_action( 'wp_print_scripts', 'load_stripe_market_js' );

/**
 * Load Stripe Market Admin JS
 */
function load_stripe_market_admin_js() {
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
}
add_action( 'admin_print_scripts', 'load_stripe_market_admin_js' );

/**
 * Load Stripe Market CSS
 */
function load_stripe_market_css() {
	$options = get_option( 'stripe_market_options' );
	if ( isset( $options['stripe_css_switch'] ) && $options['stripe_css_switch'] === 'Yes' ) {
		wp_enqueue_style('stripe-payment-css', STRIPE_MARKET_URL . 'css/stripe-market-display.css', array(), STRIPE_MARKET_VERSION );
	}
	wp_enqueue_style( 'stripe-widget-css', STRIPE_MARKET_URL . 'css/stripe-market-widget.css', array(), STRIPE_MARKET_VERSION );
}
add_action( 'wp_print_styles', 'load_stripe_market_css' );

/**
 * Load Stripe Market Admin CSS
 */
function load_stripe_market_admin_css() {
	wp_enqueue_style( 'stripe-css', STRIPE_MARKET_URL . 'css/stripe-market-admin.css', array(), STRIPE_MARKET_VERSION );
}
add_action( 'admin_print_styles', 'load_stripe_market_admin_css' );

/**
 * Add Thickbox to all Pages
 */
function stripe_market_thickbox() {
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'stripe-thickbox', STRIPE_MARKET_URL . 'css/stripe-market-thickbox.css', array(), STRIPE_MARKET_VERSION );
}
add_action( 'wp_print_styles','stripe_market_thickbox' );

/**
 * Replace the Thickbox images with our own
 */
function stripe_market_thickbox_imgs() {
?>

	<script type="text/javascript">
		var tb_pathToImage = "<?php echo esc_js( STRIPE_MARKET_URL ); ?>images/loadingAnimation.gif";
		var tb_closeImage  = "<?php echo esc_js( STRIPE_MARKET_URL ); ?>images/thickbox_close.png";
	</script>

<?php
}
add_action( 'wp_footer', 'stripe_market_thickbox_imgs' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array("stripeMarket", "activate"));
register_deactivation_hook(__FILE__, array("stripeMarket", "deactivate"));

stripeMarket::get_instance();
