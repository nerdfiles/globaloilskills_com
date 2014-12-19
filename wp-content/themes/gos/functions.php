<?php
/**
 * gos functions file
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

/*
 *ini_set('display_errors', 1);
 *error_reporting('E_ALL');
 */

add_action('register_form','show_role_field');
function show_role_field() {
  if (isset($_GET['role'])) {
?>
  <div class="register-form--role">
    <label for="role[seeker]">
      <span class="inner-label">Seeker</span>
      <input id="role[seeker]" checked="checked" type="radio" value= "seeker" name="role" />
    </label>
    <label for="role[recruiter]">
      <span class="inner-label">Recruiter</span>
      <input id="role[recruiter]" type="radio" value= "recruiter" name="role" />
    </label>
  </div>
<?php
  } else {
?>
  <div class="register-form--role">
    <label for="role[seeker]">
      <span class="inner-label">Seeker</span>
      <input id="role[seeker]" type="radio" value= "seeker" name="role" />
    </label>
    <label for="role[recruiter]">
      <span class="inner-label">Recruiter</span>
      <input id="role[recruiter]" type="radio" value= "recruiter" name="role" />
    </label>
  </div>
<?php
  }
}

//2. Add validation. In this case, we make sure user_type is required.
add_filter( 'registration_errors', 'gos_registration_errors', 10, 3 );
function gos_registration_errors( $errors, $sanitized_user_login, $user_email ) {
    if ( empty( $_POST['role'] ) || ! empty( $_POST['role'] ) && trim( $_POST['role'] ) == '' ) {
        $errors->add( 'role_error', __( '<strong>ERROR</strong>: You must choose your user type.', 'gos' ) );
    }
    return $errors;
}

add_action('user_register', 'register_role');
function register_role($user_id, $password="", $meta=array()) {

   $userdata = array();
   $userdata['ID'] = $user_id;
   $userdata['role'] = $_POST['role'];

   //only allow if user role is my_role

   if (($userdata['role'] == "seeker") or ($userdata['role'] == "recruiter")) {
      wp_update_user($userdata);
   }
}

function register_redirect() {
    global $post;
    if ( ! is_user_logged_in() and is_page('my-account') ) {
        wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-login.php?action=register&role=seeker' );
        exit();
    }
}
add_action( 'template_redirect', 'register_redirect' );

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
  $content = __('Welcome', 'gos');
  echo "<p>$content</p>";
}

add_theme_support( 'post-formats', array( 'image', 'quote', 'status', 'link' ) );
add_theme_support( 'post-thumbnails' );
//set_post_thumbnail_size( 300, 300 );
add_theme_support( 'automatic-feed-links' );

//require 'api_controls.php';

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
  if (is_front_page() || is_tax() || is_singular()) {
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
  wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.dev.js', array('angular', 'HTML', 'jquery', 'hoverintent'), '1.0', true );

  // CMS Taxonomy
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  // Testing Scenarios
  if (strpos($_SERVER['SERVER_NAME'],'local') !== false) {
    //wp_enqueue_script( '', 'http://localhost:35729/livereload.js', array(), '0.0.1', true);
  }
}
add_action( 'wp_enqueue_scripts', 'gos_enqueue_scripts', 200 );

function login_stylesheet() {
    wp_enqueue_script( 'HTML', get_template_directory_uri() . '/grunt/bower_components/HTML/dist/HTML.min.js', array(), '1.0', true );
    wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/js/hoverintent.js', array(), '1.0', true );
    //echo "<link rel='import' id='Polymer--paper-progress' href='" . get_template_directory_uri() . "/grunt/bower_components/paper-progress/paper-progress.html' />";
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/style-admin.css' );
    wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/js/admin.min.js', array('HTML', 'jquery', 'hoverintent'), '1.0', true );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

function admin_stylesheet() {
    wp_enqueue_script( 'HTML', get_template_directory_uri() . '/grunt/bower_components/HTML/dist/HTML.min.js', array(), '1.0', true );
    //wp_enqueue_style( 'gos-fonts-raleway', esc_url('//fonts.googleapis.com/css?family=Raleway:401,800,700,500,300,200,600,900'), array(), '0.0.1');
    wp_enqueue_style( 'custom-admin', get_template_directory_uri() . '/style-admin.css' );
    wp_enqueue_script( 'custom-admin', get_template_directory_uri() . '/js/admin.js', array('jquery', 'HTML'), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'admin_stylesheet' );

add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
  return 'http://' . $_SERVER['HTTP_HOST'];
}

add_action('after_setup_theme', 'gos_theme_language_setup');
function gos_theme_language_setup(){
    load_theme_textdomain('gos', get_template_directory() . '/languages');
}
function add_favicon() {
  $favicon_url = get_stylesheet_directory_uri() . '/images/icons/favicon.png';
  echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');

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
      if ( $role == 'seeker' || $role == 'administrator' || $role == 'recruiter' ) {
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
            'job_posting',
            'seeker'
        ) );
      } elseif ( $role == 'recruiter' ) {
        $query->set( 'post_type', 'seeker' );
      } else {
        $query->set( 'post_type', 'job_posting' );
      }
    }
  }
  return $query;
}
add_filter( 'pre_get_posts', 'SearchFilter' );

