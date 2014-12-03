<?php

class JSON_API_GlobalOilUser_Controller {
  public function user_metadata() {
    //$wp_user_metadata = array_keys( get_user_meta( get_current_user_id() ) );
    return array(
      "api" => true
      //"user_metadata" => $wp_user_metadata
    );
  }
}


