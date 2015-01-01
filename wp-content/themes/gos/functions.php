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
        //wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-login.php?action=register&role=seeker' );
        wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-login.php' );
        exit();
    }
    if ( is_user_logged_in() and is_page('my-account') ) {
        wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-admin/profile.php' );
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
    __('Dashboard'),
    __('Posts'),
    __('Media'),
    __('Links'),
    __('Pages'),
    __('Appearance'),
    __('Tools'),
    __('Users'),
    __('Google Maps'),
    __('Tidy Tags'),
    __('Database'),
    __('WP-Optimize'),
    __('Custom Fields'),
    __('Roles'),
    __('Wordfence'),
    //__('Settings'),
    __('Comments'),
    __('Plugins'),
    __('Events'),
    __('Recruiters'),
    __('Applicants'),
    __('Contact'),
    __('FooGallery')
  );
  end ($menu);
  while (prev($menu)) {
    $value = explode(' ',$menu[key($menu)][0]);
    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
  }
}
//add_action('admin_menu', 'rw_remove_menus');

/**
 * Dashboard Customizations for http://globaloilstaffing.services
 */
add_action('wp_dashboard_setup', 'dashboard_widgets');
function dashboard_widgets() {
  global $wp_meta_boxes;
  wp_add_dashboard_widget('custom_help_widget', 'Introduction', 'custom_dashboard_help');
}

