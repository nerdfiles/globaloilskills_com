<?php

/**
 * Custom Post Type - Transactions
 */
function create_stripe_market_cpt_trx() {
    $args = array(
        'public' 		    => false,
        'can_export' 	    => true,
        'capability_type'   => 'post',
        'hierarchical' 	    => false,
    );

    register_post_type( 'stripe-market-trx', $args);
}

/**
 * Custom Post Type - Products
 */
function create_stripe_market_cpt_products() {

    $labels = array(
        'name'                  => _x( 'Stripe Products', '', 'stripe-market' ),
        'singular_name'         => _x( 'Project', 'post type singular name', 'stripe-market' ),
        'add_new'               => _x( 'Add New', 'Payments', 'stripe-market' ),
        'add_new_item'          => __( 'Add New Project', 'stripe-market' ),
        'edit_item'             => __( 'Edit Project', 'stripe-market' ),
        'new_item'              => __( 'New Project', 'stripe-market' ),
        'view_item'             => __( 'View Project', 'stripe-market' ),
        'search_items'          => __( 'Search Products', 'stripe-market' ),
        'not_found'             => __( 'No Products found', 'stripe-market' ),
        'not_found_in_trash'    => __( 'No Products found in Trash', 'stripe-market' ),
        'parent_item_colon'     => '',
    );

    $args = array(
        'labels' 		    => $labels,
        'public' 		    => false,
        'can_export' 	    => true,
        'capability_type'   => 'post',
        'hierarchical' 	    => false,
        'supports'		    => array( 'title', 'editor', 'thumbnail' )
    );

    register_post_type( 'stripe-market-products', $args);

}

/**
 * Create Stripe Market Custom Post Type Meta
 */
function create_stripe_market_cpt_meta() {

  add_meta_box(
    "stripe_market_products",
    "Product Information",
    "stripe_market_products_meta",
    "stripe_products",
    "normal",
    "low"
  );

  function stripe_market_products_meta(){
    global $post;
    $desc = get_post_meta($post->ID, 'desc', true);
    echo '<p><label>Desc: </label><input type="text" name="desc" value="' . $desc . '" /><br /><small><em>For awesome people</em></small></p>';
  }

  /*
   Save all our info!!
   */
  function save_details($post_id) {
    global $post;
    /*
     Save Product Info
     */
    if(isset($_POST['post_type']) && ($_POST['post_type'] == "stripe_market_products")) {
      foreach($_POST as $k => $v){
        update_post_meta($post_id, $k, $v);
      }
    }
  }
  add_action("save_post", "save_details");
}

/**
 * Create Product Type Taxonomies
 */
function create_product_type_taxonomies() {

  $labels = array(
    'name' => _x( 'Stripe Product Types', 'products' ),
    'singular_name' => _x( 'Stripe Product Type', 'product' ),
    'search_items' => __( 'Search Stripe Product Types' ),
    'all_items' => __( 'All Stripe Product Types' ),
    'parent_item' => __( 'Parent Genre' ),
    'parent_item_colon' => __( 'Parent Genre:' ),
    'edit_item' => __( 'Edit Stripe Product Type' ),
    'update_item' => __( 'Update Stripe Product Type' ),
    'add_new_item' => __( 'Add New Stripe Product Type' ),
    'new_item_name' => __( 'New Stripe Product Type Name' ),
  );

  register_taxonomy( 'product_type', array( 'stripe_market_products' ), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array(
      'slug' => 'stripe-market-product'
    ),
  ));

}

add_action( 'init', 'create_stripe_market_cpt_trx' );
add_action( 'init', 'create_stripe_market_cpt_products' );
add_action( 'admin_menu', 'create_stripe_market_cpt_meta' );
add_action( 'init', 'create_product_type_taxonomies', 0 );
