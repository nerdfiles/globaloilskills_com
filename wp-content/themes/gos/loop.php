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
      <div class="module--job-posting-logo">
      <?php
      the_post_thumbnail('full');
      ?>
      </div>
    <?php endif; ?>

    <?php if ( is_singular() || is_tax() || is_front_page() || is_category() || is_archive() || is_search() ) : ?>

        <?php the_content(); ?>

        <?php
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

        <aside class="module--job-posting-summary">

        <div class="inner">

          <dl>
            <?php if (isset($baseSalary) && $baseSalary != ''): ?>
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
            <?php endif; ?>


            <?php if (isset($position_title && $position_title != '')): ?>
            <dt>
              Position Title
            </dt>
            <dd>
              <span class="value">
              <?php echo $position_title; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($hiringOrganization && $hiringOrganization != '')): ?>
            <dt>
              Hiring Organization
            </dt>
            <dd>
              <span class="value">
              <?php echo $hiringOrganization; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($datePosted && $datePosted != '')): ?>
            <dt>
              Date Posted
            </dt>
            <dd>
              <span class="value">
              <?php echo $datePosted; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($workHours && $workHours != '')): ?>
            <dt>
              Work Hours
            </dt>
            <dd>
              <span class="value">
              <?php echo $workHours; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($specialCommitments && $specialCommitments != '')): ?>
            <dt>
              Special Commitments
            </dt>
            <dd>
              <span class="value">
              <?php echo $specialCommitments; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($skills && $skills != '')): ?>
            <dt>
              Skills
            </dt>
            <dd>
              <span class="value">
              <?php echo $skills; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($responsibilities && $responsibilities != '')): ?>
            <dt>
              Responsibilities
            </dt>
            <dd>
              <span class="value">
              <?php echo $responsibilities; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($qualifications && $qualifications != '')): ?>
            <dt>
              Qualifications
            </dt>
            <dd>
              <span class="value">
              <?php echo $qualifications; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($benefits && $benefits != '')): ?>
            <dt>
              Benefits
            </dt>
            <dd>
              <span class="value">
              <?php echo $benefits; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($experienceRequirements && $experienceRequirements != '')): ?>
            <dt>
              Experience Requirements
            </dt>
            <dd>
              <span class="value">
              <?php echo $experienceRequirements; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($incentives && $incentives != '')): ?>
            <dt>
              Incentives
            </dt>
            <dd>
              <span class="value">
              <?php echo $incentives; ?>
              </span>
            </dd>
            <?php endif; ?>


            <?php if (isset($educationRequirements && $educationRequirements != '')): ?>
              <dt>
                Education Requirements
              </dt>
              <dd>
                <span class="value">
                <?php echo $educationRequirements; ?>
                </span>
              </dd>
            <?php endif; ?>

          </dl>
        </div>
      </aside>

      <!--<a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;', 'gos' ); ?></a>-->

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
