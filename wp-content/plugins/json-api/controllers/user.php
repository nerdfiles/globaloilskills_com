<?php

class JSON_API_User_Controller {
  // Perhaps the intersection of WordPress and Django consists in the
  // serialization of ./models/ and ./views/ togethef ./models/ and ./views/
  // together.
  //
  // @note Typically we start here with creative encounters as modules of
  //
  // __init__.py
  //
  // import DjangoJSONEncoder

  public function user_metadata() {
    include ('wp-load.php');
    $current_user = wp_get_current_user();
    return array(
      "ID" => $current_user->data->ID,
      "user_email" => $current_user->data->user_email,
      "display_name" => $current_user->data->display_name
    );
  }
}
?>

