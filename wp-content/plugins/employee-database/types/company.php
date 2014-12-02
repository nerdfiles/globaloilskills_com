<?php
new Company;             // First class object
class Company {

  var $single = "Company"; 	// this represents the singular name of the post type
  var $plural = "Companies"; 	// this represents the plural name of the post type
  var $type 	= "company"; 	// this is the actual type

  # credit: http://w3prodigy.com/behind-wordpress/php-classes-wordpress-plugin/
  function CompanyScaffolding()
  {
    $this->__construct();
  }

  function __construct()
  {
    # Place your add_actions and add_filters here
    add_action( 'init', array( &$this, 'init_company' ) );
    add_action( 'init', array( &$this, 'add_company_type') );

    # Add image support
    add_theme_support('post-thumbnails', array( $this->type ) );
    add_image_size(strtolower($this->plural).'-thumb-s', 220, 160, true);
    add_image_size(strtolower($this->plural).'-thumb-m', 300, 180, true);

    # Add Post Type to Search
    add_filter('pre_get_posts', array( &$this, 'query_company_post_type') );

    # Add Custom Taxonomies
    add_action( 'init', array( &$this, 'add_company_taxonomies'), 111 );

    # Add meta box
    add_action('add_meta_boxes', array( &$this, 'add_company_custom_metaboxes') );

    # Save entered data
    add_action('save_post', array( &$this, 'save_company_postdata') );

  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function init_company($options = null){
    if($options) {
      foreach($options as $key => $value){
        $this->$key = $value;
      }
    }
  }

  # @credit: http://www.wpinsideout.com/advanced-custom-post-types-php-class-integration
  function add_company_type(){
    $labels = array(
      'name' => _x($this->plural, 'post type general name'),
      'menu_name' => __('Companies'),
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
      'menu_position' => 11,
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


  function query_company_post_type($query) {
    if(is_category() || is_tag()) {
      $post_type = get_query_var('post_type');
    if($post_type) {
      $post_type = $post_type;
    } else {
      $post_type = array($this->type); // replace cpt to your custom post type
    }
    $query->set('post_type', $post_type);
    return $query;
    }
  }

  function add_company_taxonomies() {

    register_taxonomy_for_object_type( 'category', 'job_posting' );

    register_taxonomy(
      'company-location',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Company Location' ),
          'singular_name' => __( 'Company Location' ),
          'all_items' => __( 'All Company Locations' ),
          'add_new_item' => __( 'Add Company Location' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-location'
        ),
      )
    );

    register_taxonomy(
      'company-ranking',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Ranking' ),
          'singular_name' => __( 'Ranking' ),
          'all_items' => __( 'All Rankings' ),
          'add_new_item' => __( 'Add Ranking' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-ranking'
        ),
      )
    );

    register_taxonomy(
      'company-industry',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Industry Codes' ),
          'singular_name' => __( 'Industry' ),
          'all_items' => __( 'All Industries' ),
          'add_new_item' => __( 'Add Industry' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-industry'
        ),
      )
    );

    register_taxonomy(
      'company-status',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Company Status' ),
          'singular_name' => __( 'Company Status' ),
          'all_items' => __( 'All Company Statuses' ),
          'add_new_item' => __( 'Add Company Status' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-status'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_company_custom_metaboxes() {
    add_meta_box( 'company_metabox1', 'Details', array( &$this, 'company_metabox1'), $this->type, 'normal', 'high' );
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function company_metabox1() {

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
    <label for="data[annual_sales]">Annual Sales</label>
    <input
      type="number"
      step="any"
      id= "data[annual_sales]"
      name="data[annual_sales]"
      value="<?php echo $annual_sales[0] ?>"
      placeholder="<?php echo $annual_sales[0] ? $annual_sales[0] : 'USD' ?>"
      title="Determines Annual Sales Growth, etc."
    />
    </p>

    <p>
    <label for="data[number_of_employees]">Number of Employees</label>
    <input
      type="number"
      id= "data[number_of_employees]"
      name="data[number_of_employees]"
      value="<?php echo $number_of_employees[0] ?>"
      placeholder="Determines Employee Growth Metrics, etc."
      title="Determines Employee Growth Metrics, etc."
    />
    </p>

    <p>
    <label for="data[market_cap]">Market Cap</label>
    <input
      type="number"
      step="any"
      id= "data[market_cap]"
      name="data[market_cap]"
      value="<?php echo $market_cap[0] ?>"
      placeholder="<?php echo $market_cap[0] ? $market_cap[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[square_footage]">Square Footage</label>
    <input
      type="number"
      step="any"
      id= "data[square_footage]"
      name="data[square_footage]"
      value="<?php echo $square_footage[0] ?>"
      placeholder="<?php echo $square_footage[0] ? $square_footage[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[net_income]">Net Income</label>
    <input
      type="number"
      step="any"
      id= "data[net_income]"
      name="data[net_income]"
      value="<?php echo $net_income[0] ?>"
      placeholder="<?php echo $net_income[0] ? $net_income[0] : 'USD' ?>"
      title="Determines Net Income Growth, etc."
    />
    </p>

    <p>
    <label for="data[advertising_expense]">Advertising Expense</label>
    <input
      type="number"
      step="any"
      id= "data[advertising_expense]"
      name="data[advertising_expense]"
      value="<?php echo $advertising_expense[0] ?>"
      placeholder="<?php echo $advertising_expense[0] ? $advertising_expense[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[research_expense]">Research Expense</label>
    <input
      type="number"
      step="any"
      id= "data[research_expense]"
      name="data[research_expense]"
      value="<?php echo $research_expense[0] ?>"
      placeholder="<?php echo $research_expense[0] ? $research_expense[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[assets]">Assets</label>
    <input
      type="number"
      step="any"
      id= "data[assets]"
      name="data[assets]"
      value="<?php echo $assets[0] ?>"
      placeholder="<?php echo $assets[0] ? $assets[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[filing_date]">filing_date</label>
    <input
      type="number"
      step="any"
      id= "data[filing_date]"
      name="data[filing_date]"
      value="<?php echo $filing_date[0] ?>"
      placeholder="<?php echo $filing_date[0] ? $filing_date[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[trading_date]">trading_date</label>
    <input
      type="number"
      step="any"
      id= "data[trading_date]"
      name="data[trading_date]"
      value="<?php echo $trading_date[0] ?>"
      placeholder="<?php echo $trading_date[0] ? $trading_date[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[exchange_rate]">exchange_rate</label>
    <input
      type="number"
      step="any"
      id= "data[exchange_rate]"
      name="data[exchange_rate]"
      value="<?php echo $exchange_rate[0] ?>"
      placeholder="<?php echo $exchange_rate[0] ? $exchange_rate[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[currency_enum]">currency_enum</label>
    <input
      type="text"
      id= "data[currency_enum]"
      name="data[currency_enum]"
      value="<?php echo $currency_enum[0] ?>"
      placeholder="<?php echo $currency_enum[0] ? $currency_enum[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[duns]">duns</label>
    <input
      type="text"
      id= "data[duns]"
      name="data[duns]"
      value="<?php echo $duns[0] ?>"
      placeholder="<?php echo $duns[0] ? $duns[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[industry_focus]">industry_focus</label>
    <input
      type="number"
      step="any"
      id= "data[industry_focus]"
      name="data[industry_focus]"
      value="<?php echo $industry_focus[0] ?>"
      placeholder="<?php echo $industry_focus[0] ? $industry_focus[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[exchange_focus]">exchange_focus</label>
    <input
      type="number"
      step="any"
      id= "data[exchange_focus]"
      name="data[exchange_focus]"
      value="<?php echo $exchange_focus[0] ?>"
      placeholder="<?php echo $exchange_focus[0] ? $exchange_focus[0] : 'USD' ?>"
    />
    </p>

    <!-- #category
       -<p>
       -<label for="data[franchise_status]">franchise_status</label>
       -<input
       -  type="number"
       -  step="any"
       -  id= "data[franchise_status]"
       -  name="data[franchise_status]"
       -  value="<?php echo $franchise_status[0] ?>"
       -  placeholder="<?php echo $franchise_status[0] ? $franchise_status[0] : 'USD' ?>"
       -/>
       -</p>
       -->

    <!-- #category
       -<p>
       -<label for="data[home_based_status]">home_based_status</label>
       -<input
       -  type="text"
       -  id= "data[home_based_status]"
       -  name="data[home_based_status]"
       -  value="<?php echo $home_based_status[0] ?>"
       -  placeholder="<?php echo $currency_status[0] ? $currency_status[0] : 'USD' ?>"
       -/>
       -</p>
       -->

    <!-- #category
       -<p>
       -<label for="data[subsidiary_status]">subsidiary_status</label>
       -<input
       -  type="number"
       -  step="any"
       -  id= "data[subsidiary_status]"
       -  name="data[subsidiary_status]"
       -  value="<?php echo $subsidiary_status[0] ?>"
       -  placeholder="<?php echo $subsidiary_status[0] ? $subsidiary_status[0] : 'USD' ?>"
       -/>
       -</p>
       -->

    <!-- # category
       -<p>
       -<label for="data[ownership_types]">ownership_types</label>
       -<input
       -  type="number"
       -  step="any"
       -  id= "data[ownership_types]"
       -  name="data[ownership_types]"
       -  value="<?php echo $ownership_types[0] ?>"
       -  placeholder="<?php echo $ownership_types[0] ? $ownership_types[0] : 'USD' ?>"
       -/>
       -</p>
       -->

    <p>
    <label for="data[founding_year]">founding_year</label>
    <input
      type="number"
      step="any"
      id= "data[founding_year]"
      name="data[founding_year]"
      value="<?php echo $founding_year[0] ?>"
      placeholder="<?php echo $founding_year[0] ? $founding_year[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label for="data[location_types]">location_types</label>
    <input
      type="number"
      step="any"
      id= "data[location_types]"
      name="data[location_types]"
      value="<?php echo $location_types[0] ?>"
      placeholder="<?php echo $location_types[0] ? $location_types[0] : 'USD' ?>"
    />
    </p>

    <!-- #category
       -<p>
       -<label for="data[legal_status_codes]">legal_status_codes</label>
       -<input
       -  type="number"
       -  step="any"
       -  id= "data[legal_status_codes]"
       -  name="data[legal_status_codes]"
       -  value="<?php echo $legal_status_codes[0] ?>"
       -  placeholder="<?php echo $legal_status_codes[0] ? $legal_status_codes[0] : 'USD' ?>"
       -/>
       -</p>
       -->

    <style type="text/css">
      #company_metabox1 label {
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


  function save_company_postdata(){
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
