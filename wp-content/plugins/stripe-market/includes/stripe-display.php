<?php

/**
 * Display Stripe Form
 *
 * @return string Stripe Form (DOM)
 *
 * @since 1.0
 *
 * @param currency
 * @param labels_on
 * @param placeholders_on
 * @param email_required
 *
 */
function stripe_market_form($pr = null, $pa = null, $am = null, $pp = null) {

	$options = get_option( 'stripe_market_options' );

	$currency        = $options['stripe_currency'];
	$labels_on       = $options['stripe_labels_on'] === 'Yes';
	$placeholders_on = $options['stripe_placeholders_on'] === 'Yes';
	$email_required  = $options['stripe_email_required'] === 'Yes';

  $pa_split = explode(';', base64_decode(esc_attr($pa)));
  $payParent = base64_decode(esc_attr($pa));

	ob_start();
?>

	<div id="stripe-market-wrap">
		<form id="stripe-market-payment-form">

    <input
      type="hidden"
      name="action"
      value="stripe_market_charge_initiate"
    />

    <input
      type="hidden"
      name="nonce"
      value="<?php echo wp_create_nonce( 'stripe-market-nonce' ); ?>"
    />

    <input
      type="hidden"
      name="stripe_market_statement_description"
      id="stripe_market_statement_description"
      <?php if ($pr) : ?>
      value="
        <?php echo esc_attr($pr); ?>
        ---
        <?php echo $pa_split; ?>
      "
      <?php endif; ?>
    />

    <?php if ($pa) { ?>
    <input
      type="hidden"
      id="stripe_market_payouts"
      name="stripe_market_payouts"
      value="<?php echo base64_decode(esc_attr($pa)); ?>"
    />
    <?php } ?>

		<div class="stripe-market-details">
			<div class="stripe-market-notification stripe-market-failure payment-errors" style="display:none"></div>
			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="stripe_market_name"><?php _e( 'Name', 'stripe-market' ); ?></label>
				<?php endif; ?>
				<input
          type="text"
          id="stripe_market_name"
          name="stripe_market_name"
          class="stripe-market-name" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'Name', 'stripe-market' ); ?> *"<?php endif;?> autofocus required />
			</div>
			<div class="stripe-row">
				<?php if ( $labels_on) : ?>
					<label for="stripe_market_email"><?php _e( 'Email', 'stripe-market' ); ?></label>
				<?php endif; ?>
        <input
          type="email"
          id="stripe_market_email"
          name="stripe_market_email"
          class="stripe-market-email"
          <?php if ( $placeholders_on ) : ?>
          placeholder="<?php _e( 'E-mail', 'stripe-market' ); ?><?php echo $email_required ? ' *' : ''; ?>"
          <?php endif; ?>
          <?php echo $email_required ? ' required' : ''; ?>
        />
			</div>
			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="stripe_market_comment"><?php _e( 'Comment', 'stripe-market' ); ?></label>
				<?php endif; ?>
        <textarea
          id="stripe_market_comment"
          name="stripe_market_comment"
          class="stripe-market-comment"
          <?php if ( $placeholders_on ) : ?>
          placeholder="<?php _e( 'Comment', 'stripe-market' ); ?>"<?php endif; ?>
        ></textarea>
			</div>
		</div>

    <input
      id="stripe_market_currency"
      name="stripe_market_currency"
      type="hidden"
      readonly="readonly"
      value="<?php echo esc_attr( $currency ); ?>"
    />

		<div class="stripe-market-card">
			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
          <label for="stripe_market_amount">
            <?php printf( __( 'Amount (%s)', 'stripe-market' ), esc_html( $currency ) ); ?>
          </label>
				<?php endif; ?>
        <input
          type="text"
          id="stripe_market_amount"
          name="stripe_market_amount"
          autocomplete="off"
          class="stripe-market-card-amount"
          id="stripe-market-card-amount"

          <?php

/*
 *          $x = 0;
 *
 *          foreach($tags as $i => $pa_split) {
 *            $i >0;
 *            $x = $i;
 *
 *          if ($x != 1 || $payParent) :
 */
          ?>
            readonly="readonly"
          <?php
            /*
             *endif;
             */
          ?>

          <?php
            if ($am) :
          ?>

          value="<?php echo base64_decode(esc_attr($am)); ?>"

          <?php endif; ?>

          <?php if ( $placeholders_on) : ?>placeholder="<?php printf( __( 'Amount (%s)', 'stripe-market' ), $currency ); ?> *"<?php endif; ?>
          required
        />
			</div>

			<div class="stripe-row">
				<?php if ( $labels_on ) : ?>
					<label for="card-number"><?php _e( 'Card Number', 'stripe-market' ); ?></label>
				<?php endif; ?>
				<input type="text" id="card-number" autocomplete="off" class="card-number" <?php if ( $placeholders_on) : ?>placeholder="<?php _e( 'Card Number', 'stripe-market' ); ?> *"<?php endif; ?> required />
			</div>
			<div class="stripe-row">
				<div class="stripe-row-left">
					<?php if ( $labels_on ) : ?>
						<label for="card-cvc"><?php _e( 'CVC Number', 'stripe-market' ); ?></label>
					<?php endif; ?>
					<input type="text" id="card-cvc" autocomplete="off" class="card-cvc" <?php if ( $placeholders_on ) : ?>placeholder="<?php _e( 'CVC Number', 'stripe-market' ); ?> *"<?php endif; ?> maxlength="4" required />
				</div>
				<div class="stripe-row-right">
					<label for="card-expiry" class="stripe-expiry">Expiry</label>
					<select id="card-expiry" class="card-expiry-month">
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5">05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
					<span></span>
					<select class="card-expiry-year">
						<?php $year = date( 'Y',time() );
						$num = 1;
						while ( $num <= 7 ) { ?>
							<option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
							<?php
							$year++;
							$num++;
						} ?>
					</select>
				</div>
			</div>
			</div>
			<?php $options = get_option( 'stripe_market_options' );
			if ( isset( $options['stripe_recent_switch'] ) && $options['stripe_recent_switch'] === 'Yes' ) { ?>
				<div class="stripe-market-meta">
					<div class="stripe-row">
            <input
              type="checkbox"
              name="stripe_market_public"
              value="public"
              checked="checked"
            />
            <label><?php _e( 'Display on Website?', 'stripe-market' ); ?></label>
						<p class="stripe-display-comment"><?php _e( 'If you check this box, the name as you enter it (including the avatar from your e-mail) and comment will be shown in recent donations. Your e-mail address and donation amount will not be shown.', 'stripe-market' ); ?></p>
					</div>
				</div>
			<?php }; ?>
			<div style="clear:both"></div>
			<input type="hidden" name="stripe_market_form" value="1" />
      <button
        type="submit"
        class="stripe-submit-button"
      >
        <span><div class="spinner">&nbsp;</div><?php _e( 'Submit Payment', 'stripe-market' ); ?></span>
      </button>
			<div class="stripe-spinner"></div>

		</form>
	</div>
	<div class="stripe-market-poweredby">
    <?php printf( __( 'Payments powered by %s. No card information is stored on this server.', 'stripe-market' ), '<a href="https://stripe.com/" target="_blank">Stripe</a>' ); ?>
  </div>
  <?php
  $output = apply_filters( 'stripe_market_filter_form', ob_get_contents() );
  ob_end_clean();
  return $output;
}
