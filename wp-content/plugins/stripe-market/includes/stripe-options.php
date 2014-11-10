<?php

/**
 * Create Options Fields
 *
 * @since 1.0
 *
 */
function stripe_market_options_init() {

	register_setting( 'stripe_market_options', 'stripe_market_options' );

	add_settings_section( 'stripe_market_section_main', '', 'stripe_market_options_header', 'stripe_market_section' );

	add_settings_field( 'stripe_header', 'Payment Form Header', 'stripe_market_field_header', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_recent_switch', 'Enable Recent Widget?', 'stripe_market_field_recent', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_css_switch', 'Enable Payment Form CSS?', 'stripe_market_field_css', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_labels_on', 'Enable Payment Form Labels?', 'stripe_market_field_labels', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_placeholders_on', 'Enable Payment Form Placeholders?', 'stripe_market_field_placeholders', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_email_required', 'Is Email A Required Field?', 'stripe_market_field_email_required', 'stripe_market_section', 'stripe_market_section_main' );
	add_settings_field( 'stripe_currency', 'Currency', 'stripe_market_field_currency', 'stripe_market_section', 'stripe_market_section_main' );

	add_settings_section( 'stripe_market_section_api', '', 'stripe_market_options_header_api', 'stripe_market_section' );

	add_settings_field( 'stripe_api_switch', 'Enable Test API Environment?', 'stripe_market_field_switch', 'stripe_market_section', 'stripe_market_section_api' );

  add_settings_field(
    'stripe_test_api',
    'API Secret Key (Test Environment)',
    'stripe_market_field_test',
    'stripe_market_section',
    'stripe_market_section_api'
  );
  add_settings_field(
    'stripe_test_api_publish',
    'API Publishable Key (Test Environment)',
    'stripe_market_field_test_publish',
    'stripe_market_section',
    'stripe_market_section_api'
  );
  add_settings_field(
    'stripe_test_client',
    'Client ID (Test Environment)',
    'stripe_market_field_test_client',
    'stripe_market_section',
    'stripe_market_section_api'
  );

  add_settings_field(
    'stripe_prod_api',
    'API Secret Key (Production Environment)',
    'stripe_market_field_prod',
    'stripe_market_section',
    'stripe_market_section_api'
  );
  add_settings_field(
    'stripe_prod_api_publish',
    'API Publishable Key (Production Environment)',
    'stripe_market_field_prod_publish',
    'stripe_market_section',
    'stripe_market_section_api'
  );
  add_settings_field(
    'stripe_prod_client',
    'Client ID (Production Environment)',
    'stripe_market_field_prod_client',
    'stripe_market_section',
    'stripe_market_section_api'
  );

  add_settings_section(
    'stripe_market_section_ssl',
    '',
    'stripe_market_options_header_ssl',
    'stripe_market_section'
  );

  add_settings_field(
    'stripe_modal_ssl',
    'Enable SSL for modal pop-up?',
    'stripe_market_field_ssl',
    'stripe_market_section',
    'stripe_market_section_ssl'
  );

}

/**
 * Options Page Headers (blank)
 *
 * @since 1.0
 *
 */

function stripe_market_options_header () { ?>
	<h2>General</h2>
<?php }

function stripe_market_options_header_api () { ?>
	<h2>API</h2>
<?php }

function stripe_market_options_header_ssl () { ?>
	<h2>SSL</h2>
<?php }

/**
 * Individual Fields
 *
 * @since 1.0
 *
 */
function stripe_market_field_header () {

	$options = get_option( 'stripe_market_options' );
	$value = $options['stripe_header']; ?>

	<input id="setting_api" name="stripe_market_options[stripe_header]" type="text" size="40" value="<?php echo esc_attr( $value ); ?>" />

<?php }

function stripe_market_field_recent () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_recent_switch" name="stripe_market_options[stripe_recent_switch]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_recent_switch'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function stripe_market_field_css () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_css_switch" name="stripe_market_options[stripe_css_switch]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_css_switch'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function stripe_market_field_labels () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_labels_on" name="stripe_market_options[stripe_labels_on]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_labels_on'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function stripe_market_field_placeholders () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_placeholders_switch" name="stripe_market_options[stripe_placeholders_on]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_placeholders_on'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function stripe_market_field_email_required () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_email_required" name="stripe_market_options[stripe_email_required]">

	<?php foreach( $items as $item ) {

		$selected = $options['stripe_email_required'] === $item ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

function stripe_market_field_switch () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_api_switch" name="stripe_market_options[stripe_api_switch]">

		<?php foreach( $items as $item ) {

			$selected = $options['stripe_api_switch'] === $item ? 'selected="selected"' : ''; ?>

			<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

		<?php } ?>

	</select>

<?php }

function stripe_market_field_currency () {

	$options = get_option( 'stripe_market_options' );
	$items = array( 'USD', 'CAD', 'GBP', 'EUR', 'AUD' ); ?>

	<select id="stripe_currency" name="stripe_market_options[stripe_currency]">

		<?php foreach( $items as $item ) {

			$selected = $options['stripe_currency'] === $item ? 'selected="selected"' : ''; ?>

			<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

		<?php } ?>

	</select>

<?php
}

/**
 * Test
 */
function stripe_market_field_test () {
	$options = get_option( 'stripe_market_options' );
  $value = $options['stripe_test_api'];
?>

  <input
    id="setting_api"
    name="stripe_market_options[stripe_test_api]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

function stripe_market_field_test_publish () {
	$options = get_option( 'stripe_market_options' );
  $value = $options['stripe_test_api_publish'];
?>

  <input
    id="setting_api"
    name="stripe_market_options[stripe_test_api_publish]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

function stripe_market_field_test_client () {

  $options = get_option( 'stripe_market_options' );
  $value = $options['stripe_test_client'];
?>

  <input
    id="settings_api"
    name="stripe_market_options[stripe_test_client]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

/**
 * Prod
 */
function stripe_market_field_prod () {

	$options = get_option( 'stripe_market_options' );
  $value = $options['stripe_prod_api'];
?>

  <input
    id="setting_api"
    name="stripe_market_options[stripe_prod_api]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

function stripe_market_field_prod_publish () {

	$options = get_option( 'stripe_market_options' );
	$value = $options['stripe_prod_api_publish']; ?>

  <input
    id="setting_api"
    name="stripe_market_options[stripe_prod_api_publish]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

function stripe_market_field_prod_client () {

  $options = get_option( 'stripe_market_options' );
  $value = $options['stripe_prod_client'];
?>

  <input
    id="settings_api"
    name="stripe_market_options[stripe_prod_client]"
    class="code"
    type="text"
    size="40"
    value="<?php echo esc_attr( $value ); ?>"
  />

<?php
}

function stripe_market_field_ssl () {
	$options = get_option( 'stripe_market_options' );
	$items = array( 'Yes', 'No' ); ?>

	<select id="stripe_modal_ssl" name="stripe_market_options[stripe_modal_ssl]">

	<?php foreach( $items as $item ) {

		$selected = ($options['stripe_modal_ssl']==$item) ? 'selected="selected"' : ''; ?>

		<option value="<?php echo esc_attr( $item ); ?>" <?php echo $selected; ?>><?php echo esc_html( $item ); ?></option>

	<?php } ?>

	</select>

<?php }

/**
 * Register Options Page
 *
 * @since 1.0
 *
 */
function stripe_market_add_page() {
	add_options_page( 'Stripe Market', 'Stripe Market', 'manage_options', 'stripe_market', 'stripe_market_options_page' );
}

/**
 * Create Options Page Content
 *
 * @since 1.0
 *
 */
function stripe_market_options_page() { ?>

	<script type="text/javascript">
		jQuery( function() {
			jQuery( '#stripe-market-tabs' ).tabs();
		} );
	</script>

	<div id="stripe-market-tabs">

		<h1 class="stripe-title">Stripe Market</h1>

		<ul id="stripe-market-tabs-nav">
			<li><a href="#stripe-market-tab-transactions">Transactions</a></li>
		  <li><a href="#stripe-market-tab-products">Products</a></li>
			<li><a href="#stripe-market-tab-settings">Settings</a></li>
			<li><a href="#stripe-market-tab-about">About</a></li>
		</ul>

		<div style="clear:both"></div>

		<div id="stripe-market-tab-transactions">

			<table class="stripe-market-transactions">

			  <thead>

			  <tr>

				  <th style="width:44px;"><div class="dot-stripe-live"></div><div class="dot-stripe-public"></div></th>
				  <th style="width:200px;">Person</th>
				  <th style="width:140px;">Net Amount (Fee)</th>
				  <th style="width:80px;">Date</th>

				  <th>Comment</th>

			  </tr></thead>

			<?php stripe_market_options_display_trx(); ?>

			<p style="color:#777">The amount of payments display is limited to 500. Log in to your <a href="https://manage.stripe.com/dashboard">Stripe dashboard</a> to see more.</p>
			<div style="color:#777"><div class="dot-stripe-live"></div>Live Environment (as opposed to Test API)</div>
			<div style="color:#777"><div class="dot-stripe-public"></div>Will show in Widget (as opposed to only being visible to you)</div>

			<br />

			<form method="POST">
				<input type="hidden" name="stripe_market_delete_tests" value="1">
				<input type="submit" class="button" value="Delete all test transactions">
			</form>

		</div>

		<div id="stripe-market-tab-products">

				 <table class="stripe-market-products">
					<thead><tr class="stripe-market-absolute"></tr><tr>

						<th style="width:100px;">Progress</th>
						<th style="width:200px;">Raised (Target)</th>
						<th style="width:200px;">Product</th>
						<th>Description</th>

					</tr></thead>

					<?php

					// Content

					echo '<td><div class="progress-stripe-wrap"><div class="progress-stripe-value" style="width:40px"></div></div></td>';
					echo '<td>' . $person . '</td>';
					echo '<td>' . $received . '</td>';
					echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
					echo '<td class="stripe-comment">"' . $content . '"</td>';

					//echo '</tr>';


					//endwhile;

					?>


				</table>

		</div>

		<div id="stripe-market-tab-settings">

			<form action="options.php" method="post">
				<?php settings_fields( 'stripe_market_options' ); ?>
				<?php do_settings_sections( 'stripe_market_section' ); ?>
				<br />
				<input name="Submit" type="submit" class="button button-primary button-large" value="<?php _e( 'Save Changes', 'stripe-market' ); ?>" />
			</form>

			<p style="margin-top:20px;color:#777">Test payments using the <strong>Test Environment</strong> first. You can use the following details:</p>
			<ul style="color:#777">
				<li><strong>Card Number</strong> 4242424242424242</li>
				<li><strong>Card Month</strong> 05</li>
				<li><strong>Card Year</strong> 2015</li>
			</ul>
			<p style="color:#777"><strong>Note:</strong> CVC is optional when payments are made.</p>

		</div>

		<div id="stripe-market-tab-about">

			<p>This plugin was orginially created by <a href="http://www.twitter.com/noeltock" target="_blank">@noeltock</a> and <a href="http://hmn.md">Human Made</a>. It's been expanded to a "market logic" app by <a href="http://nerdfiles.net">nerdfiles</a>.</p>
			<!--p>If you need any support, please use the <a href="http://wordpress.org/tags/stripe-market?forum_id=10">forums</a>, this is the only location we will provide support. If you are interested in contributing or raising development issues, please visit the <a href="https://github.com/humanmade/stripe-market">Github respository</a>. Thank you!</p-->

	</div>

<?php }

function stripe_market_delete_tests() {

	if ( isset( $_POST['stripe_market_delete_tests'] ) && $_POST['stripe_market_delete_tests'] === '1' ) {

		$test_transactions = new WP_query( array(
			'post_type' => 'stripe-market-trx',
			'post_status' => 'publish',
			'posts_per_page' => 500
		) );

		while ( $test_transactions->have_posts() ) : $test_transactions->the_post();

			// Delete Post
			if ( get_post_meta( get_the_id(), 'stripe-market-live', true ) === 'TEST' ) {
				var_dump( the_title() );
				wp_delete_post( get_the_id(), true );
			}

		endwhile;

		wp_redirect( wp_get_referer() );

		exit;

	}

}
add_action( 'admin_init', 'stripe_market_delete_tests');

add_action( 'show_user_profile', 'stripe_market_profile_fields' );
add_action( 'edit_user_profile', 'stripe_market_profile_fields' );

function stripe_market_profile_fields( $user ) {

  // Set labels for meta data
  $key__pub_key = 'STRIPE_PUB_KEY';
  $key__access_token = 'STRIPE_ACCESS_TOKEN';

?>

	<h3 id="stripe-information">Stripe Information</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Public Key</label></th>
			<td>
        <input
          type="text"
          name="stripe_market_users[stripe_public_key]"
          id="stripe_market_users[stripe_public_key]"
          value="<?php echo esc_attr( get_user_meta($user->id,  $key__pub_key, true ) ); ?>"
          class="regular-text"
        /><br />
				<span class="description">Your Stripe Public Key</span>
			</td>
		</tr>

		<tr>
			<th><label for="twitter">Stripe Access Token</label></th>
			<td>
        <input
          type="text"
          name="stripe_market_users[stripe_access_token]"
          id="stripe_market_users[stripe_access_token]"
          value="<?php echo esc_attr( get_user_meta($user->id,  $key__access_token, true ) ); ?>"
          class="regular-text"
        /><br />
				<span class="description">Your Stripe Access Token</span>
			</td>
		</tr>

	</table>
<?php }
