1. https://github.com/Botnary/wp-slim-framework
2. http://docs.slimframework.com/#Route-Middleware (wrappers for JSON API requests)
3. Must carry over ``functions.php``. Generally use WP JSON API.

    add_action('slim_mapping',function($slim){
        $slim->get('/{{slim}}/api/get_author_posts/?id=:u', function($user){
          printf("User is %s",$user);
        });
    });

Or:

    add_action('slim_mapping',function($slim){
        $slim->get('/{{slim}}/api/get_author_posts/?slug=:slug', function($user){
          printf("User is %s",$user);
        });
    });

For prosperity:

    class Rest{
        function __construct(){
            add_action('slim_mapping',array(&$this,'slim_mapping');
        }

        function slim_mapping($slim){
            //if needed the class context
            $context = $this;
            $slim->get('/{{slim}}/api/get_posts/:post_type/:page/:count',function($post_type, $page, $count)use($context){
                  $context->printJobPostings($post_type);
            });
            $slim->put('/{{slim}}/api/update_post/:id/:nonce',function($id, $nonce)use($context){
                  $context->updateJobPosting($id, $nonce);
            });
            //.... and so on
        }

        function printJobPosting($job_posting){
            printf("Job Posting is %s",$job_posting);
        }
    }

