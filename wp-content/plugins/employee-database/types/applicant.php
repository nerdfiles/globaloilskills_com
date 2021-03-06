<?php

new ApplicantPostType;    // First class object
class ApplicantPostType {

  var $single = "Applicant"; 	// this represents the singular name of the post type
  var $plural = "Applicants"; 	// this represents the plural name of the post type
  var $type 	= "applicant"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function ApplicantScaffolding() {
    $this->__construct();
  }

  function __construct() {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init' ) );
    add_action( 'init', array( &$this, 'add_applicant_type') );

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_job_applicant_taxonomies'), 100 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_applicant_custom_metaboxes') );

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
  function add_applicant_type(){
    $labels = array(
      'name' => _x($this->plural, 'post type general name'),
      'singular_name' => _x($this->single, 'post type singular name'),
      'menu_name' => __('Onboarding'),
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
      'menu_position' => 10,
      'show_in_nav_menus' => true,
      'taxonomies' => array(),
      'supports' => array(
        'title',
        'editor',
#      	'author',
        'thumbnail',
#      	'excerpt',
        //'comments'
      ),
    );
    register_post_type($this->type, $options);
    flush_rewrite_rules( false );
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

  function add_job_applicant_taxonomies() {
    /*
    Applicant Name
    Applicant Email
    Job
    Files
    Posted
    Status
    */

    register_taxonomy(
      'applicant-status',
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
          'slug' => 'applicant-status'
        ),
      )
    );

    register_taxonomy(
      'applicant-status',
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
          'slug' => 'applicant-status'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_applicant_custom_metaboxes() {
    add_meta_box( 'applicant_metabox1', 'Details', array( &$this, 'applicant_metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function applicant_metabox1() {

    global $post;
    extract(get_post_custom($post->ID));

    wp_nonce_field( plugin_basename(__FILE__), 'noncename' );  // Use nonce for verification
  ?>

    <p>
    <label for="data[applicant_email]">Applicant E-mail</label>
    <input
      type="text"
      id= "data[applicant_email]"
      name="data[applicant_email]"
      value="<?php echo $applicant_email[0] ?>"
      placeholder="5-6 Word Title"
      size="75"
    />
    </p>

    <p>
    <label for="data[applicant_role]">applicant_role</label>
    <input
      type="text"
      id= "data[applicant_role]"
      name="data[applicant_role]"
      value="<?php echo $applicant_role[0] ? $applicant_role[0] : 'USD' ?>"
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
      #applicant_metabox1 label {
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
?>
