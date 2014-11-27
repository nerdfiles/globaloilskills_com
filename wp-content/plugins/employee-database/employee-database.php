<?php
/*
Plugin Name: Job Postings Database
Plugin URI: http://nerdfiles.net
Author: nerdfiles
Author URI: http://nerdfiles.net
Description: A custom post type that adds Applicants and custom taxonomies.
Needs:
Version: 1.0
*/

include_once('recruiter.php');
//include_once('job.php');

new JobPostType;
class JobPostType {

  var $single = "Job Posting"; 	// this represents the singular name of the post type
  var $plural = "Job Postings"; 	// this represents the plural name of the post type
  var $type 	= "jobposting"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function JobPostPostType()
  {
    $this->__construct();
  }

  function __construct()
  {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init' ) );
    add_action( 'init', array(&$this, 'add_post_type'));

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_taxonomies'), 0 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_custom_metaboxes') );

    # Save entered data
    add_action('save_post', array( &$this, 'save_postdata') );

  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function init($options = null){
    if($options) {
      foreach($options as $key => $value){
        $this->$key = $value;
      }
    }
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function add_post_type(){
    $labels = array(
      'name' => _x($this->plural, 'post type general name'),
      'menu_name' => __('Job Board'),
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
  }


  function query_post_type($query) {
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

  function add_taxonomies() {

    register_taxonomy(
      'location',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'location' ),
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
      'job_type',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Job Type' ),
          'singular_name' => __( 'Job Type' ),
          'all_items' => __( 'All Job Types' ),
          'add_new_item' => __( 'Add Job Type' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'job_type'
        ),
      )
    );

    register_taxonomy(
      'status',
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
          'slug' => 'status'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_custom_metaboxes() {
    add_meta_box( 'metabox1', 'Details', array( &$this, 'metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function metabox1() {

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
      #metabox1 label {
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


  function save_postdata(){
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

new ApplicantPostType; // Initial call
class ApplicantPostType {

  var $single = "Applicant"; 	// this represents the singular name of the post type
  var $plural = "Applicants"; 	// this represents the plural name of the post type
  var $type 	= "applicant"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function ApplicantPostType()
  {
    $this->__construct();
  }

  function __construct()
  {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init' ) );
    add_action( 'init', array(&$this, 'add_post_type'));

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_taxonomies'), 0 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_custom_metaboxes') );

    # Save entered data
    add_action('save_post', array( &$this, 'save_postdata') );

  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function init($options = null){
    if($options) {
      foreach($options as $key => $value){
        $this->$key = $value;
      }
    }
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function add_post_type(){
    $labels = array(
      'name' => _x($this->plural, 'post type general name'),
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
      'taxonomies' => array( 'post_tag' ),
      'supports' => array(
        'title',
        //'editor',
#      	'author',
        'thumbnail',
#      	'excerpt',
        //'comments'
      ),
    );
    register_post_type($this->type, $options);
  }


  function query_post_type($query) {
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

  function add_taxonomies() {
    register_taxonomy(
      'menu',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Department' ),
          'singular_name' => __( 'Departments' ),
          'all_items' => __( 'All Departments' ),
          'add_new_item' => __( 'Add Department' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'menu'
        ),
      )
     );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_custom_metaboxes() {
    add_meta_box( 'metabox1', 'Details', array( &$this, 'metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function metabox1() {

    global $post;
    extract(get_post_custom($post->ID));

    wp_nonce_field( plugin_basename(__FILE__), 'noncename' );  // Use nonce for verification
  ?>
    <p>
    <label for="data[short_title]">Short Title</label>
    <input type="text" id= "data[short_title]" name="data[short_title]" value="<?php echo $short_title[0] ?>"  placeholder="5-6 Word Title" size="75" />
    </p>

    <p>
    <label for="data[currency]">Currency</label>
    <input
      type="text"
      id= "data[currency]"
      name="data[currency]"
      value="<?php echo $currency[0] ? $currency[0] : 'USD' ?>"
      size="10"
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
    <label for="data[applicant_url]">Applicant Website</label>
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
      #metabox1 label {
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


  function save_postdata(){
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

