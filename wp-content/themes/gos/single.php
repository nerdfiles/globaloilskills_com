<?php
/**
 * gos template for displaying Single-Posts
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */

get_header(); ?>

	<section class="page-content primary view-main" role="main">

		<?php
			if ( have_posts() ) : the_post();

				get_template_part( 'loop', get_post_format() ); ?>

				<!--aside class="post-aside">

					<div class="post-links">
						<?php previous_post_link( '%link', __( '&laquo; Previous post', 'gos' ) ) ?>
						<?php next_post_link( '%link', __( 'Next post &raquo;', 'gos' ) ); ?>
					</div>

					<?php
						if ( comments_open() || get_comments_number() > 0 ) :
							comments_template( '', true );
						endif;
					?>

				</aside--><?php

			else :

				get_template_part( 'loop', 'empty' );

			endif;
		?>

	</section>

<?php get_footer(); ?>
