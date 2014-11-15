<?php
/**
 * gos template for displaying the header
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="ie ie-no-support" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="ie ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title><?php
    /*
     * Print the <title> tag based on what is being viewed.
     */
    global $page, $paged;

    wp_title( '|', true, 'right' );

    // Add the blog name.
    bloginfo( 'name' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
      echo " | $site_description";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
      echo ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) );

    ?></title>
    <meta name="viewport" content="width=device-width" />
    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
    <![endif]-->
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <div
      class="site view-main"
      ng-controller="CoreCtrl"
    >

      <header class="site-header">

        <?php if ( '' != get_custom_header()->url ) : ?>
          <img src="<?php header_image(); ?>" class="custom-header" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
        <?php endif; ?>

        <a class="logo" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
          <h1 class="site-name"><?php bloginfo( 'name' ); ?></h1>
          <div class="site-description"><?php bloginfo( 'description' ); ?></div>
        </a>

        <div class="menu"><?php

          $nav_menu = wp_nav_menu(
            array(
              'container' => 'nav',
              'container_class' => 'main-menu',
              'items_wrap' => '<ul class="%2$s">%3$s</ul>',
              'theme_location' => 'main-menu',
              'fallback_cb' => '__return_false',
            )
          ); ?>

        </div>

      </header>
