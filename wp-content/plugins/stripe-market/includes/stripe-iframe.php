<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title><?php _e( 'Stripe Payment','stripe-market' ); ?></title>
        <link
          rel="stylesheet"
          href="<?php echo esc_url( STRIPE_MARKET_URL ) . 'css/stripe-market-display.css'; ?>"
        />

        <script type="text/javascript">
            //<![CDATA[
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            var wpstripekey = '<?php echo esc_js( STRIPE_MARKET_KEY ); ?>';
            //]]>;
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="https://js.stripe.com/v1/"></script>
        <script src="<?php echo esc_js( STRIPE_MARKET_URL ) . 'js/stripe-market.js'; ?>"></script>

    </head>

    <body>
        <?php
          $pr = isset($_GET['pr']) ? esc_attr($_GET['pr']) : null;
          $pa = isset($_GET['pa']) ? esc_attr($_GET['pa']) : null;
          $am = isset($_GET['am']) ? esc_attr($_GET['am']) : null;
        ?>

        <?php echo stripe_market_form($pr, $pa, $am); ?>

    </body>

</html>