function custom_dashboard_help() {
  $content = __('Welcome.', 'gos');
?>
  <?php echo "<p>$content</p>"; ?>
  <?php if (is_admin()) { ?>
  <p>If you are an admin, expect a few visual glitches like this menu. Most users will not see the menu below. We're working on it.</p>
  <!--div id="adminmenuwrap" style="width: 50%;">
    <ul id="adminmenu" role="navigation" style="width: auto; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between">
    <li class="wp-has-submenu wp-not-current-submenu open-if-no-js menu-top menu-icon-post menu-top-first" id="menu-posts">
    <a href="edit.php" class="wp-has-submenu wp-not-current-submenu open-if-no-js menu-top menu-icon-post menu-top-first" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post"><br></div><div class="wp-menu-name">Posts</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Posts</li><li class="wp-first-item"><a href="edit.php" class="wp-first-item">All Posts</a></li><li><a href="post-new.php">Add New</a></li><li><a href="edit-tags.php?taxonomy=category">Categories</a></li><li><a href="edit-tags.php?taxonomy=post_tag">Tags</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tribe_events" id="menu-posts-tribe_events">
    <a href="edit.php?post_type=tribe_events" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tribe_events" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-calendar"><br></div><div class="wp-menu-name">Events</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Events</li><li class="wp-first-item"><a href="edit.php?post_type=tribe_events" class="wp-first-item">Events</a></li><li><a href="post-new.php?post_type=tribe_events">Add New</a></li><li><a href="edit-tags.php?taxonomy=post_tag&amp;post_type=tribe_events">Tags</a></li><li><a href="edit-tags.php?taxonomy=tribe_events_cat&amp;post_type=tribe_events">Event Categories</a></li><li><a href="edit.php?post_type=tribe_venue">Venues</a></li><li><a href="edit.php?post_type=tribe_organizer">Organizers</a></li><li><a href="edit.php?post_type=tribe_events&amp;page=events-importer">Import: CSV</a></li><li><a href="edit.php?post_type=tribe_events&amp;page=tribe-events-calendar">Settings</a></li><li><a href="edit.php?post_type=tribe_events&amp;page=tribe-events-calendar&amp;tab=help">Help</a></li><li><a href="edit.php?post_type=tribe_events&amp;page=tribe-app-shop">Event Add-Ons</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" id="menu-posts-job_posting">
    <a href="edit.php?post_type=job_posting" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post"><br></div><div class="wp-menu-name">Job Board</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Job Board</li><li class="wp-first-item"><a href="edit.php?post_type=job_posting" class="wp-first-item">Job Board</a></li><li><a href="post-new.php?post_type=job_posting">Add Job</a></li><li><a href="edit-tags.php?taxonomy=category&amp;post_type=job_posting">Categories</a></li><li><a href="edit-tags.php?taxonomy=job-location&amp;post_type=job_posting">Job Location</a></li><li><a href="edit-tags.php?taxonomy=job-industry&amp;post_type=job_posting">Industry</a></li><li><a href="edit-tags.php?taxonomy=job-role&amp;post_type=job_posting">Role</a></li><li><a href="edit-tags.php?taxonomy=job-status&amp;post_type=job_posting">Job Status</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" id="menu-posts-application">
    <a href="edit.php?post_type=application" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post"><br></div><div class="wp-menu-name">Onboarding</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Onboarding</li><li class="wp-first-item"><a href="edit.php?post_type=application" class="wp-first-item">Onboarding</a></li><li><a href="post-new.php?post_type=application">Add Application</a></li><li><a href="edit-tags.php?taxonomy=category&amp;post_type=application">Categories</a></li><li><a href="edit-tags.php?taxonomy=application-status&amp;post_type=application">Application Status</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media" id="menu-media">
    <a href="upload.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-media"><br></div><div class="wp-menu-name">Media</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Media</li><li class="wp-first-item"><a href="upload.php" class="wp-first-item">Library</a></li><li><a href="media-new.php">Add New</a></li><li><a href="edit-tags.php?taxonomy=category&amp;post_type=attachment">Categories</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" id="menu-posts-company">
    <a href="edit.php?post_type=company" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post"><br></div><div class="wp-menu-name">Companies</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Companies</li><li class="wp-first-item"><a href="edit.php?post_type=company" class="wp-first-item">Companies</a></li><li><a href="post-new.php?post_type=company">Add Company</a></li><li><a href="edit-tags.php?taxonomy=category&amp;post_type=company">Categories</a></li><li><a href="edit-tags.php?taxonomy=legal-status-codes&amp;post_type=company">Legal Status Codes</a></li><li><a href="edit-tags.php?taxonomy=company-suborganization&amp;post_type=company">Suborganization</a></li><li><a href="edit-tags.php?taxonomy=company-member-of&amp;post_type=company">Membership</a></li><li><a href="edit-tags.php?taxonomy=company-demands&amp;post_type=company">Demands</a></li><li><a href="edit-tags.php?taxonomy=languages&amp;post_type=company">Languages</a></li><li><a href="edit-tags.php?taxonomy=business-structure&amp;post_type=company">Business Structures</a></li><li><a href="edit-tags.php?taxonomy=location-types&amp;post_type=company">Company Location Types</a></li><li><a href="edit-tags.php?taxonomy=company-founded&amp;post_type=company">Founded</a></li><li><a href="edit-tags.php?taxonomy=company-headquarters&amp;post_type=company">Headquarters</a></li><li><a href="edit-tags.php?taxonomy=company-location&amp;post_type=company">Company Location</a></li><li><a href="edit-tags.php?taxonomy=company-ranking&amp;post_type=company">Ranking</a></li><li><a href="edit-tags.php?taxonomy=company-naics&amp;post_type=company">NAICS</a></li><li><a href="edit-tags.php?taxonomy=company-industry&amp;post_type=company">Industry Codes</a></li><li><a href="edit-tags.php?taxonomy=company-departments&amp;post_type=company">Departments</a></li><li><a href="edit-tags.php?taxonomy=isic&amp;post_type=company">ISIC</a></li><li><a href="edit-tags.php?taxonomy=traded-as&amp;post_type=company">Traded As</a></li><li><a href="edit-tags.php?taxonomy=company-type&amp;post_type=company">Company Type</a></li><li><a href="edit-tags.php?taxonomy=company-status&amp;post_type=company">Company Status</a></li><li><a href="edit-tags.php?taxonomy=company-products&amp;post_type=company">Company Products</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" id="menu-posts-google_maps">
    <a href="edit.php?post_type=google_maps" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-post" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-post"><br></div><div class="wp-menu-name">Google Maps</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Google Maps</li><li class="wp-first-item"><a href="edit.php?post_type=google_maps" class="wp-first-item">All Maps</a></li><li><a href="post-new.php?post_type=google_maps">Add New</a></li><li><a href="edit.php?post_type=google_maps&amp;page=gmb_settings">Settings</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page" id="menu-pages">
    <a href="edit.php?post_type=page" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-page"><br></div><div class="wp-menu-name">Pages</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Pages</li><li class="wp-first-item"><a href="edit.php?post_type=page" class="wp-first-item">All Pages</a></li><li><a href="post-new.php?post_type=page">Add New</a></li></ul></li>
    <li class="wp-not-current-submenu menu-top menu-icon-comments" id="menu-comments">
    <a href="edit-comments.php" class="wp-not-current-submenu menu-top menu-icon-comments"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-comments"><br></div><div class="wp-menu-name">Comments <span class="awaiting-mod count-0"><span class="pending-count">0</span></span></div></a></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-xdmsg" id="menu-posts-xdmsg">
    <a href="edit.php?post_type=xdmsg" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-xdmsg" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before"><img src="http://local.globaloilskills.com/wp-content/plugins/xili-dictionary/images/XD-logo-16.png" alt=""></div><div class="wp-menu-name">xili-dictionary©</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">xili-dictionary©</li><li class="wp-first-item"><a href="edit.php?post_type=xdmsg" class="wp-first-item">Msg list</a></li><li><a href="post-new.php?post_type=xdmsg">New msgid</a></li><li><a href="edit-tags.php?taxonomy=origin&amp;post_type=xdmsg">Origin</a></li><li><a href="edit-tags.php?taxonomy=writer&amp;post_type=xdmsg">Writer</a></li><li><a href="edit.php?post_type=xdmsg&amp;page=dictionary_page">Tools, Files po mo</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_wpcf7" id="toplevel_page_wpcf7"><a href="admin.php?page=wpcf7" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_wpcf7" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-generic"><br></div><div class="wp-menu-name">Contact</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Contact</li><li class="wp-first-item"><a href="admin.php?page=wpcf7" class="wp-first-item">Contact Forms</a></li><li><a href="admin.php?page=wpcf7-new">Add New</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_xili-tidy-tags" id="toplevel_page_xili-tidy-tags"><a href="admin.php?page=xili_tidy_tags_settings" class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_xili-tidy-tags" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before"><img src="http://local.globaloilskills.com/wp-content/plugins/xili-tidy-tags/images/xilitidy-logo-16.png" alt=""></div><div class="wp-menu-name">Tidy Tags</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Tidy Tags</li><li class="wp-first-item"><a href="admin.php?page=xili_tidy_tags_settings" class="wp-first-item">Tidy Tags settings</a></li><li><a href="admin.php?page=xili_tidy_tags_assign">Tidy Tags assign</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-foogallery menu-top-last" id="menu-posts-foogallery">
    <a href="edit.php?post_type=foogallery" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-foogallery menu-top-last" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-format-gallery"><br></div><div class="wp-menu-name">FooGallery</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">FooGallery</li><li class="wp-first-item"><a href="edit.php?post_type=foogallery" class="wp-first-item">Galleries</a></li><li><a href="post-new.php?post_type=foogallery">Add Gallery</a></li><li><a href="edit.php?post_type=foogallery&amp;page=foogallery-settings">Settings</a></li><li><a href="edit.php?post_type=foogallery&amp;page=foogallery-extensions">Extensions</a></li><li><a href="edit.php?post_type=foogallery&amp;page=foogallery-help">Help</a></li></ul></li>
    <li class="wp-not-current-submenu wp-menu-separator"><div class="separator"></div></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-appearance menu-top-first" id="menu-appearance">
    <a href="themes.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-appearance menu-top-first" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-appearance"><br></div><div class="wp-menu-name">Appearance</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Appearance</li><li class="wp-first-item"><a href="themes.php" class="wp-first-item">Themes</a></li><li class="hide-if-no-customize"><a href="customize.php?return=%2Fwp-admin%2Findex.php" class="hide-if-no-customize">Customize</a></li><li><a href="widgets.php">Widgets</a></li><li><a href="nav-menus.php">Menus</a></li><li><a href="theme-editor.php">Editor</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-plugins" id="menu-plugins">
    <a href="plugins.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-plugins" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-plugins"><br></div><div class="wp-menu-name">Plugins <span class="update-plugins count-14"><span class="plugin-count">14</span></span></div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Plugins <span class="update-plugins count-14"><span class="plugin-count">14</span></span></li><li class="wp-first-item"><a href="plugins.php" class="wp-first-item">Installed Plugins</a></li><li><a href="plugin-install.php">Add New</a></li><li><a href="plugin-editor.php">Editor</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_wpfront-user-role-editor-all-roles" id="toplevel_page_wpfront-user-role-editor-all-roles"><a href="admin.php?page=wpfront-user-role-editor-all-roles" class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_wpfront-user-role-editor-all-roles" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before"><img src="http://local.globaloilskills.com/wp-content/plugins/wpfront-user-role-editor/images/roles_menu.png" alt=""></div><div class="wp-menu-name">Roles</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Roles</li><li class="wp-first-item"><a href="admin.php?page=wpfront-user-role-editor-all-roles" class="wp-first-item">All Roles</a></li><li><a href="admin.php?page=wpfront-user-role-editor-add-new">Add New</a></li><li><a href="admin.php?page=wpfront-user-role-editor-restore">Restore</a></li><li><a href="admin.php?page=wpfront-user-role-editor">Settings</a></li><li><a href="admin.php?page=wpfront-user-role-editor-go-pro"><span class="wpfront-go-pro">Go Pro</span></a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" id="menu-users">
    <a href="users.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-users"><br></div><div class="wp-menu-name">Users</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Users</li><li class="wp-first-item"><a href="users.php" class="wp-first-item">All Users</a></li><li><a href="user-new.php">Add New</a></li><li><a href="profile.php">Your Profile</a></li><li><a href="users.php?page=Author_category_panel">Author category</a></li><li><a href="users.php?page=users_extended">Users Extended</a></li><li><a href="users.php?page=wpfront-user-role-editor-assign-roles">Assign / Migrate</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tools" id="menu-tools">
    <a href="tools.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tools" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-tools"><br></div><div class="wp-menu-name">Tools</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Tools</li><li class="wp-first-item"><a href="tools.php" class="wp-first-item">Available Tools</a></li><li><a href="import.php">Import</a></li><li><a href="export.php">Export</a></li><li><a href="tools.php?page=redirection.php">Redirection</a></li><li><a href="tools.php?page=widget-importer-exporter">Widget Importer &amp; Exporter</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings" id="menu-settings">
    <a href="options-general.php" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-settings"><br></div><div class="wp-menu-name">Settings</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Settings</li><li class="wp-first-item"><a href="options-general.php" class="wp-first-item">General</a></li><li><a href="options-writing.php">Writing</a></li><li><a href="options-reading.php">Reading</a></li><li><a href="options-discussion.php">Discussion</a></li><li><a href="options-media.php">Media</a></li><li><a href="options-permalink.php">Permalinks</a></li><li><a href="options-general.php?page=user_extra_fields">Cimy User Extra Fields</a></li><li><a href="options-general.php?page=custom-header-images/custom-header-images.php">Header Images</a></li><li><a href="options-general.php?page=schema-creator">Schema Creator</a></li><li><a href="options-general.php?page=wp-mail-smtp/wp_mail_smtp.php">Email</a></li><li><a href="options-general.php?page=zigwidgetclass-options">ZigWidgetClass</a></li><li><a href="options-general.php?page=language_page">Languages ©xili</a></li><li><a href="options-general.php?page=json-api">JSON API</a></li><li><a href="options-general.php?page=extend_search">Search Everything</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_edit?post_type=acf menu-top-last" id="toplevel_page_edit-post_type-acf">
    <a href="edit.php?post_type=acf" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_edit?post_type=acf menu-top-last" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-generic"><br></div><div class="wp-menu-name">Custom Fields</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Custom Fields</li><li class="wp-first-item"><a href="edit.php?post_type=acf" class="wp-first-item">Custom Fields</a></li><li><a href="edit.php?post_type=acf&amp;page=acf-export">Export</a></li><li><a href="edit.php?post_type=acf&amp;page=acf-addons">Add-ons</a></li></ul></li>
    <li class="wp-not-current-submenu wp-menu-separator"><div class="separator"></div></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_CF7DBPluginSubmissions menu-top-first" id="toplevel_page_CF7DBPluginSubmissions"><a href="admin.php?page=CF7DBPluginSubmissions" class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-generic toplevel_page_CF7DBPluginSubmissions menu-top-first" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-admin-generic"><br></div><div class="wp-menu-name">Contact Form DB</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Contact Form DB</li><li class="wp-first-item"><a href="admin.php?page=CF7DBPluginSubmissions" class="wp-first-item">Contact Form DB</a></li><li><a href="admin.php?page=CF7DBPluginShortCodeBuilder">Short Code</a></li><li><a href="admin.php?page=CF7DBPluginSettings">Options</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_Wordfence" id="toplevel_page_Wordfence"><a href="admin.php?page=Wordfence" class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_Wordfence" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before"><img src="http://local.globaloilskills.com/wp-content/plugins/wordfence/images/wordfence-logo-16x16.png" alt=""></div><div class="wp-menu-name">Wordfence</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Wordfence</li><li class="wp-first-item"><a href="admin.php?page=Wordfence" class="wp-first-item">Scan</a></li><li><a href="admin.php?page=WordfenceActivity">Live Traffic</a></li><li><a href="admin.php?page=WordfenceSitePerf">Performance Setup</a></li><li><a href="admin.php?page=WordfenceBlockedIPs">Blocked IPs</a></li><li><a href="admin.php?page=WordfenceTwoFactor">Cellphone Sign-in</a></li><li><a href="admin.php?page=WordfenceCountryBlocking">Country Blocking</a></li><li><a href="admin.php?page=WordfenceScanSchedule">Scan Schedule</a></li><li><a href="admin.php?page=WordfenceWhois">Whois Lookup</a></li><li><a href="admin.php?page=WordfenceRangeBlocking">Advanced Blocking</a></li><li><a href="admin.php?page=WordfenceSecOpt">Options</a></li></ul></li>
    <li class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_wp-dbmanager/database-manager" id="toplevel_page_wp-dbmanager-database-manager"><a href="admin.php?page=wp-dbmanager/database-manager.php" class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_wp-dbmanager/database-manager" aria-haspopup="true"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-archive"><br></div><div class="wp-menu-name">Database</div></a>
    <ul class="wp-submenu wp-submenu-wrap"><li class="wp-submenu-head">Database</li><li class="wp-first-item"><a href="admin.php?page=wp-dbmanager/database-manager.php" class="wp-first-item">Database</a></li><li><a href="admin.php?page=wp-dbmanager/database-backup.php">Backup DB</a></li><li><a href="admin.php?page=wp-dbmanager/database-manage.php">Manage Backup DB</a></li><li><a href="admin.php?page=wp-dbmanager/database-optimize.php">Optimize DB</a></li><li><a href="admin.php?page=wp-dbmanager/database-repair.php">Repair DB</a></li><li><a href="admin.php?page=wp-dbmanager/database-empty.php">Empty/Drop Tables</a></li><li><a href="admin.php?page=wp-dbmanager/database-run.php">Run SQL Query</a></li><li><a href="admin.php?page=wp-dbmanager/wp-dbmanager.php">DB Options</a></li></ul></li>
    <li class="wp-not-current-submenu menu-top toplevel_page_WP-Optimize menu-top-last" id="toplevel_page_WP-Optimize">
    <a href="admin.php?page=WP-Optimize" class="wp-not-current-submenu menu-top toplevel_page_WP-Optimize menu-top-last"><div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before"><img src="http://local.globaloilskills.com/wp-content/plugins/wp-optimize/wpo.png" alt=""></div><div class="wp-menu-name">WP-Optimize</div></a></li></ul>
  </div></ul-->
  <?php } ?>
<?php
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

function SearchFilter($query) {
  if ($query->is_search) {
    global $current_user;
    $query->set( 'posts_per_page', 50 );
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
        $query->set( 'post_type', array('seeker') );
      } else {
        $query->set( 'post_type', array('job_posting') );
      }
    }
  }
  if (!$query->is_admin && $query->is_search) {
    $query->set( 'posts_per_page', 50 );
    $query->set( 'post_type', array('job_posting') );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'SearchFilter' );

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
  //return '';
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
