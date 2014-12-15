<?php
/*
Plugin Name: Schema Creator by Raven
Plugin URI: http://schema-creator.org/?utm_source=wp&utm_medium=plugin&utm_campaign=schema
Description: Insert schema.org microdata into posts and pages
Version: 1.050
Author: Raven Internet Marketing Tools
Author URI: http://raventools.com/?utm_source=wp&utm_medium=plugin&utm_campaign=schema
License: GPL v2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


  Resources

  http://schema-creator.org/
  http://foolip.org/microdatajs/live/
  http://www.google.com/webmasters/tools/richsnippets

Actions Hooks:
  raven_sc_register_settings	: runs when the settings are registered
  raven_sc_options_validate	: runs when the settings are saved ( &array )
  raven_sc_metabox			: runs when the metabox is outputted
  raven_sc_save_metabox		: runs when the metabox is saved

Filters:
  raven_sc_default_settings	: gets default settings values
  raven_sc_admin_tooltip		: gets the tooltips for admin pages


*/

if(!defined('SC_BASE'))
  define('SC_BASE', plugin_basename(__FILE__) );

if(!defined('SC_VER'))
  define('SC_VER', '1.050');


if ( !class_exists( "RavenSchema" ) ) :
  class RavenSchema
  {
    /**
     * Constructs a new RavenSchema
     */
    public function __construct() {
      add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );
      add_action( 'admin_menu', array( $this, 'add_pages' )	);
      add_action( 'admin_init', array( $this, 'register_settings' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
      add_action( 'admin_footer',	array( $this, 'schema_form'	) );
      add_action( 'the_posts', array( $this, 'schema_loader' ) );
      add_action( 'do_meta_boxes', array( $this, 'metabox_schema' ), 10, 2 );
      add_action( 'save_post', array( $this, 'save_metabox' ) );
      add_action( 'admin_bar_menu', array( $this, 'schema_test' ), 9999 );

      add_filter( 'raven_sc_admin_tooltip', array( $this, 'get_tooltips' ) );
      add_filter( 'raven_sc_default_settings', array( $this, 'get_default_settings' ) );
      add_filter( 'plugin_action_links', array( $this, 'quick_link' ), 10, 2 );
      add_filter( 'body_class', array( $this, 'body_class' ) );
      add_filter( 'media_buttons', array( $this, 'media_button' ), 31 );
      add_filter( 'the_content', array( $this, 'schema_wrapper' ) );
      add_filter( 'admin_footer_text', array( $this, 'schema_footer' ) );

      add_shortcode( 'schema', array( $this, 'shortcode' ) );

      register_activation_hook( __FILE__, array( $this, 'default_settings' ) );
    }

    /**
     * Load textdomain for international goodness
     */
    public function plugin_textdomain() {
      load_plugin_textdomain( 'schema', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Shows the settings option on the plugins page
     *
     * @param string[] $links current links for plugin
     * @param string $file plugin file links being fetched for
     *
     * @return string[] the links for the plugin
     */
    public function quick_link( $links, $file ) {
      static $this_plugin;

      if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
      }

      // check to make sure we are on the correct plugin
      if ( $file == $this_plugin ) {
        $settings_link	= '<a href="'.menu_page_url( 'schema-creator', 0 ).'">'.__( 'Settings', 'schema' ).'</a>';
        array_unshift( $links, $settings_link );
      }

      return $links;
    }

    /**
     * Adds the `test schema` link to the admin toolbar
     *
     * @param object $wp_admin_bar the current admin bar
     */
    public function schema_test( $wp_admin_bar ) {
      // No link on admin panel
      if ( is_admin() )
        return;

      // only load on singles
      if ( !is_singular() )
        return;

      //get some variables
      global $post;
      $link = get_permalink($post->ID);

      // set args for tab
      global $wp_admin_bar;

      $args = array(
        'parent'	=> 'top-secondary',
        'id'		=> 'schema-test',
        'title' 	=> _x('Test Schema', 'test the schema button title', 'schema'),
        'href'		=> esc_url( __( 'http://www.google.com/webmasters/tools/richsnippets/', 'schema' ) .
                    '?url='.urlencode($link).'&html=' ),
        'meta'		=> array(
          'class'		=> 'schema-test',
          'target'	=> '_blank'
          )
      );

      $wp_admin_bar->add_node($args);
    }

    /**
     * Display metabox for schemas
     *
     * @param string $page current page hook
     * @param string $context current metabox context
     */
    public function metabox_schema( $page, $context ) {
      // only add on side
      if ('side' != $context)
        return;

      // check to see if they have options first
      $schema_options	= get_option('schema_options');

      // they haven't enabled this? THEN YOU LEAVE NOW
      if( empty( $schema_options['body'] ) && empty( $schema_options['post'] ) )
        return;

      // get custom post types
      $args = array(
        'public'   => true,
        '_builtin' => false
      );
      $output		= 'names';
      $operator	= 'and';

      $customs	= get_post_types( $args, $output, $operator );
      $builtin	= array('post' => 'post', 'page' => 'page');

      $types		= $customs !== false ? array_merge( $customs, $builtin ) : $builtin;

      if ( in_array( $page,  $types ) )
        add_meta_box( 'schema-post-box', __( 'Schema Display Options', 'schema' ), array( &$this, 'schema_post_box' ), $page, $context, 'high' );
    }

    /**
     * Display checkboxes for disabling the itemprop and itemscope
     */
    public function schema_post_box( ) {
      global $post;

      // Add downwards compatability
      $disable_body = get_post_meta($post->ID, '_schema_disable_body', true);
      $disable_body = $disable_body === true || $disable_body == 'true' || $disable_body == '1';
      $disable_post = get_post_meta($post->ID, '_schema_disable_post', true);
      $disable_post = $disable_post === true || $disable_post == 'true' || $disable_post == '1';

      // use nonce for security
      wp_nonce_field( SC_BASE, 'schema_nonce' );
      ?>

      <p class="schema-post-option">
        <input type="checkbox" name="schema_disable_body" id="schema_disable_body" value="true" <?php echo checked( $disable_body, true, false ); ?>>
        <label for="schema_disable_body"><?php _e('Disable body itemscopes on this post.', 'schema'); ?></label>
      </p>

      <p class="schema-post-option">
        <input type="checkbox" name="schema_disable_post" id="schema_disable_post" value="true" <?php echo checked( $disable_post, true, false ); ?>>
        <label for="schema_disable_post"><?php _e('Disable content itemscopes on this post.', 'schema'); ?></label>
      </p>
      <?php

      do_action( 'raven_sc_metabox' );
    }

    /**
     * Save the data
     *
     * @param int $post_id the current post id
     * @return int|void the post id or void
     */
    public function save_metabox( $post_id = 0 )
    {
      $post_id = (int)$post_id;
      $post_status = get_post_status( $post_id );

      if ( "auto-draft" == $post_status )
        return $post_id;

      if ( isset( $_POST['schema_nonce'] ) && !wp_verify_nonce( $_POST['schema_nonce'], SC_BASE ) )
        return;

      if ( !current_user_can( 'edit_post', $post_id ) )
        return;

      // OK, we're authenticated: we need to find and save the data
      $db_check = isset( $_POST['schema_disable_body'] );
      $dp_check = isset( $_POST['schema_disable_post'] );

      update_post_meta( $post_id, '_schema_disable_body', $db_check );
      update_post_meta( $post_id, '_schema_disable_post', $dp_check );

      do_action( 'raven_sc_save_metabox' );
    }

    /**
     * Gets the options value for a key
     *
     * @param string $key the option key
     * @return mixed the option value
     */
    function get_option( $key ) {
      $schema_options	= get_option( 'schema_options' );
      return isset($schema_options[$key]) ? $schema_options[$key] : NULL;
    }

    /**
     * Gets the tooltip value for a key
     *
     * @param string $key the tooltip key
     * @return string the tooltip value
     */
    function get_tooltip( $key ) {
      $tooltips = apply_filters( 'raven_sc_admin_tooltip', array() );
      return isset($tooltips[ $key ]) ? htmlentities( $tooltips[ $key ] ) : NULL;
    }

    /**
     * Build settings page
     */
    public function add_pages() {
      add_submenu_page('options-general.php',
         __('Schema Creator', 'schema'),
         __('Schema Creator', 'schema'),
        'manage_options',
        'schema-creator',
        array( $this, 'do_page' )
      );
    }

    /**
     * Register settings
     */
    public function register_settings() {
      register_setting( 'schema_options', 'schema_options', array(&$this, 'options_validate' ) );

      // Information
      add_settings_section('info_section', __('Information', 'schema'), array(&$this, 'options_info_section'), 'schema_options');
      add_settings_field( 'info_version', __('Plugin Version', 'schema'), array(&$this, 'options_info_version'), 'schema_options', 'info_section');

      // CSS output
      add_settings_section( 'display_section', __('Display', 'schema'), array( &$this, 'options_display_section' ), 'schema_options' );
      add_settings_field( 'css', __( 'CSS output', 'schema' ), array( &$this, 'options_display_css' ), 'schema_options', 'display_section' );

      // HTML data applying
      add_settings_section( 'data_section', __('Data', 'schema'), array( &$this, 'options_data_section' ), 'schema_options' );
      add_settings_field( 'body', __( 'Body Tag', 'schema' ), array( &$this, 'options_data_body' ), 'schema_options', 'data_section' );
      add_settings_field( 'post', __( 'Content Wrapper', 'schema' ), array( &$this, 'options_data_post' ), 'schema_options', 'data_section' );

      do_action( 'raven_sc_register_settings' );
    }

    /**
     * Outputs the info section HTML
     */
    function options_info_section() {
    ?>
            <div id='info_section'>
                <p>
        <?php
          printf(
            __( 'By default, the %s plugin by %s includes unique CSS IDs and classes. You can reference the CSS to control the style of the HTML that the Schema Creator plugin outputs.' , 'schema' ).'<br>',

            // the plugin
            '<a target="_blank"
              href="'. esc_url( _x( 'http://schema-creator.org/?utm_source=wp&utm_medium=plugin&utm_campaign=schema', 'plugin uri', 'schema' ) ) .'"
              title="' . esc_attr( _x( 'Schema Creator', 'plugin name', 'schema' ) ) . '">'. _x( 'Schema Creator' , 'plugin name', 'schema') . '</a>',

            // the author
            '<a target="_blank"
              href="' . esc_url( _x( 'http://raventools.com/?utm_source=wp&utm_medium=plugin&utm_campaign=schema', 'author uri', 'schema' ) ) . '"
              title="' . esc_attr( _x('Raven Internet Marketing Tools', 'author', 'schema' ) ) . '"> ' . _x( 'Raven Internet Marketing Tools' , 'author', 'schema') . '</a>'
          );
          _e('The plugin can also automatically include <code>http://schema.org/Blog</code> and <code>http://schema.org/BlogPosting</code> schemas to your pages and posts.', 'schema'); echo "<br>";
          printf(
            __( 'Google also offers a %s to review and test the schemas in your pages and posts.', 'schema'),

            // Rich Snippet Testing Tool link
            '<a target="_blank"
              href="' . esc_url( __( 'http://www.google.com/webmasters/tools/richsnippets/', 'schema' ) ) . '"
              title="' . esc_attr__( 'Rich Snippet Testing tool', 'schema' ) . '"> '. __( 'Rich Snippet Testing tool' , 'schema'). '</a>'
          )
        ?>
                </p>

      </div> <!-- end #info_section -->
            <?php
    }

    /**
     * Outputs the info version field
     */
    function options_info_version()
    {
      echo "<code id='info_version'>".SC_VER."</code>";
    }

    /**
     * Outputs the display section HTML
     */
    function options_display_section() { }

    /**
     * Outputs the display css field
     */
    function options_display_css() {
      $css_hide = $this->get_option( 'css' );
      $css_hide = isset( $css_hide ) && ($css_hide === true || $css_hide == 'true');

      echo '<label for="schema_css">
          <input type="checkbox" id="schema_css" name="schema_options[css]" class="schema_checkbox" value="true" '.checked($css_hide, true, false).'/>
           '.__('Exclude default CSS for schema output', 'schema').'
        </label>
        <span class="ap_tooltip" tooltip="'.$this->get_tooltip( 'default_css' ).'">'._x('(?)', 'tooltip button', 'schema').'</span>
      ';
    }

    /**
     * Outputs the data section HTML
     */
    function options_data_section() { }

    /**
     * Outputs data body field
     */
    function options_data_body() {
      $body_tag = $this->get_option( 'body' );
      $body_tag = isset( $body_tag ) && ($body_tag === true || $body_tag == 'true');

      echo '<label for="schema_body">
          <input type="checkbox" id="schema_body" name="schema_options[body]" class="schema_checkbox" value="true" '.checked($body_tag, true, false).'/>
           '.__('Apply itemprop &amp; itemtype to main body tag', 'schema').'
        </label>
        <span class="ap_tooltip" tooltip="'.$this->get_tooltip( 'body_class' ).'">'._x('(?)', 'tooltip button', 'schema').'</span>
      ';

    }

    /**
     * Outputs data post field
     */
    function options_data_post() {
      $post_tag = $this->get_option( 'post' );
      $post_tag = isset( $post_tag ) && ($post_tag === true || $post_tag == 'true');

      echo '<label for="schema_post">
          <input type="checkbox" id="schema_post" name="schema_options[post]" class="schema_checkbox" value="true" '.checked($post_tag, true, false).'/>
           '.__('Apply itemscope &amp; itemtype to content wrapper', 'schema').'
        </label>
        <span class="ap_tooltip" tooltip="'.$this->get_tooltip( 'post_class' ).'">'._x('(?)', 'tooltip button', 'schema').'</span>
      ';
    }

    /**
     * Validates input
     *
     * @param mixed[] $input the to be processed new values
     * @return mixed the processed new values
     */
    function options_validate( $input ) {
      do_action_ref_array( 'raven_sc_options_validate', array( &$input ) );

      /* example:
       * $input['some_value'] =  wp_filter_nohtml_kses($input['some_value']);
       * $input['maps_zoom'] = min(21, max(0, intval($input['maps_zoom'])));
       * */

      $input['css'] = isset( $input['css'] );
      $input['body'] = isset( $input['body'] );
      $input['post'] = isset( $input['post'] );

      return $input; // return validated input
    }

    /**
     * Set default settings
     */
    public function default_settings( )
    {
      $options_check	= get_option('schema_options');
      $default = apply_filters( 'raven_sc_default_settings', array() );

      if( is_null( $options_check ) ) {

        $options_check = array();

      } else {

        // Upgrade options, not very forward compatible since new options
        // are always false. This is due to the face that old false values
        // where not properly saved.
        foreach( $default as $option => $value )
          $options_check[ $option ] = isset( $options_check[ $option ] ) && $options_check[ $option ] === 'true';

      }

      // Existing options will override defaults
      update_option('schema_options', $options_check + $default );
    }

    /**
     * Gets the default settings
     *
     * @param mixed[] $default current defaults
     * @return mixed[] new defaults
     */
    public function get_default_settings( $default = array() )
    {
      $default['css']	= false;
      $default['body'] = true;
      $default['post'] = true;

      return $default;
    }

    /**
     * Content for pop-up tooltips
     *
     * @param string[] $tooltip current tooltips
     * @return string[] new tooltips
     */
    public function get_tooltips( $tooltip = array() )
    {
      $tooltip = $tooltip + array(
        'default_css'	=> __('Check to remove Schema Creator CSS from the microdata HTML output.', 'schema'),
        'body_class'	=> __('Check to add the <code>http://schema.org/Blog</code> schema itemtype to the BODY element on your pages and posts. Your theme must have the <code>body_class</code> template tag for this to work.', 'schema'),
        'post_class'	=> __('Check to add the <code>http://schema.org/BlogPosting</code> schema itemtype to the content wrapper on your pages and posts.', 'schema'),

        // end tooltip content
      );

      return $tooltip;
    }

    /**
     * Display main options page structure
     */
    public function do_page() {

      if (!current_user_can('manage_options') )
        return;
      ?>
      <div class="wrap">
        <div class="icon32" id="icon-schema"><br></div>
        <h2><?php _e('Schema Creator Settings', 'schema'); ?></h2>
                <div class="schema_options">
                  <form action="options.php" method="post">
            <?php settings_fields( 'schema_options' ); ?>
            <?php do_settings_sections('schema_options'); ?>
                      <p class="submit">
                          <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
                      </p>
                  </form>
        </div> <!-- end .schema_options -->
      </div> <!-- end .wrap -->
    <?php }


    /**
     * load scripts and style for admin settings page
     *
     * @param string $hook the current page hook
     */
    public function admin_scripts( $hook ) {
      // for post editor
      if ( $hook == 'post-new.php' || $hook == 'post.php' ) :
        wp_enqueue_style( 'schema-admin', plugins_url('/lib/css/schema-admin.css', __FILE__), array(), SC_VER, 'all' );

        wp_enqueue_script( 'jquery-ui-core');
        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_enqueue_script( 'jquery-ui-slider');
        wp_enqueue_script( 'jquery-timepicker', plugins_url('/lib/js/jquery.timepicker.js', __FILE__) , array('jquery'), SC_VER, true );
        wp_enqueue_script( 'format-currency', plugins_url('/lib/js/jquery.currency.min.js', __FILE__) , array('jquery'), SC_VER, true );
        wp_enqueue_script( 'schema-form', plugins_url('/lib/js/schema.form.init.js', __FILE__) , array('jquery'), SC_VER, true );
      endif;

      // for admin settings screen
      $current_screen = get_current_screen();
      if ( 'settings_page_schema-creator' == $current_screen->base ) :
        wp_enqueue_style( 'schema-admin', plugins_url('/lib/css/schema-admin.css', __FILE__), array(), SC_VER, 'all' );

        wp_enqueue_script( 'jquery-qtip', plugins_url('/lib/js/jquery.qtip.min.js', __FILE__) , array('jquery'), SC_VER, true );
        wp_enqueue_script( 'schema-admin', plugins_url('/lib/js/schema.admin.init.js', __FILE__) , array('jquery'), SC_VER, true );
      endif;
    }


    /**
     * add attribution link to settings page
     *
     * @param string $text the current footer text
     * @return string the new footer text
     */
    public function schema_footer( $text ) {
      $current_screen = get_current_screen();

      if ( 'settings_page_schema-creator' !== $current_screen->base )
        return $text;

      $text = '<span id="footer-thankyou">' .
        sprintf( __('This plugin brought to you by the fine folks at %s', 'schema'),
          '<a target="_blank"
            href="' . esc_url( _x( 'http://raventools.com/?utm_source=wp&utm_medium=plugin&utm_campaign=schema', 'plugin url', 'schema' ) ).'"
            title="' . esc_attr__( 'Internet Marketing Tools for SEO and Social Media', 'schema' )  . '"> '.
            _x('Raven Internet Marketing Tools', 'author', 'schema') . '
          </a>'
        ) .
      '</span>';

      return $text;
    }

    /**
     * Load body classes.
     *
     * Outputs itemtype and itemscope when body classes are generated.
     *
     * @param string[] $classes current body classes
     * @return string[] new body classes
     */
    public function body_class( $classes ) {

      if (is_search() || is_404() )
        return $classes;

      $schema_options = get_option('schema_options');
      $bodytag = !isset($schema_options['body']) || ($schema_options['body'] === true || $schema_options['body'] == 'true');

      // user disabled the tag. so bail.
      if ( $bodytag === false )
        return $classes;

      // check for single post disable
      global $post;
      if ( empty($post) )
        return $classes;

      $disable_body = get_post_meta($post->ID, '_schema_disable_body', true);
      if ( $disable_body === true || $disable_body == 'true' || $disable_body == '1' )
        return $classes;

      $backtrace = debug_backtrace();
      if ( $backtrace[4]['function'] === 'body_class' ) {
        echo 'itemtype="http://schema.org/Blog" ';
        echo 'itemscope="" ';
      }

      return $classes;
    }

    /**
     * Load front-end CSS if shortcode is present
     *
     * @param object[] $posts the posts to display
     * @return object[] the posts to display
     */
    public function schema_loader( $posts ) {

      // no posts present. nothing more to do here
      if ( empty( $posts ) )
        return $posts;

      // they said they didn't want the CSS. their loss.
      $schema_options = get_option('schema_options');
      if( isset( $schema_options['css'] ) && ( $schema_options['css'] === true || $schema_options['css'] == 'true' ) )
        return $posts;


      // false because we have to search through the posts first
      $found = false;

      // search through each post
      foreach ($posts as $post) {
        $meta_check	= get_post_meta($post->ID, '_raven_schema_load', true);

        // check the post content for the short code
        $content = $post->post_content;
        if ( preg_match('/schema(.*)/', $content) ) {
          // we have found a post with the short code
          $found = true;
          // stop the search
          break;
        }
      }

      if ($found == true )
        wp_enqueue_style( 'schema-style', plugins_url('/lib/css/schema-style.css', __FILE__), array(), SC_VER, 'all' );

      if ( empty($meta_check) && $found == true )
        update_post_meta($post->ID, '_raven_schema_load', true);

      if ( $found == false )
        delete_post_meta($post->ID, '_raven_schema_load');

      return $posts;
    }

    /**
     * wrap content in markup
     *
     * @param string $content the post content
     * @return string the proccesed post content
     */
    public function schema_wrapper( $content ) {

      $schema_options = get_option( 'schema_options' );
      $wrapper = !isset($schema_options['post']) || ( $schema_options['post'] === true || $schema_options['post'] == 'true' );

      // user disabled content wrapper. just return the content as usual
      if ($wrapper === false)
        return $content;

      // check for single post disable
      global $post;
      $disable_post = get_post_meta( $post->ID, '_schema_disable_post', true );
      if( $disable_post === true || $disable_post == 'true' || $disable_post == '1' )
        return $content;

      // updated content filter to wrap the itemscope
      $content = '<div itemscope itemtype="http://schema.org/BlogPosting">'.$content.'</div>';

    // Returns the content.
    return $content;

    }

    /**
     * Build out shortcode with variable array of options
     *
     * @param string $atts shortcode attributes
     * @param string $content shortcode content
     * @return string processed shortcode
     */
    public function shortcode( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'type'				=> '',
        'evtype'			=> '',
        'orgtype'			=> '',
        'name'				=> '',
        'orgname'			=> '',
        'jobtitle'			=> '',
        'url'				=> '',
        'description'		=> '',
        'bday'				=> '',
        'street'			=> '',
        'pobox'				=> '',
        'city'				=> '',
        'state'				=> '',
        'postalcode'		=> '',
        'country'			=> '',
        'email'				=> '',
        'phone'				=> '',
        'fax'				=> '',

        // JobPosting
        'baseSalary'             => '',
        'benefits'               => '',
        'datePosted'             => '',
        'educationRequirements'  => '',
        'employmentType'         => '',
        'experienceRequirements' => '',
        'hiringOrganization'     => '',
        'incentives'             => '',
        'industry'               => '',
        'jobLocation'            => '',
        'occupationalCategory'   => '',
        'qualifications'         => '',
        'responsibilities'       => '',
        'salaryCurrency'         => '',
        'skills'                 => '',
        'specialCommitments'     => '',
        'title'                  => '',
        'workHours'              => '',

        // Product
        'brand'				=> '',
        'manfu'				=> '',
        'model'				=> '',
        'single_rating'		=> '',
        'agg_rating'		=> '',
        'prod_id'			=> '',
        'price'				=> '',
        'condition'			=> '',
        'sdate'				=> '',
        'stime'				=> '',
        'edate'				=> '',
        'duration'			=> '',
        'director'			=> '',
        'producer'			=> '',
        'actor_1'			=> '',
        'author'			=> '',
        'publisher'			=> '',
        'pubdate'			=> '',
        'edition'			=> '',
        'isbn'				=> '',
        'ebook'				=> '',
        'paperback'			=> '',
        'hardcover'			=> '',
        'rev_name'			=> '',
        'rev_body'			=> '',
        'user_review'		=> '',
        'min_review'		=> '',
        'max_review'		=> '',
        'ingrt_1'			=> '',
        'image'				=> '',
        'instructions'		=> '',
        'prephours'			=> '',
        'prepmins'			=> '',
        'cookhours'			=> '',
        'cookmins'			=> '',
        'yield'				=> '',
        'calories'			=> '',
        'fatcount'			=> '',
        'sugarcount'		=> '',
        'saltcount'			=> '',

      ), $atts ) );

      // create array of actor fields
      $actors = array();
      foreach ( $atts as $key => $value ) {
        if ( strpos( $key , 'actor' ) === 0 )
          $actors[] = $value;
      }

      // create array of actor fields
      $ingrts = array();
      foreach ( $atts as $key => $value ) {
        if ( strpos( $key , 'ingrt' ) === 0 )
          $ingrts[] = $value;
      }

      // wrap schema build out
      $sc_build = '<div id="schema_block" class="schema_'.$type.'">';

      // person
      if(isset($type) && $type == 'person') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Person">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($orgname)) {
          $sc_build .= '<div itemscope itemtype="http://schema.org/Organization">';
          $sc_build .= '<span class="schema_orgname" itemprop="name">'.$orgname.'</span>';
          $sc_build .= '</div>';
        }

        if(!empty($jobtitle))
          $sc_build .= '<div class="schema_jobtitle" itemprop="jobtitle">'.$jobtitle.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

        if(!empty($street))
          $sc_build .= '<div class="street" itemprop="streetAddress">'.$street.'</div>';

        if(!empty($pobox))
          $sc_build .= '<div class="pobox">'.__('P.O. Box:', 'schema' ).' <span itemprop="postOfficeBoxNumber">'.$pobox.'</span></div>';

        if(!empty($city) && !empty($state)) :
          $sc_build .= '<div class="city_state">';
          $sc_build .= '<span class="locale" itemprop="addressLocality">'.$city.'</span>,';
          $sc_build .= '<span class="region" itemprop="addressRegion">'.$state.'</span>';
          $sc_build .= '</div>';
        // secondary check if one part of city / state is missing to keep markup consistent
        elseif(empty($state) && !empty($city) ) :
          $sc_build .= '<div class="city_state"><span class="locale" itemprop="addressLocality">'.$city.'</span></div>';
        elseif(empty($city) && !empty($state) ) :
          $sc_build .= '<div class="city_state"><span class="region" itemprop="addressRegion">'.$state.'</span></div>';
        endif;

        if(!empty($postalcode))
          $sc_build .= '<div class="postalcode" itemprop="postalCode">'.$postalcode.'</div>';

        if(!empty($country))
          $sc_build .= '<div class="country" itemprop="addressCountry">'.$country.'</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '</div>';

        if(!empty($email))
          $sc_build .= '<div class="email" itemprop="email">'.antispambot($email).'</div>';

        if(!empty($phone))
          $sc_build .= '<div class="phone" itemprop="telephone">'.__('Phone:', 'schema' ).' '.$phone.'</div>';

        if(!empty($bday))
          $sc_build .= '<div class="bday"><meta itemprop="birthDate" content="'.$bday.'">'._x('DOB:', 'person', 'schema' ).' '.date('m/d/Y', strtotime($bday)).'</div>';

        // close it up
        $sc_build .= '</div>';

      }

      if(isset($type) && $type == 'job_posting') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/JobPosting">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($baseSalary))
          $sc_build .= '<div class="baseSalary"><span class="desc_type">'.__('baseSalary:', 'job_posting', 'schema' ).'</span> <span itemprop="baseSalary">'.$baseSalary.'</span></div>';

        if(!empty($benefits))
          $sc_build .= '<div class="benefits"><span class="desc_type">'.__('benefits:', 'job_posting', 'schema' ).'</span> <span itemprop="benefits">'.$benefits.'</span></div>';

        if(!empty($datePosted))
          $sc_build .= '<div class="datePosted"><span class="desc_type">'.__('datePosted:', 'job_posting', 'schema' ).'</span> <span itemprop="datePosted">'.$datePosted.'</span></div>';

        if(!empty($educationRequirements))
          $sc_build .= '<div class="educationRequirements"><span class="desc_type">'.__('educationRequirements:', 'job_posting', 'schema' ).'</span> <span itemprop="educationRequirements">'.$educationRequirements.'</span></div>';

        if(!empty($employmentType))
          $sc_build .= '<div class="employmentType"><span class="desc_type">'.__('employmentType:', 'job_posting', 'schema' ).'</span> <span itemprop="employmentType">'.$employmentType.'</span></div>';

        if(!empty($experienceRequirements))
          $sc_build .= '<div class="experienceRequirements"><span class="desc_type">'.__('experienceRequirements:', 'job_posting', 'schema' ).'</span> <span itemprop="experienceRequirements">'.$experienceRequirements.'</span></div>';

        if(!empty($hiringOrganization))
          $sc_build .= '<div class="hiringOrganization" itemprop="hiringOrganization" itemscope itemtype="http://schema.org/Organization">
          <span class="desc_type">'._x('Hiring Organization:', 'job_posting', 'schema' ).'</span> <span itemprop="name">'.$hiringOrganization.'</span>
          </div>';

        if(!empty($incentives))
          $sc_build .= '<div class="incentives"><span class="desc_type">'.__('incentives:', 'job_posting', 'schema' ).'</span> <span itemprop="incentives">'.$incentives.'</span></div>';

        if(!empty($industry))
          $sc_build .= '<div class="industry"><span class="desc_type">'.__('Industry:', 'job_posting', 'schema' ).'</span> <span itemprop="industry">'.$industry.'</span></div>';

        if(!empty($jobLocation))
          $sc_build .= '<div class="jobLocation"><span class="desc_type">'.__('jobLocation:', 'job_posting', 'schema' ).'</span> <span itemprop="jobLocation">'.$jobLocation.'</span></div>';

        if(!empty($occupationalCategory))
          $sc_build .= '<div class="occupationalCategory"><span class="desc_type">'.__('occupationalCategory:', 'job_posting', 'schema' ).'</span> <span itemprop="occupationalCategory">'.$occupationalCategory.'</span></div>';

        if(!empty($qualifications))
          $sc_build .= '<div class="qualifications"><span class="desc_type">'.__('qualifications:', 'job_posting', 'schema' ).'</span> <span itemprop="qualifications">'.$qualifications.'</span></div>';

        if(!empty($responsibilities))
          $sc_build .= '<div class="responsibilities"><span class="desc_type">'.__('responsibilities:', 'job_posting', 'schema' ).'</span> <span itemprop="responsibilities">'.$responsibilities.'</span></div>';

        if(!empty($salaryCurrency))
          $sc_build .= '<div class="salaryCurrency"><span class="desc_type">'.__('salaryCurrency:', 'job_posting', 'schema' ).'</span> <span itemprop="salaryCurrency">'.$salaryCurrency.'</span></div>';

        if(!empty($skills))
          $sc_build .= '<div class="skills"><span class="desc_type">'._x('Skills:', 'job_posting', 'schema' ).'</span> <span itemprop="skills">'.$skills.'</span></div>';

        if(!empty($specialCommitments))
          $sc_build .= '<div class="specialCommitments"><span class="desc_type">'.__('specialCommitments:', 'job_posting', 'schema' ).'</span> <span itemprop="specialCommitments">'.$specialCommitments.'</span></div>';

        if(!empty($title))
          $sc_build .= '<div class="title"><span class="desc_type">'.__('title:', 'job_posting', 'schema' ).'</span> <span itemprop="title">'.$title.'</span></div>';

        if(!empty($workHours))
          $sc_build .= '<div class="workHours"><span class="desc_type">'.__('workHours:', 'job_posting', 'schema' ).'</span> <span itemprop="workHours">'.$workHours.'</span></div>';

        // close it up
        $sc_build .= '</div>';

      }

      // product
      if(isset($type) && $type == 'product') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Product">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($brand))
          $sc_build .= '<div class="brand" itemprop="brand" itemscope itemtype="http://schema.org/Organization">
            <span class="desc_type">'._x('Brand:', 'product', 'schema' ).'</span> <span itemprop="name">'.$brand.'</span>
          </div>';

        if(!empty($manfu))
          $sc_build .= '<div class="manufacturer" itemprop="manufacturer" itemscope itemtype="http://schema.org/Organization">
            <span class="desc_type">'._x('Manufacturer:', 'product', 'schema' ).'</span> <span itemprop="name">'.$manfu.'</span>
          </div>';

        if(!empty($model))
          $sc_build .= '<div class="model"><span class="desc_type">'._x('Model:', 'product', 'schema' ).'</span> <span itemprop="model">'.$model.'</span></div>';

        if(!empty($prod_id))
          $sc_build .= '<div class="prod_id"><span class="desc_type">'.__('Product ID:', 'schema' ).'</span> <span itemprop="productID">'.$prod_id.'</span></div>';

        // Have both rating fields
        if(!empty($single_rating) && !empty($agg_rating)) :
          $sc_build .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
          $sc_build .= '<span itemprop="ratingValue">'.$single_rating.'</span> '._x('based on', 'product rating based on', 'schema' ).' ';
          $sc_build .= '<span itemprop="reviewCount">'.$agg_rating.'</span> '._x('reviews', 'product rating based on', 'schema' ).'';
          $sc_build .= '</div>';

        // Secondary check if one part of review is missing to keep markup consistent
        elseif(empty($agg_rating) && !empty($single_rating) ) :
          $sc_build .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="ratingValue"><span class="desc_type">'._x('Review:', 'single product rating', 'schema' ).'</span> '.$single_rating.'</span>
          </div>';

        // Tertiary check if the other part of review is missing
        elseif(empty($single_rating) && !empty($agg_rating) ) :
            $sc_build .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
              <span itemprop="reviewCount">'.$agg_rating.'</span> '._x('total reviews', 'aggregated product rating count', 'schema' ).'
            </div>';
        endif;

        if(!empty($price) && !empty($condition)) {
          $sc_build .= '<div class="offers" itemprop="offers" itemscope itemtype="http://schema.org/Offer">';
          $sc_build .= '<span class="price" itemprop="price">'.$price.'</span>';
          $sc_build .= '<link itemprop="itemCondition" href="http://schema.org/'.$condition.'Condition" /> '.$condition.'';
          $sc_build .= '</div>';
        }

        if(empty($condition) && !empty ($price))
          $sc_build .= '<div class="offers" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span class="price" itemprop="price">'.$price.'</span></div>';


        // close it up
        $sc_build .= '</div>';

      }

      // event
      if(isset($type) && $type == 'event') {

        $default   = (!empty($evtype) ? $evtype : 'Event');
        $sc_build .= '<div itemscope itemtype="http://schema.org/'.$default.'">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($sdate) && !empty($stime) ) :
          $metatime = $sdate.'T'.date('G:i', strtotime($sdate.$stime));
          $sc_build .= '<div><meta itemprop="startDate" content="'.$metatime.'">'._x('Starts:', 'event', 'schema' ).' '.date('m/d/Y', strtotime($sdate)).' '.$stime.'</div>';

        // secondary check for missing start time
        elseif(empty($stime) && !empty($sdate) ) :
          $sc_build .= '<div><meta itemprop="startDate" content="'.$sdate.'">'._x('Starts:', 'event', 'schema' ).' '.date('m/d/Y', strtotime($sdate)).'</div>';
        endif;

        if(!empty($edate))
          $sc_build .= '<div><meta itemprop="endDate" content="'.$edate.':00.000">'._x('Ends:', 'event', 'schema' ).' '.date('m/d/Y', strtotime($edate)).'</div>';

        if(!empty($duration)) {

          $hour_cnv	= date('G', strtotime($duration));
          $mins_cnv	= date('i', strtotime($duration));

          $hours		= (!empty($hour_cnv) && $hour_cnv > 0 ? $hour_cnv.' '.__('hours:', 'schema' ) : '');
          $minutes	= (!empty($mins_cnv) && $mins_cnv > 0 ? ' '.__('and', 'schema' ).' '.$mins_cnv.' '.__('minutes', 'schema' ) : '');

          $sc_build .= '<div><meta itemprop="duration" content="0000-00-00T'.$duration.'">'._x('Duration:', 'event', 'schema' ).' '.$hours.$minutes.'</div>';
        }

        // close actual event portion
        $sc_build .= '</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

        if(!empty($street))
          $sc_build .= '<div class="street" itemprop="streetAddress">'.$street.'</div>';

        if(!empty($pobox))
          $sc_build .= '<div class="pobox">'.__('P.O. Box:', 'schema' ).' <span itemprop="postOfficeBoxNumber">'.$pobox.'</span></div>';

        if(!empty($city) && !empty($state)) {
          $sc_build .= '<div class="city_state">';
          $sc_build .= '<span class="locale" itemprop="addressLocality">'.$city.'</span>,';
          $sc_build .= '<span class="region" itemprop="addressRegion"> '.$state.'</span>';
          $sc_build .= '</div>';
        }

          // secondary check if one part of city / state is missing to keep markup consistent
          if(empty($state) && !empty($city) )
            $sc_build .= '<div class="city_state"><span class="locale" itemprop="addressLocality">'.$city.'</span></div>';

          if(empty($city) && !empty($state) )
            $sc_build .= '<div class="city_state"><span class="region" itemprop="addressRegion">'.$state.'</span></div>';

        if(!empty($postalcode))
          $sc_build .= '<div class="postalcode" itemprop="postalCode">'.$postalcode.'</div>';

        if(!empty($country))
          $sc_build .= '<div class="country" itemprop="addressCountry">'.$country.'</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '</div>';

      }

      // organization
      if(isset($type) && $type == 'organization') {

        $default   = (!empty($orgtype) ? $orgtype : 'Organization');
        $sc_build .= '<div itemscope itemtype="http://schema.org/'.$default.'">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

        if(!empty($street))
          $sc_build .= '<div class="street" itemprop="streetAddress">'.$street.'</div>';

        if(!empty($pobox))
          $sc_build .= '<div class="pobox">'.__('P.O. Box:', 'schema' ).' <span itemprop="postOfficeBoxNumber">'.$pobox.'</span></div>';

        if(!empty($city) && !empty($state)) {
          $sc_build .= '<div class="city_state">';
          $sc_build .= '<span class="locale" itemprop="addressLocality">'.$city.'</span>,';
          $sc_build .= '<span class="region" itemprop="addressRegion"> '.$state.'</span>';
          $sc_build .= '</div>';
        }

          // secondary check if one part of city / state is missing to keep markup consistent
          if(empty($state) && !empty($city) )
            $sc_build .= '<div class="city_state"><span class="locale" itemprop="addressLocality">'.$city.'</span></div>';

          if(empty($city) && !empty($state) )
            $sc_build .= '<div class="city_state"><span class="region" itemprop="addressRegion">'.$state.'</span></div>';

        if(!empty($postalcode))
          $sc_build .= '<div class="postalcode" itemprop="postalCode">'.$postalcode.'</div>';

        if(!empty($country))
          $sc_build .= '<div class="country" itemprop="addressCountry">'.$country.'</div>';

        if(	!empty($street) ||
          !empty($pobox) ||
          !empty($city) ||
          !empty($state) ||
          !empty($postalcode) ||
          !empty($country)
          )
          $sc_build .= '</div>';

        if(!empty($email))
          $sc_build .= '<div class="email" itemprop="email">'.antispambot($email).'</div>';

        if(!empty($phone))
          $sc_build .= '<div class="phone" itemprop="telephone">'.__('Phone:', 'schema' ).' '.$phone.'</div>';

        if(!empty($fax))
          $sc_build .= '<div class="fax" itemprop="faxNumber">'.__('Fax:', 'schema' ).' '.$fax.'</div>';

        // close it up
        $sc_build .= '</div>';

      }

      // movie
      if(isset($type) && $type == 'movie') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Movie">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';


        if(!empty($director))
          $sc_build .= '<div itemprop="director" itemscope itemtype="http://schema.org/Person">'.__('Directed by:', 'schema' ).' <span itemprop="name">'.$director.'</span></div>';

        if(!empty($producer))
          $sc_build .= '<div itemprop="producer" itemscope itemtype="http://schema.org/Person">'.__('Produced by:', 'schema' ).' <span itemprop="name">'.$producer.'</span></div>';

        if(!empty($actor_1)) {
          $sc_build .= '<div>'.__('Starring:', 'schema' ).'';
            foreach ($actors as $actor) {
              $sc_build .= '<div itemprop="actors" itemscope itemtype="http://schema.org/Person">';
              $sc_build .= '<span itemprop="name">'.$actor.'</span>';
              $sc_build .= '</div>';
            }
          $sc_build .= '</div>';
        }


        // close it up
        $sc_build .= '</div>';

      }

      // book
      if(isset($type) && $type == 'book') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Book">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($author))
          $sc_build .= '<div itemprop="author" itemscope itemtype="http://schema.org/Person">'.__('Written by:', 'schema' ).' <span itemprop="name">'.$author.'</span></div>';

        if(!empty($publisher))
          $sc_build .= '<div itemprop="publisher" itemscope itemtype="http://schema.org/Organization">'.__('Published by:', 'schema' ).' <span itemprop="name">'.$publisher.'</span></div>';

        if(!empty($pubdate))
          $sc_build .= '<div class="bday"><meta itemprop="datePublished" content="'.$pubdate.'">'.__('Date Published:', 'schema' ).' '.date('m/d/Y', strtotime($pubdate)).'</div>';

        if(!empty($edition))
          $sc_build .= '<div>'.__('Edition:', 'schema' ).' <span itemprop="bookEdition">'.$edition.'</span></div>';

        if(!empty($isbn))
          $sc_build .= '<div>'.__('ISBN:', 'schema' ).' <span itemprop="isbn">'.$isbn.'</span></div>';

        if( !empty($ebook) || !empty($paperback) || !empty($hardcover) ) {
          $sc_build .= '<div>'.__('Available in:', 'schema' ).' ';

            if(!empty($ebook))
              $sc_build .= '<link itemprop="bookFormat" href="http://schema.org/Ebook">'.__('Ebook', 'schema' ).' ';

            if(!empty($paperback))
              $sc_build .= '<link itemprop="bookFormat" href="http://schema.org/Paperback">'.__('Paperback', 'schema' ).' ';

            if(!empty($hardcover))
              $sc_build .= '<link itemprop="bookFormat" href="http://schema.org/Hardcover">'.__('Hardcover', 'schema' ).' ';

          $sc_build .= '</div>';
        }


        // close it up
        $sc_build .= '</div>';

      }

      // review
      if(isset($type) && $type == 'review') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Review">';

        if(!empty($name) && !empty($url) ) {
          $sc_build .= '<a class="schema_url" target="_blank" itemprop="url" href="'.esc_url($url).'">';
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';
          $sc_build .= '</a>';
        }

        if(!empty($name) && empty($url) )
          $sc_build .= '<div class="schema_name" itemprop="name">'.$name.'</div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($rev_name))
          $sc_build .= '<div class="schema_review_name" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing"><span itemprop="name">'.$rev_name.'</span></div>';

        if(!empty($author))
          $sc_build .= '<div itemprop="author" itemscope itemtype="http://schema.org/Person">'.__('Written by:', 'schema').' <span itemprop="name">'.$author.'</span></div>';

        if(!empty($pubdate))
          $sc_build .= '<div class="pubdate"><meta itemprop="datePublished" content="'.$pubdate.'">'.__('Date Published:', 'schema').' '.date('m/d/Y', strtotime($pubdate)).'</div>';

        if(!empty($rev_body))
          $sc_build .= '<div class="schema_review_body" itemprop="reviewBody">'.esc_textarea($rev_body).'</div>';

        if(!empty($user_review) ) {
          $sc_build .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';

          // minimum review scale
          if(!empty($min_review))
            $sc_build .= '<meta itemprop="worstRating" content="'.$min_review.'">';

          $sc_build .= '<span itemprop="ratingValue">'.$user_review.'</span>';

          // max review scale
          if(!empty($max_review))
            $sc_build .= ' / <span itemprop="bestRating">'.$max_review.'</span> '.__('stars', 'schema' ).'';


          $sc_build .= '</div>';
        }

        // close it up
        $sc_build .= '</div>';

      }

      // recipe
      if(isset($type) && $type == 'recipe') {

        $sc_build .= '<div itemscope itemtype="http://schema.org/Recipe">';

        $imgalt = isset($name) ? $name : __('Recipe Image', 'schema' );

        if(!empty($image)) // put image first so it can lay out better
          $sc_build .= '<img class="schema_image" itemprop="image" src="'.esc_url($image).'" alt="'.$imgalt.'" />';

        if(!empty($name) )
          $sc_build .= '<div class="schema_name header_type" itemprop="name">'.$name.'</div>';

        if(!empty($author) && !empty($pubdate) ) {
          $sc_build .= '<div class="schema_byline">';
          $sc_build .= ''.__('By', 'schema' ).' <span class="schema_strong" itemprop="author">'.$author.'</span>';
          $sc_build .= ' '.__('on', 'schema' ).' <span class="schema_pubdate"><meta itemprop="datePublished" content="'.$pubdate.'">'.date('m/d/Y', strtotime($pubdate)).'</span>';
          $sc_build .= '</div>';
        }

        if(!empty($author) && empty($pubdate) )
          $sc_build .= '<div class="schema_author"> '.__('by', 'schema' ).' <span class="schema_strong" itemprop="author">'.$author.'<span></div>';

        if(!empty($description))
          $sc_build .= '<div class="schema_description" itemprop="description">'.esc_attr($description).'</div>';

        if(!empty($yield) || !empty($prephours) || !empty($prepmins) || !empty($cookhours) || !empty($cookmins) ) {
          $sc_build .= '<div>';

          // PREP: both variables present
          if( !empty($prephours) && !empty($prepmins) ) {

            $prephours_f = sprintf( _nx('%d hour', '%d hours', $prephours, 'recipe time', 'schema'), $prephours);
            $prepmins_f  = sprintf( _nx('%d minute', '%d minutes', $prepmins, 'recipe time', 'schema'), $prepmins);

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'._x('Prep Time:', 'recipe', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="prepTime" content="PT'.$prephours.'H'.$prepmins.'M">';
            $sc_build .= sprintf( _x( '%s, %s', 'x hours, y minutes', 'schema'), $prephours_f, $prepmins_f );
            $sc_build .= '</p>';
          }
          // PREP: no minutes
          elseif( !empty($prephours) && empty($prepmins) ) {

            $prephours_f = sprintf( _nx('%d hour', '%d hours', $prephours, 'recipe time', 'schema'), $prephours );

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'.__('Prep Time:', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="prepTime" content="PT'.$prephours.'H">';
            $sc_build .= $prephours_f;
            $sc_build .= '</p>';
          }
          // PREP: no hours
          elseif( !empty($prepmins) && empty($prephours) ) {

            $prepmins_f = sprintf( _nx('%d minute', '%d minutes', $prepmins, 'recipe time', 'schema'), $prepmins );

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'.__('Prep Time:', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="prepTime" content="PT'.$prepmins.'M">';
            $sc_build .= $prepmins_f;
            $sc_build .= '</p>';
          }

          // COOK: both variables present
          if( !empty($cookhours) && !empty($cookmins) ) {

            $cookhours_f = sprintf( _nx('%d hour', '%d hours', $cookhours, 'recipe time', 'schema'), $cookhours );
            $cookmins_f =  sprintf( _nx('%d minute', '%d minutes', $cookmins, 'recipe time', 'schema'), $cookmins );

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'.__('Cook Time:', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="cookTime" content="PT'.$cookhours.'H'.$cookmins.'M">';
            $sc_build .= sprintf( _x( '%s, %s', 'x hours, y minutes', 'schema'), $cookhours_f, $cookmins_f );
            $sc_build .= '</p>';
          }
          // COOK: no minutes
          elseif( !empty($cookhours) && empty($cookmins) ) {

            $cookhours_f = sprintf( _nx('%d hour', '%d hours', $cookhours, 'recipe time', 'schema'), $cookhours );

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'.__('Cook Time:', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="cookTime" content="PT'.$cookhours.'H">';
            $sc_build .= $cookhours_f;
            $sc_build .= '</p>';
          }
          // COOK: no hours
          elseif( !empty($cookmins) && empty($cookhours) ) {

            $cookmins_f =  sprintf( _nx('%d minute', '%d minutes', $cookmins, 'recipe time', 'schema'), $cookmins );

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'.__('Cook Time:', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="cookTime" content="PT'.$cookmins.'M">';
            $sc_build .= $cookmins_f;
            $sc_build .= '</p>';
          }

          // YIELD
          if( !empty($yield) ) {

            $sc_build .= '<p class="stacked">';
            $sc_build .= '<span class="schema_strong">'._x('Yield:', 'recipe', 'schema' ).'</span> ';
            $sc_build .= '<meta itemprop="recipeYield">';
            $sc_build .= $yield;
            $sc_build .= '</p>';
          }

          $sc_build .= '</div>';
        }

        if( !empty($calories) || !empty($fatcount) || !empty($sugarcount) || !empty($saltcount) ) {
          $sc_build .= '<div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">';
          $sc_build .= '<span class="schema_strong">'.__('Nutrition Information:', 'schema' ).'</span><ul>';

          if(!empty($calories))
            $sc_build .= '<li><span itemprop="calories">'.
              sprintf( _n('%d calorie', '%d calories', $calories, 'schema'), $calories ) .
            '</span></li>';

          if(!empty($fatcount))
            $sc_build .= '<li><span itemprop="fatContent">'.
              sprintf( _n('%d gram of fat', '%d grams of fat', $fatcount, 'schema'), $fatcount ) .
            '</span></li>';

          if(!empty($sugarcount))
            $sc_build .= '<li><span itemprop="sugarContent">'.
              sprintf( _n('%d gram of sugar', '%d grams of sugar', $sugarcount, 'schema'), $sugarcount ) .
            '</span></li>';

          if(!empty($saltcount))
            $sc_build .= '<li><span itemprop="sodiumContent">'.
              sprintf( _n('%d milligram of sodium', '%d milligrams of sodium', $saltcount, 'schema'), $saltcount ) .
            '</span></li>';

          $sc_build .= '</ul></div>';
        }

        if(!empty($ingrt_1)) {
          $sc_build .= '<div><span class="schema_strong">'.__('Ingredients:', 'schema' ).'</span>';
          $sc_build .= '<ul>';
          foreach ($ingrts as $ingrt) {
            $sc_build .= '<li><span itemprop="ingredients">'.$ingrt.'</span></li>';
          }
          $sc_build .= '</ul>';
          $sc_build .= '</div>';
        }

        if(!empty($instructions))
          $sc_build .= '<div class="schema_instructions" itemprop="recipeInstructions">
            <span class="schema_strong">'.__('Instructions:', 'schema' ).'</span><br />'.esc_attr($instructions).'
          </div>';

        // close it up
        $sc_build .= '</div>';

      }


      // close schema wrap
      $sc_build .= '</div>';

      // return entire build array
      return $sc_build;

    }

    /**
     * Add button to top level media row
     */
    public function media_button() {

      // don't show on dashboard (QuickPress)
      $current_screen = get_current_screen();
      if ( 'dashboard' == $current_screen->base )
        return;

      // don't display button for users who don't have access
      if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;

      // do a version check for the new 3.5 UI
      $version	= get_bloginfo('version');

      if ($version < 3.5) {
        // show button for v 3.4 and below
        echo '<a href="#TB_inline?width=650&inlineId=schema_build_form" class="thickbox schema_clear schema_one" id="add_schema" title="' . __('Schema Creator Form') . '">' .
          __('Schema Creator Form', 'schema' ) .
        '</a>';
      } else {
        // display button matching new UI
        $img = '<span class="schema-media-icon"></span> ';
        echo '<a href="#TB_inline?width=650&inlineId=schema_build_form" class="thickbox schema_clear schema_two button" id="add_schema" title="' . esc_attr__( 'Add Schema' ) . '">' .
          $img . __( 'Add Schema', 'schema' ) .
        '</a>';
      }

    }

    public function schema_form() {
      include_once('schema-form.php');
    }
  /// end class
  }

  // Instantiate our class
  $ravenSchema = new RavenSchema();
endif;
