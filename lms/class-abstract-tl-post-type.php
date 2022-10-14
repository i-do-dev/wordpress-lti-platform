<?php

/**
 * Class TL_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

 abstract class TL_Post_Type {
    /**
	 * Type of post
	 *
	 * @var string
	 */
	protected $_post_type = '';

    /**
	 * Columns display on list table
	 *
	 * @var array
	 */
	protected $_columns = array();

	/**
	 * Metaboxes registered
	 *
	 * @var array
	 */
	protected $_meta_boxes = array();

    /**
	 * @var array
	 */
	protected $_default_metas = array();

    /**
	 * Constructor
	 *
	 * @param string
	 * @param mixed
	 */
	public function __construct( $post_type = '', $args = '' ) {

        if ( ! empty( $post_type ) ) {
			$this->_post_type = $post_type;
		}
		
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_tl_post' ), 10, 2 );
		add_action( 'the_content', array( $this, 'tl_post_content' ));
		//add_filter( 'pre_post_link', array( $this, 'custom_pre_post_link' ), 10, 2 );
		//add_action( 'deleted_post', array( $this, 'deleted_post'));
		/*add_action( 'before_delete_post', array( $this, '_before_delete_post' ) );
		add_action( 'wp_trash_post', array( $this, '_before_trash_post' ) );
		add_action( 'trashed_post', array( $this, '_trashed_post' ) ); */

    }

    /**
	 * This function is invoked along with 'init' action to register
	 * new post type with WP.
	 */
	public function register() {
		$args = $this->args_register_post_type();
		if ( $args ) {
			register_post_type( $this->_post_type, $args );
			flush_rewrite_rules();
		}
	}

    /**
	 * Args to register custom post type.
	 *
	 * @return array
	 */
	public function args_register_post_type() : array {
		return array();
	}

	/**
	 * Add Metabox.
	 *
	 * @return void
	 */
	public function add_meta_box($args = array()) {
		if (is_array($args) && !empty($args)) {
			call_user_func_array('add_meta_box', $args);
		}
	}

	
	/**
	 * Add Metaboxs.
	 *
	 * @return void
	 */
	
	public function add_meta_boxes () {}

	public function save_tl_post () {}
	
	public function tl_post_content () {}

 }