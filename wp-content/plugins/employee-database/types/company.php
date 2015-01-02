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
    add_action( 'add_meta_boxes', array( &$this, 'add_company_custom_metaboxes') );

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
      'capability_type' => 'post',
      'hierarchical' => false,
      'has_archive' => true,
      'menu_position' => 11,
      //'show_in_nav_menus' => true,
      'taxonomies' => array(),
      'supports' => array(
        'title',
        'editor',
        //'author',
        'thumbnail',
        //'excerpt',
        //'comments'
      ),
    );
    register_post_type($this->type, $options);
    //flush_rewrite_rules( false );
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

    /**
     * Categories NOS (Not Otherwise Specified)
     */
    register_taxonomy_for_object_type( 'category', 'company' );

    /**
     * Legal Status Codes
     *
     * @see http://www.epo.org/searching/data/data/tables/legal-status/usa.html
     */
    register_taxonomy(
      'legal-status-codes',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Legal Status Codes' ),
          'singular_name' => __( 'Legal Status Codes' ),
          'all_items' => __( 'All Legal Status Codes' ),
          'add_new_item' => __( 'Add Legal Status Codes' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'legal-status-codes'
        ),
      )
    );

    /**
     * Suborganization
     *
     * @example Fair Trade, Rainforest Action Alliance, MENSA, etc.
     * @see https://schema.org/subOrganization
     */
    register_taxonomy(
      'company-suborganization',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Suborganization' ),
          'singular_name' => __( 'Suborganization' ),
          'all_items' => __( 'All Suborganization' ),
          'add_new_item' => __( 'Add Suborganization' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'suborganization'
        ),
      )
    );

    /**
     * Membership
     *
     * @example Fair Trade, Rainforest Action Alliance, MENSA, etc.
     * @see http://schema.org/memberOf
     */
    register_taxonomy(
      'company-member-of',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Membership' ),
          'singular_name' => __( 'Membership' ),
          'all_items' => __( 'All Memberships' ),
          'add_new_item' => __( 'Add Membership' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'member-of'
        ),
      )
    );

    /**
     * Demands
     *
     * @see http://schema.org/seeks
     */
    register_taxonomy(
      'company-demands',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Demands' ),
          'singular_name' => __( 'Demand' ),
          'all_items' => __( 'All Demands' ),
          'add_new_item' => __( 'Add Demand' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'demands'
        ),
      )
    );

    /**
     * Languages
     *
     * @see http://bls.dor.wa.gov/ownershipstructures.aspx
     */
    register_taxonomy(
      'languages',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Languages' ),
          'singular_name' => __( 'Languages' ),
          'all_items' => __( 'All Languages' ),
          'add_new_item' => __( 'Add Language' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'languages'
        ),
      )
    );

    /**
     * Business Structure
     *
     * @see http://bls.dor.wa.gov/ownershipstructures.aspx
     */
    register_taxonomy(
      'business-structure',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Business Structures' ),
          'singular_name' => __( 'Business Structures' ),
          'all_items' => __( 'All Business Structures' ),
          'add_new_item' => __( 'Add Business Structures' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'business-structure'
        ),
      )
    );

    /**
     * Normative Geographical Descriptors
     *
     * @see http://iatistandard.org/activity-standard/iati-activities/iati-activity/location/location-type/
     */
    register_taxonomy(
      'location-types',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Company Location Types' ),
          'singular_name' => __( 'Company Location Types' ),
          'all_items' => __( 'All Company Location Types' ),
          'add_new_item' => __( 'Add Company Location Types' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'location-types'
        ),
      )
    );

    /**
     * Company Founded Locations
     *
     * @taxonomy
     * Earth > Americas > North America > United States > Texas > Houston
     */
    register_taxonomy(
      'company-founded',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Founded' ),
          'singular_name' => __( 'Founded' ),
          'all_items' => __( 'All Founding Locations' ),
          'add_new_item' => __( 'Add Founding Location' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'founded'
        ),
      )
    );

    /**
     * Company Headquarters Locations
     *
     * @taxonomy
     * Earth > Americas > North America > United States > Texas > Houston
     */
    register_taxonomy(
      'company-headquarters',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Headquarters' ),
          'singular_name' => __( 'Headquarters' ),
          'all_items' => __( 'All Headquarters' ),
          'add_new_item' => __( 'Add Headquarters' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'headquarters'
        ),
      )
    );

    /**
     * Company Locations
     *
     * @taxonomy
     * Earth > Americas > North America > United States > Texas > Houston
     */
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
          'slug' => 'company-locations'
        ),
      )
    );

    /**
     * Company Ranking NOS (Not Otherwise Specified)
     */
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

    /**
     * Company Industries (North America Taxonomy)
     */
    register_taxonomy(
      'company-naics',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'NAICS' ),
          'singular_name' => __( 'naics' ),
          'all_items' => __( 'All NAICS' ),
          'add_new_item' => __( 'Add NAICS' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'naics'
        ),
      )
    );

    /**
     * Company Industries NOS (Not Otherwise Specified)
     */
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
          'slug' => 'company-industries'
        ),
      )
    );

    /**
     * Departments
     * @see https://www.osha.gov/pls/imis/departmentssearch.html?p_departments=&p_search={{TERM}}
     */
    register_taxonomy(
      'company-departments',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Departments' ),
          'singular_name' => __( 'Department' ),
          'all_items' => __( 'All Departments' ),
          'add_new_item' => __( 'Add Department' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'departments'
        ),
      )
    );

    /**
     * International Standard of Industrial Classification of All Economic Activities
     * @see https://www.osha.gov/pls/imis/sicsearch.html?p_sic=&p_search={{TERM}}
     */
    register_taxonomy(
      'isic',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'ISIC' ),
          'singular_name' => __( 'ISIC' ),
          'all_items' => __( 'All ISIC' ),
          'add_new_item' => __( 'Add ISIC' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'isic'
        ),
      )
    );

    /**
     * Company Traded As
     */
    register_taxonomy(
      'traded-as',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Traded As' ),
          'singular_name' => __( 'Traded As' ),
          'all_items' => __( 'All Traded As' ),
          'add_new_item' => __( 'Add Traded As' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'traded-as'
        ),
      )
    );

    /**
     * Company Type
     *
     * @example
     * public, private
     */
    register_taxonomy(
      'company-type',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Company Type' ),
          'singular_name' => __( 'Company Type' ),
          'all_items' => __( 'All Company Types' ),
          'add_new_item' => __( 'Add Company Type' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-type'
        ),
      )
    );

    /**
     * Company Status
     */
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

    /**
     * Company Products
     */
    register_taxonomy(
      'company-products',
      array($this->type),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __( 'Company Products' ),
          'singular_name' => __( 'Company Products' ),
          'all_items' => __( 'All Company Products' ),
          'add_new_item' => __( 'Add Company Products' )
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => array(
          'slug' => 'company-products'
        ),
      )
    );

  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function add_company_custom_metaboxes() {
    // @see http://code.tutsplus.com/articles/attaching-files-to-your-posts-using-wordpress-custom-meta-boxes-part-1--wp-22291
    add_meta_box(
        'company_metabox1',
        'Details',
        array( &$this, 'company_metabox1'),
        $this->type,
        'normal',
        'high'
    );
    /*
     *add_meta_box(
     *  'prfx_meta',
     *  __('Meta Box Title', 'prfx-textdomain'),
     *  array(&$this, 'prfx_meta_callback'),
     *  $this->type,
     *  'normal',
     *  'high'
     *);
     */
  }

  function prfx_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $prfx_stored_meta = get_post_meta( $post->ID );
  ?>

    <p>
      <label for="meta-text" class="prfx-row-title"><?php _e( 'Example Text Input', 'prfx-textdomain' )?></label>
      <input type="file" name="meta-text" id="meta-text" value="<?php if ( isset ( $prfx_stored_meta['meta-text'] ) ) echo $prfx_stored_meta['meta-text'][0]; ?>" />
    </p>

    <?php
  }

  # @credit: http://wptheming.com/2010/08/custom-metabox-for-post-type/
  function company_metabox1() {
      global $post;
      extract(get_post_custom($post->ID));
      wp_nonce_field( plugin_basename(__FILE__), 'noncename' );  // Use nonce for verification
  ?>

    <p>
    <label for="data[legal_name]">Legal Name</label>
    <input
      type="text"
      id= "data[legal_name]"
      name="data[legal_name]"
      value="<?php echo $legal_name[0] ?>"
      placeholder="Legal Name"
      size="75"
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
      placeholder="<?php echo $annual_sales[0] ? $annual_sales[0] : '' ?>"
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
      placeholder="<?php echo $market_cap[0] ? $market_cap[0] : '' ?>"
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
      placeholder="<?php echo $square_footage[0] ? $square_footage[0] : '' ?>"
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
      placeholder="<?php echo $net_income[0] ? $net_income[0] : '' ?>"
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
      placeholder="<?php echo $advertising_expense[0] ? $advertising_expense[0] : '' ?>"
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
      placeholder="<?php echo $research_expense[0] ? $research_expense[0] : '' ?>"
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
      placeholder="<?php echo $assets[0] ? $assets[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[filing_date]">Filing Date</label>
    <input
      type="date"
      id= "data[filing_date]"
      name="data[filing_date]"
      value="<?php echo $filing_date[0] ?>"
      placeholder="<?php echo $filing_date[0] ? $filing_date[0] : '01/01/2000' ?>"
    />
    </p>

    <p>
    <label for="data[trading_date]">Trading Date</label>
    <input
      type="date"
      id= "data[trading_date]"
      name="data[trading_date]"
      value="<?php echo $trading_date[0] ?>"
      placeholder="<?php echo $trading_date[0] ? $trading_date[0] : '01/01/2000' ?>"
    />
    </p>

    <p>
    <label for="data[exchange_rate]">Exchange Rate</label>
    <input
      type="number"
      step="any"
      id= "data[exchange_rate]"
      name="data[exchange_rate]"
      value="<?php echo $exchange_rate[0] ?>"
      placeholder="<?php echo $exchange_rate[0] ? $exchange_rate[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[currency_enum]">Currency Enum</label>
    <input
      type="text"
      id= "data[currency_enum]"
      name="data[currency_enum]"
      value="<?php echo $currency_enum[0] ?>"
      placeholder="<?php echo $currency_enum[0] ? $currency_enum[0] : 'USD' ?>"
    />
    </p>

    <p>
    <label
      for="data[taxid]"
      title="The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain."
    >TaxID</label>
    <input
      type="text"
      id= "data[taxid]"
      name="data[taxid]"
      value="<?php echo $taxid[0] ?>"
      placeholder="<?php echo $taxid[0] ? $taxid[0] : '' ?>"
    />
    </p>

    <p>
    <label
      for="data[vtaxid]"
      title="The Value-added Tax ID of the organization or person."
    >vtaxid</label>
    <input
      type="text"
      id= "data[vtaxid]"
      name="data[vtaxid]"
      value="<?php echo $vtaxid[0] ?>"
      placeholder="<?php echo $vtaxid[0] ? $vtaxid[0] : '' ?>"
    />
    </p>

    <p>
    <label
      for="data[duns]"
      title="Data Universal Numbering System"
    ><a href="https://schema.org/duns">DUNS</a></label>
    <input
      type="text"
      id= "data[duns]"
      name="data[duns]"
      value="<?php echo $duns[0] ?>"
      placeholder="<?php echo $duns[0] ? $duns[0] : '' ?>"
    />
    </p>

    <p>
    <label for="data[hasPOS]">Has POS?</label>
    <input
      type="checkbox"
      id= "data[hasPOS]"
      name="data[hasPOS]"
      value="<?php echo $hasPOS[0] ?>"
    />
    </p>

    <p>
    <label for="data[bitcoin]">Accepts Bitcoin?</label>
    <input
      type="checkbox"
      id= "data[bitcoin]"
      name="data[bitcoin]"
      value="<?php echo $bitcoin[0] ?>"
    />
    </p>

    <p>
    <label for="data[franchise_status]">Franchise?</label>
    <input
      type="checkbox"
      id= "data[franchise_status]"
      name="data[franchise_status]"
      value="<?php echo $franchise_status[0] ?>"
    />
    </p>

    <p>
    <label for="data[home_based_status]">Home Based?</label>
    <input
      type="checkbox"
      id= "data[home_based_status]"
      name="data[home_based_status]"
      value="<?php echo $home_based_status[0] ?>"
    />
    </p>

    <p>
    <label for="data[subsidiary_status]">Subsidiary?</label>
    <input
      type="checkbox"
      id= "data[subsidiary_status]"
      name="data[subsidiary_status]"
      value="<?php echo $subsidiary_status[0] ?>"
    />
    </p>

    <p>
    <label for="data[woman_owned]">Woman owned?</label>
    <input
      type="checkbox"
      id= "data[woman_owned]"
      name="data[woman_owned]"
      value="<?php echo $woman_owned[0] ?>"
    />
    </p>

    <p>
    <label for="data[founding_date]">Founding Date/Year</label>
    <input
      type="number"
      step="any"
      id= "data[founding_date]"
      name="data[founding_date]"
      value="<?php echo $founding_date[0] ?>"
      placeholder="<?php echo $founding_date[0] ? $founding_date[0] : '1841' ?>"
    />
    </p>

    <p>
    <label for="data[dissolution_date]">Dissolution Date</label>
    <input
      type="number"
      step="any"
      id= "data[dissolution_date]"
      name="data[dissolution_date]"
      value="<?php echo $dissolution_date[0] ?>"
      placeholder="<?php echo $dissolution_date[0] ? $dissolution_date[0] : '1841' ?>"
    />
    </p>

    <p>
    <label
      for="data[primary_email]"
    >Primary E-mail</label>
    <input
      type="text"
      id="data[primary_email]"
      name="data[primary_email]"
      value="<?php echo $primary_email[0] ?>"
      placeholder="<?php echo $primary_email[0] ? $primary_email[0] : '' ?>"
    />
    </p>

    <p>
    <label
      for="data[phone_number]"
    >Phone Number</label>
    <input
      type="text"
      id="data[phone_number]"
      name="data[phone_number]"
      value="<?php echo $phone_number[0] ?>"
      placeholder="<?php echo $phone_number[0] ? $phone_number[0] : '' ?>"
    />
    </p>

    <p>
    <label
      for="data[fax_number]"
    >Fax Number</label>
    <input
      type="text"
      id="data[fax_number]"
      name="data[fax_number]"
      value="<?php echo $fax_number[0] ?>"
      placeholder="<?php echo $fax_number[0] ? $fax_number[0] : '' ?>"
    />
    </p>

    <p>
      <label for="logo">Logo</label>
      <input
        type="file"
        id="data[logo]"
        name="data[logo]"
        value="data[logo]"
      />
    </p>

    <p>
      <label for="company_qr_code">Upload your QR code image file here</label>
      <input
        type="file"
        id="data[company_qr_code]"
        name="data[company_qr_code]"
        value="data[company_qr_code]"
      />
    </p>

    <style type="text/css">
      #company_metabox1 label {
        width: 150px;
        display: -moz-inline-stack;
        display: inline-block;
        zoom: 1;
        *display: inline;
      }
      div.tabs-panel {
        height: 80px !important;
      }
    </style>
  <?php
  }


  function save_company_postdata(){
    if ( empty($_POST) || $_POST['post_type'] !== $this->type || !wp_verify_nonce( $_POST['noncename'], plugin_basename(__FILE__) ) ) {
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
