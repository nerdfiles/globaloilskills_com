<?php
/*
Plugin Name: Job Postings Database
Plugin URI: http://nerdfiles.net
Author: nerdfiles
Author URI: http://nerdfiles.net
Description: A custom post type that adds Applicants and custom taxonomies.
Needs:
Version: 1.0
*/

/*
 * The taxonomy of individuals will involve a correlation of categories such that
 * template language will be used to intersect elements by using WP-Slim-Framework.
 *
 * Users of the application will be expected to maintain the correlated data points
 * such that each entity that is posted will be the results of a form of curated
 * content (List Views, Detail Views).
 */


include('types/application.php');
include('types/applicant.php');
include('types/job-posting.php');
include('types/employee.php');
include('types/company.php');
