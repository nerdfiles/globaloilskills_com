<?php
new JobPostingType;       // First class object
class JobPostingType {

  var $single = "Job"; 	// this represents the singular name of the post type
  var $plural = "Jobs"; 	// this represents the plural name of the post type
  var $type 	= "job_posting"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function JobPostingScaffolding() {
    $this->__construct();
  }

  function __construct() {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init_job_posting' ) );
    add_action( 'init', array(&$this, 'add_job_posting_post_type'));

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_job_posting_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_job_posting_taxonomies'), 200 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_custom_metaboxes') );

    # Save entered data
    add_action('save_post', array( &$this, 'save_job_posting_postdata') );
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function init_job_posting($options = null){
    if($options) {
      foreach($options as $key => $value){
        $this->$key = $value;
      }
    }
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function add_job_posting_post_type(){
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
      'menu_position' => 8,
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
    //flush_rewrite_rules( false );
  }


  function query_job_posting_post_type($query) {
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

  function add_job_posting_taxonomies() {

    register_taxonomy_for_object_type( 'category', 'job_posting' );

    /**
     * Register Location Category
     *
     * @TODO Supply reference data.
     *
     * frex: Planet > Country > State > City > Locality (Zip Code)
     */
    register_taxonomy(
      'job-location',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Job Location' ),
          'singular_name' => __( 'Job Location' ),
          'all_items' => __( 'All Job Locations' ),
          'add_new_item' => __( 'Add Job Location' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'job-location'
        ),
      )
    );

    /**
     * Register Industry Category
     *
     * frex: Oil and Gas, etc.
     */
    register_taxonomy(
      'job-industry',
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
          'slug' => 'job-industry'
        ),
      )
    );

    /**
     * Register Job Type Category
     *
     * frex: Engineering, Programming, etc.
     */
    register_taxonomy(
      'job-role',
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
          'slug' => 'job-role'
        ),
      )
    );

    register_taxonomy(
      'job-status',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Job Status' ),
          'singular_name' => __( 'Job Status' ),
          'all_items' => __( 'All Job Statuses' ),
          'add_new_item' => __( 'Add Job Status' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'job-status'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_custom_metaboxes() {
    add_meta_box( 'job_posting_metabox1', 'Details', array( &$this, 'job_posting_metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function job_posting_metabox1() {

    global $post;
    extract(get_post_custom($post->ID));

    wp_nonce_field( plugin_basename(__FILE__), 'noncename' );  // Use nonce for verification
  ?>

    <p>
    <label for="data[position_title]">Position Title</label>
    <input
      type="text"
      id= "data[position_title]"
      name="data[position_title]"
      value="<?php echo $position_title[0] ?>"
      placeholder="Position Title"
      size="75" />
    </p>

    <p>
    <label for="data[hiringOrganization]">hiringOrganization</label>
    <input
      type="text"
      id= "data[hiringOrganization]"
      name="data[hiringOrganization]"
      value="<?php echo $hiringOrganization[0] ? $hiringOrganization[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[workHours]">workHours</label>
    <input
      type="text"
      id= "data[workHours]"
      name="data[workHours]"
      value="<?php echo $workHours[0] ? $workHours[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[specialCommitments]">specialCommitments</label>
    <input
      type="text"
      id= "data[specialCommitments]"
      name="data[specialCommitments]"
      value="<?php echo $specialCommitments[0] ? $specialCommitments[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[skills]">skills</label>
    <input
      type="text"
      id= "data[skills]"
      name="data[skills]"
      value="<?php echo $skills[0] ? $skills[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[responsibilities]">responsibilities</label>
    <input
      type="text"
      id= "data[responsibilities]"
      name="data[responsibilities]"
      value="<?php echo $responsibilities[0] ? $responsibilities[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[qualifications]">qualifications</label>
    <input
      type="text"
      id= "data[qualifications]"
      name="data[qualifications]"
      value="<?php echo $qualifications[0] ? $qualifications[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[benefits]">benefits</label>
    <input
      type="text"
      id= "data[benefits]"
      name="data[benefits]"
      value="<?php echo $benefits[0] ? $benefits[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[educationRequirements]">educationRequirements</label>
    <input
      type="text"
      id= "data[educationRequirements]"
      name="data[educationRequirements]"
      value="<?php echo $educationRequirements[0] ? $educationRequirements[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[experienceRequirements]">experienceRequirements</label>
    <input
      type="text"
      id= "data[experienceRequirements]"
      name="data[experienceRequirements]"
      value="<?php echo $experienceRequirements[0] ? $experienceRequirements[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[incentives]">incentives</label>
    <input
      type="text"
      id= "data[incentives]"
      name="data[incentives]"
      value="<?php echo $incentives[0] ? $incentives[0] : '' ?>"
    />
    </p>


    <p>
    <label for="data[baseSalary]">baseSalary</label>
    <input
      type="number"
      step="any"
      id= "data[baseSalary]"
      name="data[baseSalary]"
      value="<?php echo $baseSalary[0] ?>"
      placeholder="<?php echo $currency[0] ? $currency[0] : 'USD' ?>"
      size="25"
    />
    </p>

    <p>
    <label for="data[currency]">currency</label>
    <input
      type="text"
      id= "data[currency]"
      name="data[currency]"
      value="<?php echo $currency[0] ?>"
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
      #job_posting_metabox1 label {
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


  function save_job_posting_postdata(){
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
