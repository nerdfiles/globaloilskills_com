<?php
/*
Plugin Name: Custom Author Base
Plugin URI: http://www.jfarthing.com/wordpress-plugins/custom-author-base
Description: Adds a field to the Permalink Settings page to change the author permalink base.
Version: 1.1.1
Author: Jeff Farthing
Author URI: http://www.jfarthing.com
Textdomain: custom-author-base
*/

/**
 * Initializes the plugin
 *
 * @since 1.0
 */
function cab_init() {
	global $wp_rewrite;

	if ( $author_base = get_option( 'author_base' ) )
		$wp_rewrite->author_base = $author_base;
}
add_action( 'init', 'cab_init' );

/**
 * Adds author base field to permalink settings page
 *
 * @since 1.1
 */
function cab_load_options_permalink() {

	if ( isset( $_POST['author_base'] ) ) {

		$author_base = $_POST['author_base'];

		if ( ! empty( $author_base ) )
			$author_base = preg_replace( '#/+#', '/', '/' . $author_base );

		cab_set_author_base( $author_base );
	}

	add_settings_field(
		'author_base',
		__( 'Author base', 'custom-author-base' ),
		'cab_settings_field',
		'permalink',
		'optional',
		array( 'label_for' => 'author_base' )
	);
}
add_action( 'load-options-permalink.php', 'cab_load_options_permalink' );

/**
 * Displays author base settings field
 *
 * @since 1.1
 */
function cab_settings_field() {
	echo '<input name="author_base" id="author_base" type="text" value="' . esc_attr( get_option( 'author_base' ) ) . '" class="regular-text code" />';
}

/**
 * Set the base for the author permalink
 *
 * @since 1.1
 *
 * @param string $author_base Author permalink structure base
 */
function cab_set_author_base( $author_base ) {
	global $wp_rewrite;

	if ( $author_base != $wp_rewrite->author_base ) {
		update_option( 'author_base', $author_base );
		$wp_rewrite->init();
		if ( ! empty( $author_base ) )
			$wp_rewrite->author_base = $author_base;
	}
}

// Filter the author base
add_filter( 'option_author_base', '_wp_filter_taxonomy_base' );
