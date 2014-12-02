<?php
/**
 * gos functions file
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

/******************************************************************************\
  Theme support, standard settings, menus and widgets
\******************************************************************************/

/**
 * Hide Help
 */
add_filter( 'contextual_help', 'remove_help_tabs', 999, 3 );
function remove_help_tabs($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}

/**
 * Hide Screen Options
 */
add_filter('screen_options_show_screen', '__return_false');

/**
 * Nuke WordPress Sidebar
 */
function remove_menu() {
  global $menu;
  // Particularly for Google Maps Builder Plugin
  for ($i = 0; $i < 20; ++$i) {
    unset($menu[$i]);
  }
}
//add_action('admin_menu', 'remove_menu', 210);

/**
 * Hide Tribe Event's Calendar
 */
function disable_tribe_dashboard_widget() {
  remove_meta_box('tribe_dashboard_widget', 'dashboard', 'normal');
}
add_action('admin_menu', 'disable_tribe_dashboard_widget');

/**
 * Hide WordPress Welcome Panel
 */
remove_action('welcome_panel', 'wp_welcome_panel');

/**
 * Rework WordPress Dashboard
 */
add_action('admin_init', 'rw_remove_dashboard_widgets');
function rw_remove_dashboard_widgets() {
  remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // right now
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // recent comments
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // incoming links
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // plugins
  remove_meta_box('dashboard_activity', 'dashboard', 'normal');  // quick press
  remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');  // quick press
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');  // recent drafts
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');   // wordpress blog
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');   // other wordpress news
}

function z_remove_media_controls() {
     remove_action( 'media_buttons', 'media_buttons' );
}
add_action('admin_head','z_remove_media_controls');

/**
 * Rework WordPress Menus
 */
function rw_remove_menus () {
  global $menu;
  $restricted = array(
    /*
     *__('Dashboard'),
     *__('Posts'),
     *__('Media'),
     *__('Links'),
     *__('Pages'),
     *__('Appearance'),
     *__('Tools'),
     *__('Users'),
     *__('Settings'),
     *__('Comments'),
     *__('Plugins'),
     *__('Events'),
     *__('Recruiters'),
     *__('Applicants'),
     *__('Contact'),
     *__('FooGallery')
     */
  );
  end ($menu);
  while (prev($menu)) {
    $value = explode(' ',$menu[key($menu)][0]);
    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
  }
}
add_action('admin_menu', 'rw_remove_menus');

/**
 * Dashboard Customizations for http://globaloilstaffing.services
 */
add_action('wp_dashboard_setup', 'dashboard_widgets');
function dashboard_widgets() {
  global $wp_meta_boxes;
  wp_add_dashboard_widget('custom_help_widget', 'Analytics', 'custom_dashboard_help');
}
function custom_dashboard_help() {
  echo '<p>Welcome</p>';
}

add_theme_support( 'post-formats', array( 'image', 'quote', 'status', 'link' ) );
add_theme_support( 'post-thumbnails' );
//set_post_thumbnail_size( 300, 300 );
add_theme_support( 'automatic-feed-links' );

$custom_header_args = array(
  'width'         => 980,
  'height'        => 300,
  'default-image' => get_template_directory_uri() . '/images/header.png',
);
//add_theme_support( 'custom-header', $custom_header_args );

/**
 * Print custom header styles
 * @return void
 */
function gos_custom_header() {
  $styles = '';
  if ( $color = get_header_textcolor() ) {
    echo '<style type="text/css"> ' .
        '.site-header .logo .blog-name, .site-header .logo .blog-description {' .
          'color: #' . $color . ';' .
        '}' .
       '</style>';
  }
}
//add_action( 'wp_head', 'gos_custom_header', 11 );

$custom_bg_args = array(
  'default-color' => 'fba919',
  'default-image' => '',
);
//add_theme_support( 'custom-background', $custom_bg_args );

register_nav_menu( 'main-menu', __( 'Your sites main menu', 'gos' ) );

