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

    if ( '' != get_the_post_thumbnail() ) :
    ?>
      <?php
      the_post_thumbnail('full');
      ?>
    <?php endif; ?>

    <?php if ( is_singular() || is_tax() || is_front_page() || is_category() || is_archive() || is_search() ) : ?>

      <aside class="module--job-posting-summary">
        <?php
          if ('' != $baseSalary) {
          $baseSalary = get_post_meta(get_the_ID(), 'baseSalary', true);
          $currency = get_post_meta(get_the_ID(), 'currency', true);
          $position_title = get_post_meta(get_the_ID(), 'position_title', true);
          $hiringOrganization = get_post_meta(get_the_ID(), 'hiringOrganization', true);
          $datePosted = get_post_meta(get_the_ID(), 'datePosted', true);
          $workHours = get_post_meta(get_the_ID(), 'workHours', true);
          $skills = get_post_meta(get_the_ID(), 'skills', true);
          $specialCommitments = get_post_meta(get_the_ID(), 'specialCommitments', true);
          $qualifications = get_post_meta(get_the_ID(), 'qualifications', true);
          $educationRequirements = get_post_meta(get_the_ID(), 'educationRequirements', true);
          $experienceRequirements = get_post_meta(get_the_ID(), 'experienceRequirements', true);
          $responsibilities = get_post_meta(get_the_ID(), 'responsibilities', true);
          $benefits = get_post_meta(get_the_ID(), 'benefits', true);
          $incentives = get_post_meta(get_the_ID(), 'incentives', true);
        ?>
        <div class="inner">

          <!--
             -<h2>Job Posting Summary</h2>
             -->

          <dl>

            <dt>
              Base Salary
            </dt>
            <dd>
              <span class="value">
              <?php echo $baseSalary[0]; ?>
              </span>
              <span class="currency">
                <?php echo $currency; ?>
              </span>
            </dd>

            <dt>
              Position Title
            </dt>
            <dd>
              <span class="value">
              <?php echo $position_title; ?>
              </span>
            </dd>

            <dt>
              Hiring Organization
            </dt>
            <dd>
              <span class="value">
              <?php echo $hiringOrganization; ?>
              </span>
            </dd>

            <dt>
              Date Posted
            </dt>
            <dd>
              <span class="value">
              <?php echo $datePosted; ?>
              </span>
            </dd>

            <dt>
              Work Hours
            </dt>
            <dd>
              <span class="value">
              <?php echo $workHours; ?>
              </span>
            </dd>

            <dt>
              Special Commitments
            </dt>
            <dd>
              <span class="value">
              <?php echo $specialCommitments; ?>
              </span>
            </dd>

            <dt>
              Skills
            </dt>
            <dd>
              <span class="value">
              <?php echo $skills; ?>
              </span>
            </dd>

            <dt>
              Responsibilities
            </dt>
            <dd>
              <span class="value">
              <?php echo $responsibilities; ?>
              </span>
            </dd>

            <dt>
              Qualifications
            </dt>
            <dd>
              <span class="value">
              <?php echo $qualifications; ?>
              </span>
            </dd>

            <dt>
              Benefits
            </dt>
            <dd>
              <span class="value">
              <?php echo $benefits; ?>
              </span>
            </dd>

            <dt>
              Experience Requirements
            </dt>
            <dd>
              <span class="value">
              <?php echo $experienceRequirements; ?>
              </span>
            </dd>

            <dt>
              Incentives
            </dt>
            <dd>
              <span class="value">
              <?php echo $incentives; ?>
              </span>
            </dd>


            <dt>
              Education Requirements
            </dt>
            <dd>
              <span class="value">
              <?php echo $educationRequirements; ?>
              </span>
            </dd>

          </dl>
        </div>
      </aside>

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

      <?php } ?>

      <?php the_content(); ?>

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
