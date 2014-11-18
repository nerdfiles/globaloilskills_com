<?php
/**
 * gos template for displaying the Front-Page
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

get_header(); ?>

  <!--

  (_)   (_)                     / _____)                      | |
   _______  ___  ____  _____   ( (____  _____ _____  ____ ____| |__
  |  ___  |/ _ \|    \| ___ |   \____ \| ___ (____ |/ ___) ___)  _ \
  | |   | | |_| | | | | ____|   _____) ) ____/ ___ | |  ( (___| | | |
  |_|   |_|\___/|_|_|_|_____)  (______/|_____)_____|_|   \____)_| |_|
    ______ _     _       _
   / _____|_)   | |     | |
  ( (____  _  __| |_____| |__  _____  ____
   \____ \| |/ _  | ___ |  _ \(____ |/ ___)
   _____) ) ( (_| | ____| |_) ) ___ | |
  (______/|_|\____|_____)____/\_____|_|

  -->
  <div class="home-search-widgets">
    <ul>
      <?php if ( function_exists( 'dynamic_sidebar' ) ) :
        dynamic_sidebar( 'home-search-sidebar' );
      endif; ?>
    </ul>
  </div>

  <!--
    (_)   (_)                     / _____|_)   | |     | |
     _______  ___  ____  _____   ( (____  _  __| |_____| |__  _____  ____
    |  ___  |/ _ \|    \| ___ |   \____ \| |/ _  | ___ |  _ \(____ |/ ___)
    | |   | | |_| | | | | ____|   _____) ) ( (_| | ____| |_) ) ___ | |
    |_|   |_|\___/|_|_|_|_____)  (______/|_|\____|_____)____/\_____|_|
  -->

  <div class="home-widgets">
    <h2>Available Jobs</h2>
    <ul>
      <?php if ( function_exists( 'dynamic_sidebar' ) ) :
        dynamic_sidebar( 'home-sidebar' );
      endif; ?>
    </ul>
  </div>

  <!--
    (____  \                     / __|_)  _
     ____)  )_____ ____  _____ _| |__ _ _| |_  ___
    |  __  (| ___ |  _ \| ___ (_   __) (_   _)/___)
    | |__)  ) ____| | | | ____| | |  | | | |_|___ |
    |______/|_____)_| |_|_____) |_|  |_|  \__|___/
  -->

  <div class="home-benefits">
    <ul>
      <?php if ( function_exists( 'dynamic_sidebar' ) ) :
        dynamic_sidebar( 'home-benefits-sidebar' );
      endif; ?>
    </ul>
  </div>

<?php get_footer(); ?>
