<?php

/**
 * Class TL_School_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

class TL_School_Post_Type extends TL_Post_Type
{
   /**
    * @var null
    */
   protected static $_instance = null;

   /**
    * @var string
    */
   protected $_post_type = TL_SCHOOL_CPT;

   /**
    * Get Instance
    */
   public static function instance()
   {
      if (is_null(self::$_instance)) {
         self::$_instance = new self();
      }

      return self::$_instance;
   }

   /**
    * Constructor
    */
   public function __construct()
   {
      parent::__construct();
   }

   /**
    * Register course post type.
    */
   public function args_register_post_type(): array
   {

      // $located = locate_template('single-tl_trek.php');
      // if (empty($located)) {
      //    add_filter('single_template', function ($page_template, $type) {
      //       global $post;
      //       if ($post->post_type == TL_TREK_CPT) {
      //          $page_template = dirname(__FILE__) . '/templates/trek/single-tl_trek.php';
      //       }
      //       return $page_template;
      //    }, 20, 2);
      // }

 

      $labels           =     array(
         'name'               => _x('LXP School', 'Post Type General Name', 'tinylms'),
         'singular_name'      => _x('LXP School', 'Post Type Singular Name', 'tinylms'),
         'menu_name'          => __('LXP Schools', 'tinylms'),
         'parent_item_colon'  => __('Parent Item:', 'tinylms'),
         'all_items'          => __('LXP Schools', 'tinylms'),
         'view_item'          => __('View LXP School', 'tinylms'),
         'add_new_item'       => __('Add New LXP School', 'tinylms'),
         'add_new'            => __('Add New', 'tinylms'),
         'edit_item'          => __('Edit LXP School', 'tinylms'),
         'update_item'        => __('Update LXP School', 'tinylms'),
         'search_items'       => __('Search LXP School', 'tinylms'),
         'not_found'          => sprintf(__('You haven\'t had any School yet. Click <a href="%s">Add new</a> to start', 'tinylms'), admin_url('post-new.php?post_type=tl_school')),
         'not_found_in_trash' => __('No LXP School found in Trash', 'tinylms'),
      );

      $args = array(
         'labels'             => $labels,
         'public'             => true,
         'query_var'          => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'has_archive'        => true,
         'show_in_menu'       => true,
         'show_in_admin_bar'  => true,
         'show_in_nav_menus'  => true,
         'rewrite'            => array(
            'slug'       => 'school',
            'with_front' => false
         ),
         'show_in_rest'       => false,
         'rest_base'          => 'tl_school',
         'supports' => array('title', 'editor', 'thumbnail'),
         // 'capabilities' => array(
         //     'edit_post' => 'edit_lxp_school',
         //     'publish_posts' => 'publish_lxp_schools',
         //     'read_post' => 'read_lxp_school',
         //     'read_private_posts' => 'read_private_lxp_schools',
         //     'delete_post' => 'delete_lxp_school',
         //     'delete_posts' => 'delete_lxp_schools',
         //     'create_posts' => 'create_lxp_schools',
         //     'create_post' => 'create_lxp_school'
         // )
      );
      add_theme_support('post-thumbnails');
      return $args;
   }


   public function add_meta_boxes()
   {
      $this->add_meta_box([
         'School-district-id',      // Unique ID
         esc_html__('School District ', 'school'),    // Title
         array(self::instance(), 'lxp_school_district_metabox_html'),   // Callback function
         $this->_post_type,         // Admin page (or post type)
         'advanced',         // Context
         'default',         // Priority
         'show_in_rest' => true,
      ]);
   }

   function post_meta_request_params($args, $request)
   {
      $args += array(
         'meta_key'   => $request['meta_key'],
         'meta_value' => $request['meta_value'],
         'meta_query' => $request['meta_query'],
      );
      return $args;
   }

   public function lxp_school_district_metabox_html($post = null)
   {
      $args = array(
         'post_type' => 'tl_district',
         'orderby'    => 'ID',
         'post_status' => 'publish',
         'order'    => 'DESC',
         'posts_per_page' => -1
      );
      $districts = get_posts($args);
      $selectedDistrict =  get_post_meta($post->ID, 'lxp_school_district_id', true) ;
      $output = '  <h4>Select District</h4>';

      $output .= '<select name="lxp_school_district_id" id="course_select_options" style="margin-top:-10px"> ';
      foreach ($districts as $district) {
         if ($selectedDistrict == $district->ID) {
            $selected = "selected";
         } else {
            $selected = "";
         }
         $output .= '<option value="' . $district->ID . '" ' . $selected . ' >' . $district->post_title . ' </option>';
      }
      $output .= '</select>';
      echo $output;
   }

   public function save_tl_post($post_id = null)
   {
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_type']) && 'tl_school' == $_POST['post_type']) {
         update_post_meta($post_id, 'lxp_school_district_id', $_POST['lxp_school_district_id']);
      }
   }


   public function insert_post_api($post, $request)
   {
   }

   function modify_list_row_actions($actions, $post)
   {
      return $actions;
   }

   public  function grade_view()
   {
   }

   public  function grade_book_view()
   {
   }

   public function register_views()
   {
   }
}
