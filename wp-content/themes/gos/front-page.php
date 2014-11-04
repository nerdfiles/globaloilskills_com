<?php
/**
 * gos template for displaying the Front-Page
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

get_header(); ?>

  <div class="home-search-widgets">
    <ul>
      <?php if ( function_exists( 'dynamic_sidebar' ) ) :
        dynamic_sidebar( 'home-search-sidebar' );
      endif; ?>
    </ul>
  </div>

  <div class="home-widgets">
    <ul>
      <?php if ( function_exists( 'dynamic_sidebar' ) ) :
        dynamic_sidebar( 'home-sidebar' );
      endif; ?>
    </ul>
  </div>

  <section class="page-content primary" role="main">
    <?php
      if ( have_posts() ) :

        while ( have_posts() ) : the_post();

          get_template_part( 'loop', get_post_format() );

        endwhile;

      else :

        get_template_part( 'loop', 'empty' );

      endif;
    ?>
    <div class="pagination">

      <?php get_template_part( 'template-part', 'pagination' ); ?>

    </div>
  </section>

<?php get_footer(); ?>
