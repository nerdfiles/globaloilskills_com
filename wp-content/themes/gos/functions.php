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
      'id' => 'footer-sidebar',
      'name' => __( 'Footer widgets', 'gos' ),
      'description' => __( 'Shows in the sites footer', 'gos' )
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
  //wp_enqueue_style( 'gos-fonts-fontawesome', esc_url('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'), array(), '0.0.1');
  wp_enqueue_style( 'gos-styles', get_stylesheet_uri(), array(), '1.0' );

  // Dependencies
  wp_enqueue_script( 'jquery' );

  // Default Front End

  // Is this necessary at dev?
  //wp_enqueue_script( 'f7', get_template_directory_uri() . '/grunt/bower_components/framework7/dist/js/framework7.min.js', array(), '1.0', true );
  //wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.dev.js', array('f7'), '1.0', true );

  // Basic DOM Tools
  wp_enqueue_script( 'HTML', get_template_directory_uri() . '/grunt/bower_components/HTML/dist/HTML.min.js', array(), '1.0', true );
  wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/js/hoverintent.js', array(), '1.0', true );

  // AngularJS
  wp_enqueue_script( 'angular', get_template_directory_uri() . '/grunt/bower_components/angular/angular.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-route', get_template_directory_uri() . '/grunt/bower_components/angular-route/angular-route.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-sanitize', get_template_directory_uri() . '/grunt/bower_components/angular-sanitize/angular-sanitize.js', array(), '1.0', true );
  wp_enqueue_script( 'angular-animate', get_template_directory_uri() . '/grunt/bower_components/angular-animate/angular-animate.js', array(), '1.0', true );

  // Main
  wp_enqueue_script( 'default-scripts', get_template_directory_uri() . '/js/scripts.dev.js', array('angular', 'hoverintent', 'HTML'), '1.0', true );

  // CMS Taxonomy
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  // Testing Scenarios
  if (strpos($_SERVER['SERVER_NAME'],'local') !== false) {
    wp_enqueue_script( '', 'http://localhost:35729/livereload.js', array(), '0.0.1', true);
  }
}
add_action( 'wp_enqueue_scripts', 'gos_enqueue_scripts' );

function login_stylesheet() {
    //echo "<link rel='import' id='Polymer--paper-progress' href='" . get_template_directory_uri() . "/grunt/bower_components/paper-progress/paper-progress.html' />";
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/js/scripts.min.js', array('jquery'), '1.0', true );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

function admin_stylesheet() {
    wp_enqueue_style( 'gos-fonts-raleway', esc_url('//fonts.googleapis.com/css?family=Raleway:400,800,700,500,300,200,600,900'), array(), '0.0.1');
    wp_enqueue_style( 'custom-admin', get_template_directory_uri() . '/style.css' );
    //wp_enqueue_script( 'custom-admin', get_template_directory_uri() . '/grunt/dist/require.js', array('jquery'), '1.0', true );
}
//add_action( 'admin_enqueue_scripts', 'admin_stylesheet' );

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
  // Stop execution
  exit;
}

/*
 * Conditions for Recruiter
 *
 * Basic MemberMouse implementation involves the creation of Members with Employee Schemas
 */
//add_action('wpcf7_contact_form', 'MM__Create');
function MM__Create() {

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
}

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
    if ($show == false && ! $_GET['post_type']) {
      ob_start();
      ?>
      <style>
      .wpcf7 { display: none; }
      </style>
      <?php
      $html = ob_get_clean();
      echo $html;
      //exit;
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

