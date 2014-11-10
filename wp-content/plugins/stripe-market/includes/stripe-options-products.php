<?php

/**
 * Update product Transactions Array
 *
 * @param $action either 'add' or 'remove'
 * @param $post_id ID of Stripe product (custom post type)
 * @param $trx_id ID of Individual Transactions (custom post type)
 */
function stripe_market_update_product_transactions( $action, $post_id, $trx_id) {
    // Get & update transactions list associated with product
    $transactions = get_post_meta( $post_id, 'stripe-market-product-transactions' );
    if ( $action == 'add' ) {
        array_push($transactions, $trx_id);
    } else {
        $position = array_search( $trx_id, $transactions );
        unset( $transactions[$position] );
    }
    update_post_meta( $post_id, 'stripe-market-product-transactions', $transactions);
    // Update transactions total
    foreach ( $transactions as $transaction ) {
        $amount = get_post_meta( $transaction, 'stripe-market-amount' );
        $sum .= $amount;
    }
    update_post_meta( $post_id, 'stripe-market-product-funded', $sum );
}


/**
 * Products - Display products within options page
 */
function stripe_market_options_display_products() {

        // Query Custom Post Types
        $args = array(
            'post_type' => 'stripe-market-products',
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => 'stripe-market-completion',
            'order' => 'ASC',
            'posts_per_page' => 50
        );

        // - query
        $my_query = null;
        $my_query = new WP_query( $args );

        while ( $my_query->have_posts() ) : $my_query->the_post();

            $time_format = get_option( 'time_format' );
            // - variables -
            $custom = get_post_custom( get_the_ID() );
            $id = ( $my_query->post->ID );
            $public = $custom["stripe-market-public"][0];
            $live = $custom["stripe-market-live"][0];
            $name = $custom["stripe-market-name"][0];
            $email = $custom["stripe-market-email"][0];
            $content = get_the_content();
            $date = $custom["stripe-market-date"][0];
            $cleandate = date('d M', $date);
            $cleantime = date('H:i', $date);
            $amount = $custom["stripe-market-amount"][0];
            $fee = ($custom["stripe-market-fee"][0]) / 100;
            $net = round($amount - $fee, 2);
            echo '<tr>';

            // Dot
            if ( $live == 'LIVE' ) {
                $dotlive = '<div class="dot-stripe-live"></div>';
            } else {
                $dotlive = '<div class="dot-stripe-test"></div>';
            }

            if ( $public == 'YES' ) {
                $dotpublic = '<div class="dot-stripe-public"></div>';
            } else {
                $dotpublic = '<div class="dot-stripe-test"></div>';
            }

            // Person
            $img = get_avatar( $email, 32 );
            $person = $img . ' <span class="stripe-name">' . $name . '</span>';
            // Received
            $received = '<span class="stripe-netamount"> + ' . $net . '</span> (-' . $fee . ')';
            // Content
            echo '<td>' . $dotlive . $dotpublic . '</td>';
            echo '<td>' . $person . '</td>';
            echo '<td>' . $received . '</td>';
            echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
            echo '<td class="stripe-comment">"' . $content . '"</td>';
            echo '</tr>';

        endwhile;

    }
?>
