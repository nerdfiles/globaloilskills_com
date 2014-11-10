<?php

/**
 * Display Transactions on Options Page
 *
 * @since 1.0
 *
 */

function stripe_market_options_display_trx() {

        // Paging

        function retrievePage() {

            if ((!isset($_POST['pagination'])) || ($_POST['pagination'] == "1")) {
                $paged = 1;
            } else {
                $paged = $_POST['pagination'];
            }
            return intval($paged);

        }

        // Query Custom Post Types

        $args = array(
            'post_type' => 'stripe-market-trx',
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => 'stripe-market-date',
            'order' => 'DESC',
            'posts_per_page' => 10,
            'paged' => retrievePage()
        );

        // - query -
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
            $fee = ($custom["stripe-market-fee"][0])/100;
            $currency = $custom['stripe-market-currency'][0];
            $net = round($amount - $fee,2);

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

            $received = '<span class="stripe-netamount"> + ' . $net . '</span> ' . $currency . ' (-' . $fee . ')';

            // Content

            echo '<td>' . $dotlive . $dotpublic . '</td>';
            echo '<td>' . $person . '</td>';
            echo '<td>' . $received . '</td>';
            echo '<td>' . $cleandate . ' - ' . $cleantime . '</td>';
            echo '<td class="stripe-comment">"' . $content . '"</td>';

            echo '</tr>';

        endwhile;
?>

</table>

<div style="clear:both"></div>

<?php

    function totalPages($transactions) {

        // get total pages

        if ( $transactions > 0 ) {
            $totalpages = floor( $transactions / 10) + 1 ;
        } else {
            return;
        }

        return $totalpages;

    }

    $currentpage = retrievePage();
    $totalpages = totalPages($my_query->found_posts);

    if ( $currentpage > 1 ) {

        echo '<form method="POST" class="pagination">';
        echo '<input type="hidden" name="pagination" value="' . ( retrievePage() - 1 ) . '" />';
        echo '<input type="submit" value="Previous 10" />';
        echo '</form>';

    }

    if ( $currentpage < $totalpages ) {

        echo '<form method="POST" class="pagination">';
        echo '<input type="hidden" name="pagination" value="' . ( retrievePage() + 1 ) . '" />';
        echo '<input type="submit" value="Next 10" />';
        echo '</form>';

    }

    echo ' <div style="clear:both"></div>';

}

?>
