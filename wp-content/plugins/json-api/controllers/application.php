<?php
class JSON_API_Application_Controller {
  public function create_application() {
    require_once $_SERVER["DOCUMENT_ROOT"]."/wp-load.php";
    /*
     *ini_set('display_errors', 1);
     *error_reporting('E_ALL');
     */

    $user_id = get_current_user_id();
    $post_type = 'application';
    $hostname = 'http://' . $_SERVER['HTTP_HOST'];

    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . '/wp-content/uploads/resumes/user/' . $user_id)) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . 'wp-content/uploads/resumes/user/' . $user_id, 0777, true);
        }
        if (!copy($_FILES['file']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . '/wp-content/uploads/resumes/user/' . $user_id . '/' . $_FILES['file']['name'])) {
          echo 'Error: ' . $_FILES['file']['error'] . '<meta itemtype="http://schema.org/JobPosting" name="demand" itemscope itemprop="demand" />';
        }
    }

    /**
     * Like federated consciousnesses, etc.
     * So here we make an application that represents an employee user's submission to a particular job posting.
     * We're using restful API methods exposed from WP's custom post taxonomy, and it's all very straightforward and simple. Oh, and fuck you.
     */

    $application_submission = array(
      // The post title should be a template model that combines:
      // 1. Username
      // 2. Job posting title
      // 3. Primary category (industry, I guess?)
      'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
      // If a resumé exists, we should recapitulate the resumé content (if parsable) into the post_content, otherwise, we take:
      // the Contact Form 7 Template answers and reproduce them. This should be a cumulative process.
      'post_content'  => wp_strip_all_tags( $_POST['post_content'] ) . "\r\n\r\n" . '<a href="' . $hostname . '/wp-content/uploads/resumes/user/' . $user_id . '/' . $_FILES['file']['name'] . '><?php echo $post_title; ?></a>',
      // The natural post status semantics don't make any fucking sense here. What does it mean to "publish" to an internal ecosystem of documents, you fucking prick?
      'post_status'   => 'publish',
      // The user ID should match the candidate to the recruiter or company that has posted the job posting. The cmpany should be notified via e-mail template post.
      'post_author'   => $user_id,
      'post_type'     => $post_type
      // We should not use tags, but rather categories which are filled or informed by the relavant category list of the initial job posting combined with an intersection of the company categories.
      //'tags_input'    => implode(",","TAG1,TAG2"),//$_POST['tags']
    );

    $post_ID = wp_insert_post( $application_submission );
    return array(
      "application_submission" => $post_ID
    );
  }
}
?>


