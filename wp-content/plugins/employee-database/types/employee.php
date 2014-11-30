<?php

/**
 * candidate Entity
 *
 * @TODO Breezify
 */
new CandidatePostType;             // First class object
class CandidatePostType {

  var $single = "Candidate"; 	// this represents the singular name of the post type
  var $plural = "Candidates"; 	// this represents the plural name of the post type
  var $type 	= "candidate"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function CandidateScaffolding()
  {
    $this->__construct();
  }

  function __construct()
  {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init_candidate' ) );
    add_action( 'init', array( &$this, 'add_candidate_post_type') );

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_candidate_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_candidate_taxonomies'), 114 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_candidate_custom_metaboxes') );

    # Save entered data
    add_action('save_post', array( &$this, 'save_candidate_postdata') );

  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function init_candidate($options = null){
    if($options) {
      foreach($options as $key => $value){
        $this->$key = $value;
      }
    }
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function add_candidate_post_type(){
    $labels = array(
      'name' => _x($this->plural, 'post type general name'),
      'menu_name' => __('Candidates'),
      'singular_name' => _x($this->single, 'post type singular name'),
      'add_new' => _x('Add ' . $this->single, $this->single),
      'add_new_item' => __('Add New ' . $this->single),
      'edit_item' => __('Edit ' . $this->single),
      'new_item' => __('New ' . $this->single),
      'view_item' => __('View ' . $this->single),
      'search_items' => __('Search ' . $this->plural),
      'not_found' =>  __('No ' . $this->plural . ' Found'),
      'not_found_in_trash' => __('No ' . $this->plural . ' found in Trash'),
      'parent_item_colon' => ''
    );

    $options = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array('slug' => strtolower($this->plural)),
      'capability_type' => 'page',
      'hierarchical' => false,
      'has_archive' => true,
      'menu_position' => 20,
      'show_in_nav_menus' => true,
      'taxonomies' => array(),
      'supports' => array(
        'title',
        'editor',
        #'author',
        'thumbnail',
        //'excerpt',
        //'comments'
      ),
    );
    register_post_type($this->type, $options);
    flush_rewrite_rules( false );
  }

  function query_candidate_post_type($query) {
    if(is_category() || is_tag()) {
      $post_type = get_query_var('post_type');
    if($post_type) {
      $post_type = $post_type;
    } else {
      $post_type = array($this->type); // replace cpt to your custom post type
    }
    $query->set('post_type',$post_type);
    return $query;
    }
  }

  function add_candidate_taxonomies() {

    register_taxonomy(
      'location',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Location' ),
          'singular_name' => __( 'Location' ),
          'all_items' => __( 'All Locations' ),
          'add_new_item' => __( 'Add Location' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'location'
        ),
      )
    );

    register_taxonomy(
      'industry',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Industry' ),
          'singular_name' => __( 'Industry' ),
          'all_items' => __( 'All Industries' ),
          'add_new_item' => __( 'Add Industry' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'industry'
        ),
      )
    );

    register_taxonomy(
      'role',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Role' ),
          'singular_name' => __( 'Role' ),
          'all_items' => __( 'All Roles' ),
          'add_new_item' => __( 'Add Role' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'role'
        ),
      )
    );

    register_taxonomy(
      'candidate-status',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Status' ),
          'singular_name' => __( 'Status' ),
          'all_items' => __( 'All Statuses' ),
          'add_new_item' => __( 'Add Status' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'candidate-status'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_candidate_custom_metaboxes() {
    add_meta_box( 'candidate_metabox1', 'Details', array( &$this, 'candidate_metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function candidate_metabox1() {

    global $post;
    extract(get_post_custom($post->ID));

    wp_nonce_field( plugin_basename(__FILE__), 'noncename' );  // Use nonce for verification
  ?>

    <p>
    <label for="data[position_title]">Position Title</label>
    <input type="text" id= "data[position_title]" name="data[position_title]" value="<?php echo $position_title[0] ?>"  placeholder="5-6 Word Title" size="75" />
    </p>

    <p>
    <label for="data[company_name]">Company Name</label>
    <input
      type="text"
      id= "data[company_name]"
      name="data[company_name]"
      value="<?php echo $company_name[0] ? $company_name[0] : 'Independent Project' ?>"
    />
    </p>

    <p>
    <label for="data[salary]">Salary</label>
    <input
      type="number"
      id= "data[salary]"
      name="data[salary]"
      value="<?php echo $salary[0] ?>"
      placeholder="<?php echo $currency[0] ? $currency[0] : 'USD' ?>"
      size="25"
    />
    </p>

    <p>
    <label for="data[applicant_url]">Website</label>
    <input
      type="url"
      id= "data[applicant_url]"
      name="data[applicant_url]"
      value="<?php echo $applicant_url[0] ?>"
      placeholder="http://www.shell.com/"
      size="75"
    />
    </p>
    <style type="text/css">
      #candidate_metabox1 label {
        width: 150px;
        display: -moz-inline-stack;
        display: inline-block;
        zoom: 1;
        *display: inline;
      }
      div.tabs-panel {
        height: 80px!important;
      }
    </style>
  <?php
  }


  function save_candidate_postdata(){
    if ( empty($_POST) || $_POST['post_type'] !== $this->type || !wp_verify_nonce( $_POST['noncename'], plugin_basename(__FILE__) )) {
      return $post_id;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
          return $post_id;


    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_page', $post_id ) )
        return $post_id;
    } else {
      if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }

    if($_POST['post_type'] == $this->type) {
      global $post;
      foreach($_POST['data'] as $key => $val) {
        update_post_meta($post->ID, $key, $val);
      }
    }

  }

}
