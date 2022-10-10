<?php

/**
 * Class TL_Lesson_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

 class TL_Lesson_Post_Type extends TL_Post_Type {
    /**
    * @var null
    */
   protected static $_instance = null;

   /**
    * @var string
    */
   protected $_post_type = TL_LESSON_CPT;

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
    * Register lesson post type.
    */
   public function args_register_post_type() : array {
      $labels           = array(
         'name'               => _x( 'Lessons', 'Post Type General Name', 'tinylms' ),
         'singular_name'      => _x( 'Lesson', 'Post Type Singular Name', 'tinylms' ),
         'menu_name'          => __( 'Lessons', 'tinylms' ),
         'parent_item_colon'  => __( 'Parent Item:', 'tinylms' ),
         'all_items'          => __( 'Lessons', 'tinylms' ),
         'view_item'          => __( 'View Lesson', 'tinylms' ),
         'add_new_item'       => __( 'Add New Lesson', 'tinylms' ),
         'add_new'            => __( 'Add New', 'tinylms' ),
         'edit_item'          => __( 'Edit Lesson', 'tinylms' ),
         'update_item'        => __( 'Update Lesson', 'tinylms' ),
         'search_items'       => __( 'Search Lessons', 'tinylms' ),
         'not_found'          => sprintf( __( 'You haven\'t had any lessons yet. Click <a href="%s">Add new</a> to start', 'tinylms' ), admin_url( 'post-new.php?post_type=tl_lesson' ) ),
         'not_found_in_trash' => __( 'No lesson found in Trash', 'tinylms' ),
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
            'slug'       => 'tl/lessons',
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
         'lesson-options-class',      // Unique ID
         esc_html__( 'Lesson Options', 'lesson-options' ),    // Title
         array(self::instance(), 'options_metabox_html'),   // Callback function
         $this->_post_type,         // Admin page (or post type)
         'side',         // Context
         'default'         // Priority
      ]);
   }

   public function options_metabox_html() {
      ?>
      <p><strong>Related Course (Project)</strong> will be mentioned here ..</p>
      <?php
   }
 }