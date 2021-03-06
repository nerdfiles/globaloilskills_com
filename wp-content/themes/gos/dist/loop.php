<?php
/**
 * gos template for displaying the standard Loop
 *
 * @package WordPress
 * @subpackage gos
 * @since gos 1.0
 */
?>

<article
  id="post-<?php the_ID(); ?>"
  <?php
  /*
   *itemscope
   *itemtype="https://schema.org/employee"
   */
  ?>
  <?php post_class(); ?>
>

  <h1 class="post-title"><?php

    if ( is_singular() ) :
      the_title();
    else : ?>

      <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php
        the_title(); ?>
      </a><?php

    endif; ?>

  </h1>

  <div class="post-meta"><?php
    gos_post_meta(); ?>
  </div>

  <div class="post-content"><?php

    if ( '' != get_the_post_thumbnail() ) : ?>
      <?php the_post_thumbnail(); ?><?php
    endif; ?>

    <?php if ( is_front_page() || is_category() || is_archive() || is_search() ) : ?>

      <?php the_excerpt(); ?>

      <aside class="module--employee--financial-summary">
        <?php
          $salary = get_post_meta(get_the_ID(), 'salary', true);
          $currency = get_post_meta(get_the_ID(), 'currency', true);
          if ( '' != $salary ) {
        ?>
          <div class="inner">
            <h2>Employee Financial Summary</h2>
            <dl>
              <dt>
                Salary
              </dt>
              <dd>
              <span class="value">
              <?php echo $salary; ?>
              </span>
              <span class="currency">
                <?php echo $currency; ?>
              </span>
              </dd>
            </dl>
          </div>
        <?php } ?>
      </aside>

      <a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;', 'gos' ); ?></a>

    <?php else : ?>

      <?php the_content( __( 'Continue reading &raquo', 'gos' ) ); ?>

    <?php endif; ?>

    <?php
      wp_link_pages(
        array(
          'before'           => '<div class="linked-page-nav"><p>'. __( 'This article has more parts: ', 'gos' ),
          'after'            => '</p></div>',
          'next_or_number'   => 'number',
          'separator'        => ' ',
          'pagelink'         => __( '&lt;%&gt;', 'gos' ),
        )
      );
    ?>

  </div>

</article>
