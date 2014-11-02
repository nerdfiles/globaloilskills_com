<?php
// http://wp.smashingmagazine.com/2012/11/08/complete-guide-custom-post-types/
// http://codex.wordpress.org/Function_Reference/register_post_type
function custom_post_type_employees() {
  $labels = array(
		'name'               => _x( 'Employees', 'post type general name' ),
		'singular_name'      => _x( 'Employee', 'post type singular name' ),
		'menu_name'          => _x( 'Employees', 'wordpress menu name, default value of name' ),
		'add_new'            => _x( 'Add New', 'employee' ),
		'add_new_item'       => __( 'Add New Employee' ),
		'edit_item'          => __( 'Edit Employee' ),
		'new_item'           => __( 'New Empoyee' ),
		'all_items'          => __( 'All Employees' ),
		'view_item'          => __( 'View Employee Page' ),
		'search_items'       => __( 'Search Products' ),
		'not_found'          => __( 'No employees found' ),
		'not_found_in_trash' => __( 'No employees found in the Trash' ),
		'parent_item_colon'  => ''
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'The current Buiten Insurance and Associates Staff Members',
		'public'        => true,
		'show_ui'		=> true,
		'menu_position' => 5,
		'menu_icon'		=> get_stylesheet_directory_uri() . '/images/users2.png', // Custom Icon Path
		'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes' ),
		'has_archive'   => true,
	);
	register_post_type( 'employee', $args );
}
add_action( 'init', 'custom_post_type_employees' );


// Custom Interaction  Messages
function custom_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['product'] = array(
		0 => '',
		1 => sprintf( __('Employee updated. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Employee updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Employee restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Employee published. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Employee saved.'),
		8 => sprintf( __('Employee submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Employee scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Employee draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'custom_updated_messages' );


// Custom Taxonomies
// http://codex.wordpress.org/Function_Reference/register_taxonomy
function custom_taxonomies_employee() {
	$labels = array(
		'name'              => _x( 'Teams', 'taxonomy general name' ),
		'singular_name'     => _x( 'Team', 'taxonomy singular name' ),
		'menu_name'         => _x( 'Teams', 'wordpress menu name, default value of name' ),
		'search_items'      => __( 'Search Employee Teams' ),
		'all_items'         => __( 'All Employee Teams' ),
		'parent_item'       => __( 'Parent Employee Team' ),
		'parent_item_colon' => __( 'Parent Employee Team:' ),
		'edit_item'         => __( 'Edit Employee Team' ),
		'update_item'       => __( 'Update Employee Team' ),
		'add_new_item'      => __( 'Add New Employee Team' ),
		'new_item_name'     => __( 'New Employee Team' )
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'employee_team', 'employee', $args );
}
add_action( 'init', 'custom_taxonomies_employee', 0 );
?>








<?php

  $query_args = array(
                  'order'             => 'ASC',
                  'orderby'           => 'title',
                  'posts_per_page'    => '-1',
                  'tax_query' => array(
                          array(
                          'taxonomy' => 'employee_team',
                          'field' => 'slug',
                          'terms' => $conditional_cat
                          )
                      )
                  );
  query_posts( $query_args );

        	if ( have_posts() ) {
        		$count = 0;
        		while ( have_posts() ) { the_post(); $count++;

                //custom conditional to assign classes to employee articles, i.e. organization
                if ( $count & 1 ) {
                    $custom_class = 'left';
                    } else {
                        $custom_class = 'right';
                        }

					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */ ?>

                    <article <?php post_class($custom_class); ?>> <?php
                        get_template_part( 'content', 'employee' ); ?>
                    </article><!-- /.post --> <?php

        		} // End WHILE Loop

        	} else {
        ?>
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php } // End IF Statement ?>
?>
