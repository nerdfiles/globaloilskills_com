<?php

class JSON_API_Application_Controller {
  public function application_metadata() {
    //$wp_user_metadata = array_keys( get_user_meta( get_current_user_id() ) );
    return array(
      "message" => "Hello!"
    );
  }
}
?>

