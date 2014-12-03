<?php

/**
* Add User Controller
*/
function add_user_controller($controllers) {
  $controllers[] = 'user';
  return $controllers;
}
add_filter('json_api_controllers', 'add_user_controller');

/**
* Set Get User Controller
*/
function set_user_controller_path() {
  return "../wp-content/themes/gos/controllers/user.php";
}
add_filter('json_api_user_controller_path', 'set_user_controller_path');


/**
* Add company Controller
*/
add_filter('json_api_controllers', 'add_company_controller');
function add_company_controller($controllers) {
  $controllers[] = 'company';
  return $controllers;
}

/**
* Set company Controller
*/
function set_company_controller_path() {
  $p = '../wp-content/themes/gos/controllers/company.php';
  return $p;
}
add_filter('json_api_company_controller_path', 'set_company_controller_path');


/**
* Add recruiter Controller
*/
add_filter('json_api_controllers', 'add_recruiter_controller');
function add_recruiter_controller($controllers) {
  $controllers[] = 'recruiter';
  return $controllers;
}

/**
* Set recruiter Controller
*/
function set_recruiter_controller_path() {
  $p = '../wp-content/themes/gos/controllers/recruiter.php';
  return $p;
}
add_filter('json_api_recruiter_controller_path', 'set_recruiter_controller_path');


/**
* Add employee Controller
*/
add_filter('json_api_controllers', 'add_employee_controller');
function add_employee_controller($controllers) {
  $controllers[] = 'employee';
  return $controllers;
}

/**
* Set employee Controller
*/
function set_employee_controller_path() {
  $p = '../wp-content/themes/gos/controllers/employee.php';
  return $p;
}
add_filter('json_api_employee_controller_path', 'set_employee_controller_path');

/*
*class JSON_API_Make_Controller {
*  public function user() {
*    global $json_api;
*    $email = $json_api->query->email;
*    $fileToUpload = $json_api->query-fileToUpload;
*    if ( email_exists($email) == false ) {
*        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
*        $user_id = wp_create_user( $email, $random_password, $email );
*    } else {
*        $random_password = __('User already exists.  Password inherited.');
*    }
*    return array(
*      "user" => $user_id
*    );
*  }
*}
*/


