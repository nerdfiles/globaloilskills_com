<?php

// Work in Progress

function stripe_market_send_api($amount, $created, $post_id) {

    //set POST variables
    $url = get_site_url() . '/get-post.php';

    $transaction_post = get_post_custom( $post_id );
    $product_id = $transaction_post["stripe-market-productid"][0];
    if ( $product_id ) {
        $product = 1;
        $product_post = get_post_custom( $post_id );
        $product_size = $product_post["stripe-market-product-size"][0];
        $product_raised = $product_post["stripe-market-product-raised"][0];
    } else {
        $product = 0;
        $product_size = 0;
        $product_raised = 0;
    }

    $fields = array(
        'amount'=>urlencode($amount),
        'date'=>urlencode($created),
        'p'=>urlencode($product),
        'ps'=>urlencode($product_size),
        'pr'=>urlencode($product_raised),
        'country'=>urlencode($country),
        'type'=>urlencode($type)
    );

    //url-ify the data for the POST
    $fields_string = '';
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&amp;'; }
    rtrim($fields_string,'&amp;');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

}

function stripe_market_base62_encode($val) {
    $base=62;
    $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    do {
        $i = $val % $base;
        $str = $chars[$i] . $str;
        $val = ($val - $i) / $base;
    } while($val > 0);
    return $str;
}

?>