if ( function_exists( 'register_sidebars' ) ) {

  register_sidebar(
    array(
      'id' => 'home-search-sidebar',
      'name' => __( 'Home Search widgets', 'gos' ),
      'description' => __( 'Shows on home page', 'gos' )
    )
  );

  register_sidebar(
    array(
      'id' => 'home-benefits-sidebar',
      'name' => __( 'Home Benefits widgets', 'gos' ),
      'description' => __( 'Shows on home page', 'gos' )
    )
  );

  register_sidebar(
    array(
      'id' => 'home-sidebar',
      'name' => __( 'Home widgets', 'gos' ),
      'description' => __( 'Shows on home page', 'gos' )
    )
  );

  register_sidebar(
    array(
      'id' => 'footer-after-content-sidebar',
      'name' => __( 'Footer After Content widgets', 'gos' ),
      'description' => __( 'Shows in the site footer', 'gos' )
    )
  );

  register_sidebar(
    array(
      'id' => 'footer-sidebar',
      'name' => __( 'Footer widgets', 'gos' ),
      'description' => __( 'Shows in the site footer', 'gos' )
    )
  );
}

if ( ! isset( $content_width ) ) $content_width = 650;

/**
 * Include editor stylesheets
 * @return void
 */
function gos_editor_style() {
    add_editor_style( 'css/wp-editor-style.css' );
}
add_action( 'init', 'gos_editor_style' );

add_action('wp_enqueue_scripts', 'google_maps_config', 25);
function google_maps_config() {
  if (is_front_page()) {
    wp_dequeue_script('google-maps-builder-gmaps');
    wp_dequeue_script('google-maps-builder-plugin-script');
    wp_dequeue_script('google-maps-builder-maps-icons');
    wp_deregister_script('google-maps-builder-gmaps');
    wp_deregister_script('google-maps-builder-plugin-script');
    wp_deregister_script('google-maps-builder-maps-icons');
  }
}

/******************************************************************************\
  Scripts and Styles
\******************************************************************************/

/**
 * Enqueue gos scripts
 * @return void
 */
function gos_enqueue_scripts() {
  // Presentation Layer
  wp_enqueue_style( 'gos-fonts-raleway', esc_url('//fonts.googleapis.com/css?family=Raleway:400,800,700,500,300,200,600,900'), array(), '0.0.1');
  wp_enqueue_style( 'gos-styles', get_stylesheet_uri(), array(), '1.0' );

  // Dependencies
  // @depends ContactForm7, (super,)
  wp_enqueue_script( 'jquery' );

  // ORM
  wp_enqueue_script( 'breeze-debug', get_template_directory_uri() . '/grunt/bower_components/breezejs/breeze.debug.js', array(), '1.0', true );

  // Default Front End

  // Is this necessary at dev?
  //wp_enqueue_script( 'f7', get_template_directory_uri() . '/grunt/bower_components/framework7/dist/js/framework7.min.js', array(), '1.0', true );
  //wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.dev.js', array('f7'), '1.0', true );

  // Basic DOM Tools
  wp_enqueue_script( 'HTML', get_template_directory_uri() . '/grunt/bower_components/HTML/dist/HTML.min.js', array(), '1.0', true );
  wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/js/hoverintent.js', array(), '1.0', true );

  // ORM
  wp_enqueue_script( 'breeze-debug', get_template_directory_uri() . '/grunt/bower_components/breezejs/breeze.debug.js', array(), '1.0', true );

  // AngularJS
  wp_enqueue_script( 'angular', get_template_directory_uri() . '/grunt/bower_components/angular/angular.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-route', get_template_directory_uri() . '/grunt/bower_components/angular-route/angular-route.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-sanitize', get_template_directory_uri() . '/grunt/bower_components/angular-sanitize/angular-sanitize.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-animate', get_template_directory_uri() . '/grunt/bower_components/angular-animate/angular-animate.js', array(), '1.0', true );

  wp_dequeue_style('membermouse-font-awesome', 110);
  wp_deregister_style('membermouse-font-awesome', 110);

  // Main
  wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.dev.js', array('angular', 'hoverintent', 'HTML'), '1.0', true );

  // CMS Taxonomy
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  // Testing Scenarios
  if (strpos($_SERVER['SERVER_NAME'],'local') !== false) {
    //wp_enqueue_script( '', 'http://localhost:35729/livereload.js', array(), '0.0.1', true);
  }
}
add_action( 'wp_enqueue_scripts', 'gos_enqueue_scripts', 200 );

