<?php

class JSON_API_Employee_Controller {
  public function get_user_metadata() {
    $wp_user_metadata = array_keys( get_user_meta( get_current_user_id() ) );
    return array(
      "user_metadata" => $wp_user_metadata
    );
  }
}


