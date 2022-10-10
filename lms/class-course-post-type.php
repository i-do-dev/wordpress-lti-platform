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
         )
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
         'default'         // Priority
      ]);
   }

   public function options_metabox_html() {
      ?>
      <a href="<?php echo admin_url().'post-new.php?post_type='.TL_LESSON_CPT.'&courseid='. get_the_ID() ?>" class="button "><span class="dashicons dashicons-plus" style="margin-top: 6px"></span>&nbsp;Add New Lessons</a>
      <h3>Lessons</h3>
      <p><a href="#">Lesson 1 (Palylist)</a></p>
      <p><a href="#">Lesson 2 (Palylist)</a></p>
      <p><a href="#">Lesson 3 (Palylist)</a></p>
      <p><a href="#">Lesson 4 (Palylist)</a></p>
      <?php
   }

 }