function login_stylesheet() {
    //echo "<link rel='import' id='Polymer--paper-progress' href='" . get_template_directory_uri() . "/grunt/bower_components/paper-progress/paper-progress.html' />";
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/style-admin.css' );
    wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/js/scripts.min.js', array('jquery'), '1.0', true );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

function admin_stylesheet() {
    //wp_enqueue_style( 'gos-fonts-raleway', esc_url('//fonts.googleapis.com/css?family=Raleway:401,800,700,500,300,200,600,900'), array(), '0.0.1');
    wp_enqueue_style( 'custom-admin', get_template_directory_uri() . '/style-admin.css' );
    //wp_enqueue_script( 'custom-admin', get_template_directory_uri() . '/grunt/dist/require.js', array('jquery'), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'admin_stylesheet' );

add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
  return 'http://' . $_SERVER['HTTP_HOST'];
}

/******************************************************************************\
  Content functions
\******************************************************************************/

/**
 * Displays meta information for a post
 * @return void
 */
function gos_post_meta() {
  if ( get_post_type() == 'post' ) {
    echo sprintf(
      __( 'Posted %s in %s%s by %s. ', 'gos' ),
      get_the_time( get_option( 'date_format' ) ),
      get_the_category_list( ', ' ),
      get_the_tag_list( __( ', <b>Tags</b>: ', 'gos' ), ', ' ),
      get_the_author_link()
    );
  }
  edit_post_link( __( ' (edit)', 'gos' ), '<span class="edit-link">', '</span>' );
}

/**
 * @depends JSON_API
 */

/*
 *function add_make_controller($controllers) {
 *  $controllers[] = 'make';
 *  return $controllers;
 *}
 *add_filter('json_api_controllers', 'add_make_controller');
 */


/**
 * @depends JSON_API
 */

/*
 *function set_create_user_controller_path() {
 *  return "/wp-content/themes/gos/create_user.php";
 *}
 *add_filter('json_api_create_user_controller_path', 'set_create_user_controller_path');
 */

/**
 * Disable Author Index
 *
 * @depends JSON_API
 */
// Disable get_author_index method (e.g., for security reasons)
add_action('json_api-core-get_author_index', 'disable_author_index');
function disable_author_index() {
  $nameConstruct = $table_prefix . '';
  printf('%s', $nameConstruct);
  // Stop execution
  exit;
}

/*
       _       _           _       _ _     _         __  __ _
      | |     | |         | |     (_) |   | |       / _|/ _(_)
  __ _| | ___ | |__   __ _| | ___  _| |___| |_ __ _| |_| |_ _ _ __   __ _
 / _` | |/ _ \| '_ \ / _` | |/ _ \| | / __| __/ _` |  _|  _| | '_ \ / _` |
| (_| | | (_) | |_) | (_| | | (_) | | \__ \ || (_| | | | | | | | | | (_| |
 \__, |_|\___/|_.__/ \__,_|_|\___/|_|_|___/\__\__,_|_| |_| |_|_| |_|\__, |
  __/ |                                                              __/ |
 |___/                                                              |___/
                       _
                      (_)
   ___  ___ _ ____   ___  ___ ___  ___
  / __|/ _ \ '__\ \ / / |/ __/ _ \/ __|
 _\__ \  __/ |   \ V /| | (_|  __/\__ \
(_)___/\___|_|    \_/ |_|\___\___||___/


1. Job (Custom Post Type)
1.1. Jobs
1.1.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)
    Unread (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle/)
    Awaiting Approval ((Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-o/))
    Inactive (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-thin/)
    Expiring Soon (Icon: http://fortawesome.github.io/Font-Awesome/icon/clock-o/)
    Expired (Icon: http://fortawesome.github.io/Font-Awesome/icon/dot-circle-o/)

1.1.2. FK
    Position Title
    Company Name
    Category
    Job Type
    Price
    Expires
    Applications
    Status

1.2. Corollary: Reactive Job Postings
     Correlate discrete numbers of "drag" interface to WP custom taxonomy
     posts "carousel", thus documents are interactive such that other key
     data points are cycled in accordance with WP Query constraints.)
     See http://worrydream.com/Tangle/guide.html.

1.3. Corollary: Leaflet Maps (Custom Post Type)
     Job (Postings) and Employers have GIS data.
     See http://leafletjs.com/.

1.2. Applications (Custom Post Type)

1.2.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    New (Icon: http://fortawesome.github.io/Font-Awesome/icon/bullseye/)
    Accepted (Icon: http://fortawesome.github.io/Font-Awesome/icon/check-circle/)
    Rejected (Icon: http://fortawesome.github.io/Font-Awesome/icon/ban/)

1.2.2. FK
    Applicant Name
    Applicant Email
    Job
    Files
    Posted
    Status

1.3. Employers (Custom Post Type)

1.3.1. FK
    Id
    Company Name
    Company Location
    Representative
    Jobs Posted
    Status

1.4. Candidates (Custom Post Type)

1.4.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)
    Inactive (Icon: http://fortawesome.github.io/Font-Awesome/icon/circle-thin/)

1.4.2. FK
    Name
    Headline
    E-mail
    Phone
    Updated (By Owner)
    Status

1.5. Payments (Stripe, Paypal as Custom Post Types)

1.5.1. FK
    ID
    Payment For
    Created At
    User
    External Id
    To Pay
    Paid
    Message

1.6. Memberships (MemberMouse)

1.6.1.
    Membership ID
    Package
    User
    Started
    Expires
    Status

1.7. Notifications (E-mail as Custom Post Type)

1.7.1.
    All (Icon: http://fortawesome.github.io/Font-Awesome/icon/bars/)
    Daily (Icon: http://fortawesome.github.io/Font-Awesome/icon/calendar-o/)
    Weekly (Icon: http://fortawesome.github.io/Font-Awesome/icon/calendar/)

1.8.2.
    Email
    Created At
    Last Run
    Frequency
    Params

2. Profile (User)

3. Settings

3.1. Configuration

3.1.1. Common Settings

3.1.2. Job Board Options

3.1.3. Resumés Options

3.1.4. SEO & Titles Options

3.1.5. Integrations

  Depends on scope of integration. There's also automation with IFTTT and
  Zapier. Don't get me started on #bitcoin.

    3.1.5.1.     Facebook
    3.1.5.2.     Twitter
    3.1.5.3.     LinkedIn
    3.1.5.4.     Google APIs
    3.1.5.5.     reCAPTCHA
    3.1.5.6.     Careerbuilder.com
    3.1.5.7.     Indeed.com
    3.1.5.8.     Payment Methods
    3.1.5.9.     PayPal
    3.1.5.10.    Stripe (Stripe Market, based on WP-Stripe, but implements
                 Stripe Connect (multi bank account WordPress sites))
    3.1.5.11.    Health Check
    3.1.5.12.    Cache (WP Super Cache)
    3.1.5.13.    Aggregators and RSS Feeds

3.2. Pricing
    Single Job Posting
    Single Resumé Access
    Employer Membership Packages

3.3. Custom Fields (Contact Form 7, a WordPress plugin)
    Add/Edit Job Form
    Apply Online Form
    Advanced Search Form
    Company Form
    Resumés Forms
    My Resumé Form
    Advanced Search Form

3.4. Promotions

3.4.1. FK

    Id
    Title
    Code
    Discount
    Expires At
    Usage
    Is Active (Icon: http://fortawesome.github.io/Font-Awesome/icon/bolt/)

3.5. Categories

3.5.1. FK

    Id
    Title
    Slug
    Total Jobs
    Total Resumés

3.6. Job Types

3.6.1. FK

    Id
    Title
    Slug
    Total Jobs
    Total Resumés

3.7. E-mail Templates

    admins (Icon: http://fortawesome.github.io/Font-Awesome/icon/child/)
    employers (Icon: http://fortawesome.github.io/Font-Awesome/icon/users/)
    candidates (Icon: http://fortawesome.github.io/Font-Awesome/icon/user/)
    other

3.8. Import (CSV)

    schedule
    csv
    json (from WP JSON API / REST :: https://github.com/cliffwoo/rest-api-cheat-sheet/blob/master/REST-API-Cheat-Sheet.md)
    schema.org microdata (arbitrary HTML)

4. Visualulz

4.1. D3 Recursive Interactive Treemap Views of Employer Differentials and

     Narratological Datatrends
     WP Query Contextualizations expressed through “drill-down” treepmaps.
     See https://github.com/mbostock/d3/wiki/Treemap-Layout.

4.2. Candidate Historical Trends over interactive time series graphs

     See http://c3js.org.

5.1. Analytics

    Mixpanel

    GoSquared

    Google Webmaster Tools

6. Tech Stack

   WANGS
       WordPress (Custom Plugin for 1-6; Hide WP Posts, Keep WP Pages, Widgets, Menus)

       AngularJS
       I've already implemented AngularJS: one controller, two services, two
       directives. WordPress Roles are being used to authorize data services,
       and we use AngularJS's internal $http service with Promises to size and
       shape payloads to clients.

       Node

       Grunt ::
           built to
               Asynchronous Module Definition for the Web via RequireJS
               package-based Cordova application, deploy iPhone first

       SASS
           Syntactically Awesome Stylesheets with Organic
           Block-Element-Modifier presentation layer
           See http://krasimir.github.io/organic-css/.

7. Testing

See https://github.com/nerdfiles/skreen.ls/blob/develop/shoot.site.coffee.

"Don't commit a line of code unless and until you have one failing test for its logical feature." But the (kind of test)[1-4] is determined by the [feature scope] which is composed of logical units or [modules of code].

1. Test-driven Development: Restricted Unit Testing (around API Requests and Responses)
1.1. frex: /api/v1/data/endpoint, /api/v2/search?query={{term}}

2. Behavior-driven Development: Behavior Testing (around Specific Interactions)

2.1. frex: Button clicks, Swipes, etc.

3. Integration Testing (around Modular Units of Programmatic Code)
3.1. frex: Search Bar, Navigation, etc.

4. Feature/Subbranch Development: End-to-End Testing (around Use Case Flows)
4.1. frex: Pages for Login Flow, Sidebars for Upload, etc.

5. UX Development: Automated Screen Testing (around Design Goals expressed as Modular Units of Presentation Code)
5.1. frex: Design Borders, Sprites, Icons, Typesetting, etc.

6. Generational Development: Regression Testing based on Client-side Error Analytics (emissions of errors from clients to a "regression API" that listens for generated errors)
6.1. frex: https://airbrake.io/blog/notifier/state-client-side-javascript-error-reporting combined with Screen Tests for Spotting errors

7. Validation:
7.1. Semantics — http://linter.structured-data.org
7.2. Markup and CSS — http://validator.w3.org/unicorn/

8. Code Linting: JSHint for Programmatic Front End syntax checking, use Preprocessors where possible to oversee correct/proper syntax production

9. Device/Browser/Responsive Testing:
9.1 Use third-party services like Sauce Labs or Browser Stack, who offer automation especially with tools like AngularJS's Protractor which can be used [outside of the Angular context]
9.2 Automated: Use Vagrant Cookbooks to build recipes for certain configurations
9.3 Manual Testing: Virtual Environments for various Use Case flows that are of interest to Users, Customers, and Stakeholders

Software Development Life Cycle configuration files should follow:

1. Unit (local, development)
2. Behavior (local, development)
3. Integration (development, staging)
4. Feature (staging, QA)
5. Screen (QA, production)

Environments:

1. Local
2. Development
3. Staging
4. QA
5. Production
*/

/*
 * Conditions for Recruiter
 *
 * Basic MemberMouse implementation involves the creation of Members with Employee Schemas
 */
//add_action('wpcf7_contact_form', 'MM__Create');
  //local.globaloilstaffing.services/api/get_page_index/?post_type=postnction MM__Create() {

  /*
   *  var user = Membership.GetUser(username);
   *  var email = null;
   *
   *  if (user != null)
   *  {
   *      email = user.Email;
   *  }
   */
  return '';
//}

/**
 * Conditional Logic for Employee Signup
 *
 * Basic MemberMouse implementation involves the creation of Members with Employee Schemas
 *
 * @see http://support.membermouse.com/knowledgebase/articles/319064-api-documentation
 *
 * @TODO Enable pass of uploaded file(s) to User Profile API.
 */
add_action('wpcf7_contact_form', 'priv_contact');
function priv_contact () {

  global $current_user;

  if (is_page()) :
    $all_roles = $current_user->roles;
    $show = false;
    foreach ($all_roles as $role) {
      if ( $role == 'employee' || $role == 'administrator' || $role == 'recruiter' ) {
        $show = true;
        ob_start();
        ?>
          <div class="user--role">
            <p>Viewing as an [<?php echo $role; ?>].</p>
          </div>
        <?php
        $logLabel = ob_get_clean();
        echo $logLabel;
      }
    }

    // Check for post_type to prevent from mucking up API call
    if ($show == false) {
      ob_start();
      ?>
      <style>
      .wpcf7 { display: none; }
      </style>
      <?php
      $html = ob_get_clean();
      echo $html;
      //exit;
    } else {
      //@include_once('partials/signup.html.php');
    }
  endif;

}

/*
 * A whole API of junk for interacting with social networks.
 */
/*
 *add_action('init', 'API');
 *class API {
 *  function __construct() {
 *    return [];
 *  }
 *  function priv_contact() {
 *    return priv_contact();
 *  }
 *}
 *
 */
/*
 * @namespace Amiright?*
 */

/*
 * @namespace Amiright?*
 */

/*
 * @namespace Amiright?*
 */

/*
 * @namespace Amiright?*
 */

/*
 * @namespace Amiright?
 */


function SearchFilter($query) {
  global $current_user;
  if ($query->is_search) {
    $query->set( 'posts_per_page', 500 );
    $all_roles = $current_user->roles;
    $show = false;
    foreach ($all_roles as $role) {
      if ( $role == 'administrator' || $role == 'manager' ) {
        $query->set( 'post_type', array(
            'relation' => 'AND',
            'post',
            'employee'
        ) );
      } elseif ( $role == 'recruiter' ) {
        $query->set( 'post_type', 'employee' );
      } else {
        $query->set( 'post_type', 'post' );
      }
    }
  }
  return $query;
}
add_filter( 'pre_get_posts', 'SearchFilter' );


/**
 * Create Application Custom Post Type
 *
 * Based on the given form submission name we will create a Custom Post Type.
 */
function wpcf7_before_action($cfdata) {
  $formtitle = $cfdata->title;
  $formcontents = $cfdata->qualifications;
  require_once $_SERVER["DOCUMENT_ROOT"]."/wp-load.php";

  if ( $formtitle == 'apply-now-form') {
    /*
     *ini_set('display_errors', 1);
     *error_reporting('E_ALL');
     */
    print_r($cfdata);
    $statusTerm = null;

    $terms = get_terms( 'applications' );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
          if ( strpos($term->name, 'status') != false ) {
            $statusTerm = $term->ID;
          }
        }
    }

    $user_ID = get_current_user_id();
    $application_post_title = $_POST['post_title'] || $formtitle;
    $application_post_content = $_POST['post_content'] || $formcontents;

    // Create post object
    $my_post = array(
      'post_title'    => wp_strip_all_tags( $application_post_title ),
      'post_content'  => $application_post_content,
      'post_type'     => 'application',
      'post_status'   => 'publish',
      'post_author'   => $user_ID,
      //'tags_input'    => implode(",","TAG1,TAG2"),
    );

    // Insert the post into the database
    $post_ID = wp_insert_post( $my_post );
    wp_set_object_terms(
      $post_ID,
      array($statusTerm,),
      'application-status'
    );

    echo $post_ID;
  }
}
add_action('wpcf7_before_send_mail', 'wpcf7_before_action',1);

