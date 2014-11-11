<?php

class JSON_API_Make_Controller {

  public function user() {

    global $json_api;
    $email = $json_api->query->email;
    $fileToUpload = $json_api->query-fileToUpload;

    if ( email_exists($email) == false ) {

        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

        $user_id = wp_create_user( $email, $random_password, $email );

    } else {

        $random_password = __('User already exists.  Password inherited.');

    }

    return array(
      "user" => $user_id
    );
  }

}

?>
