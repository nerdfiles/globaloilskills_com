<?php
/**
 * gos template for displaying Search-Results-Pages
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

get_header(); ?>

  <section class="page-content primary view-main" role="main"><?php

    if ( have_posts() ) : ?>

      <div class="search-title">
        <div class="second-search">
          <!--p>
            <?php
              /*
               *_e( 'Not what you searched for? Try again with some different keywords.', 'gos' );
               */
            ?>
          </p-->
          <?php get_search_form(); ?>
        </div>
      </div><ul class="__unstyled"><?php

      while ( have_posts() ) : the_post();

        ?><li><?php
        get_template_part( 'loop', get_post_format() );
        ?></li><?php

        /*
         *wp_link_pages(
         *  array(
         *    'before'           => '<div class="linked-page-nav"><p>' . sprintf( __( '<em>%s</em> is separated in multiple parts:', 'gos' ), get_the_title() ) . '<br />',
         *    'after'            => '</p></div>',
         *    'next_or_number'   => 'number',
         *    'separator'        => ' ',
         *    'pagelink'         => __( '&raquo; Part %', 'gos' ),
         *  )
         *);
         */

      endwhile;
      ?></ul><?php

    else :

      get_template_part( 'loop', 'empty' );

    endif; ?>

    <div class="pagination">

      <?php get_template_part( 'template-part', 'pagination' ); ?>

    </div>
  </section>
  <script>
    window.setTimeout(function () {
      var s = document.getElementById('s');
      s.setAttribute('placeholder', '<?php printf( __( 'Search Results for: %s', 'gos' ), get_search_query() ); ?>');
    }, 0);
  </script>
<?php get_footer(); ?>
