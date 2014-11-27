<?php
/**
 * gos template for displaying the footer
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */
?>
        <div class="footer-after-content-widgets">
          <h2>Developing Locations</h2>
          <ul>
            <?php if ( function_exists( 'dynamic_sidebar' ) ) :
              dynamic_sidebar( 'footer-after-content-sidebar' );
            endif; ?>
          </ul>
        </div>

        <ul class="footer-widgets"><?php
          if ( function_exists( 'dynamic_sidebar' ) ) :
            dynamic_sidebar( 'footer-sidebar' );
          endif; ?>
        </ul>

      </div>
    <?php wp_footer(); ?>
  </body>
</html>
