<?php

class JSON_API_User_Controller {
  public function user_metadata() {
    include ('wp-load.php');
    global $current_user, $post;
    $permalink = get_permalink($post->ID);
    $current_user = wp_get_current_user();
    return array(
      "ID" => $current_user->data->ID,
      "user_email" => $current_user->data->user_email,
      "display_name" => $current_user->data->display_name,
      "permalink" => $permalink
    );
  }
}
?>
