<?php

/**
 * Class TL_Course_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

 class TL_Course_Post_Type extends TL_Post_Type {
    /**
    * @var null
    */
   protected static $_instance = null;

   /**
    * @var string
    */
   protected $_post_type = TL_COURSE_CPT;

   /**
    * Get Instance
    */
   public static function instance() {
      if (is_null(self::$_instance)) {
         self::$_instance = new self();
      }

      return self::$_instance;
   }

   /**
    * Constructor
    */
   public function __construct() {
      parent::__construct();
   }

   /**
    * Register course post type.
    */
   public function args_register_post_type() : array {

      add_filter( 'single_template', function ( $page_template ) {
         global $post;
         if ( $post->post_type == "tl_course" ) {
            $page_template = dirname( __FILE__ ) . '/templates/course/course.php';
        }
        return $page_template;
      },20, 1);

      $labels           = array(
         'name'               => _x( 'Courses', 'Post Type General Name', 'tinylms' ),
         'singular_name'      => _x( 'Course', 'Post Type Singular Name', 'tinylms' ),
         'menu_name'          => __( 'Courses', 'tinylms' ),
         'parent_item_colon'  => __( 'Parent Item:', 'tinylms' ),
         'all_items'          => __( 'Courses', 'tinylms' ),
         'view_item'          => __( 'View Course', 'tinylms' ),
         'add_new_item'       => __( 'Add New Course', 'tinylms' ),
         'add_new'            => __( 'Add New', 'tinylms' ),
         'edit_item'          => __( 'Edit Course', 'tinylms' ),
         'update_item'        => __( 'Update Course', 'tinylms' ),
         'search_items'       => __( 'Search Courses', 'tinylms' ),
         'not_found'          => sprintf( __( 'You haven\'t had any courses yet. Click <a href="%s">Add new</a> to start', 'tinylms' ), admin_url( 'post-new.php?post_type=tl_course' ) ),
         'not_found_in_trash' => __( 'No course found in Trash', 'tinylms' ),
      );
      
      $args = array(
         'labels'             => $labels,
         'public'             => true,
         'query_var'          => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'has_archive'        => true,
         'show_in_menu'       => 'tiny_lms',
         'show_in_admin_bar'  => true,
         'show_in_nav_menus'  => true,
         'rewrite'            => array(
            'slug'       => 'tl/courses',
            'with_front' => false
         ),
         'show_in_rest'       => true,
         'rest_base'          => 'tl_courses',
      );

      return $args;
   }

   public function add_meta_boxes() {
      $this->options_metabox();
   }

   public function options_metabox() {
      $this->add_meta_box([
         'course-options-class',      // Unique ID
         esc_html__( 'Course Options', 'course-options' ),    // Title
         array(self::instance(), 'options_metabox_html'),   // Callback function
         $this->_post_type,         // Admin page (or post type)
         'side',         // Context
         'default',         // Priority
         'show_in_rest' => true,
      ]);
   }

   function post_meta_request_params( $args, $request )
	{
      $args += array(
			'meta_key'   => $request['meta_key'],
			'meta_value' => $request['meta_value'],
			'meta_query' => $request['meta_query'],
		);
	    return $args;
	}

   public function options_metabox_html($post = null) {
      $args = array(
         'posts_per_page'   => -1,
         'post_type'        => 'tl_lesson',
         'meta_query' => array(
            array(
               'key'   => 'tl_course_id',
               'value' =>  $post->ID
            )
         )
      );
      $result = get_posts( $args );
      ?>
      <a href="<?php echo admin_url().'post-new.php?post_type='.TL_LESSON_CPT.'&courseid='. get_the_ID() ?>" class="button "><span class="dashicons dashicons-plus" style="margin-top: 6px"></span>&nbsp;Add New Lessons</a>
      <h3>Lessons</h3>
      <input type="hidden" name="course_removed_lessons" id="course_removed_lessons">
      <?php 
      foreach($result as $result){
         echo '<p><a href="'.get_permalink($result->ID).'" target="blank">'.$result->post_title.'</a>
          &nbsp;
          <a style="color:inherit;" class="dashicons dashicons-edit course_remove_lesson" href="'. get_edit_post_link($result->ID).'" target="blank"></a>
          &nbsp;
          <span class="dashicons dashicons-trash course_remove_lesson" lesson_id="'.$result->ID.'"></span>
          
          </p>';
      }
   }

   public function save_tl_post($post_id = null)
   {
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_type']) && 'tl_course' == $_POST['post_type']) {  
         $postIds = preg_split('@,@', $_POST['course_removed_lessons'], -1, PREG_SPLIT_NO_EMPTY);
         if($postIds){
            foreach($postIds as $postId){
               wp_trash_post( $postId );
            }
         }

      }
   }

   public function insert_post_api($post, $request)
   {
      if(isset($request['meta']['lti_content_id'])){
         update_post_meta($post->ID,'lti_content_id', $request['meta']['lti_content_id']);
      }
   }
   
}