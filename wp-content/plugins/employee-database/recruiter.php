<?php
new RecruiterPostType; // Initial call

class RecruiterPostType {

  var $single = "Recruiter"; 	// this represents the singular name of the post type
  var $plural = "Recruiters"; 	// this represents the plural name of the post type
  var $type 	= "recruiter"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function RecruiterPostType()
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
        'editor',
#      	'author',
        'thumbnail',
#      	'excerpt',
        'comments'
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
          'name' => __( 'Jobs' ),
          'singular_name' => __( 'Jobs' ),
          'all_items' => __( 'All Jobs' ),
          'add_new_item' => __( 'Add Jobs' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'job'
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
    <label for="data[recruiter_url]">Recruiter Website</label>
    <input
      type="url"
      id= "data[recruiter_url]"
      name="data[recruiter_url]"
      value="<?php echo $recruiter_url[0] ?>"
      placeholder="https://www.linkedin.com/"
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