/******************************************************************************\
 *
 * API Controllers
 *
\******************************************************************************/

/**
 * Add User Controller
 */
add_filter('json_api_controllers', 'add_user_controller');
function add_user_controller($controllers) {
  $controllers[] = 'UserController';
  return $controllers;
}

/**
 * Set Get User Controller
 */
function set_get_user_controller_path() {
  $p = '/wp-content/themes/gos/controllers/get_user.php';
  return $p;
}
add_filter('json_api_get_user_controller_path', 'set_get_user_controller_path');


/**
 * Add company Controller
 */
add_filter('json_api_controllers', 'add_company_controller');
function add_company_controller($controllers) {
  $controllers[] = 'CompanyController';
  return $controllers;
}

/**
 * Set get company Controller
 */
function set_get_company_controller_path() {
  $p = '/wp-content/themes/gos/controllers/get_company.php';
  return $p;
}
add_filter('json_api_get_company_controller_path', 'set_get_company_controller_path');


/**
 * Add recruiter Controller
 */
add_filter('json_api_controllers', 'add_recruiter_controller');
function add_recruiter_controller($controllers) {
  $controllers[] = 'RecruiterController';
  return $controllers;
}

/**
 * Set get recruiter Controller
 */
function set_get_recruiter_controller_path() {
  $p = '/wp-content/themes/gos/controllers/get_recruiter.php';
  return $p;
}
add_filter('json_api_get_recruiter_controller_path', 'set_get_recruiter_controller_path');


/**
 * Add employee Controller
 */
add_filter('json_api_controllers', 'add_employee_controller');
function add_employee_controller($controllers) {
  $controllers[] = 'EmployeeController';
  return $controllers;
}

/**
 * Set get employee Controller
 */
function set_get_employee_controller_path() {
  $p = '/wp-content/themes/gos/controllers/get_employee.php';
  return $p;
}
add_filter('json_api_get_employee_controller_path', 'set_get_employee_controller_path');
