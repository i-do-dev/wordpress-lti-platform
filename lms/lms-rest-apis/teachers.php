<?php

class Rest_Lxp_Teacher
{
	/**
	 * Register the REST API routes.
	 */
	public static function init()
	{
		if (!function_exists('register_rest_route')) {
			// The REST API wasn't integrated into core until 4.4, and we support 4.0+ (for now).
			return false;
		}
		
		register_rest_route('lms/v1', '/store/teacher', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Teacher', 'store_teacher'),
				'permission_callback' => '__return_true',
				'args' => array(
					'user_email' => array(
					   'required' => true,
					   'type' => 'string',
					   'description' => 'user login name',  
					   'format' => 'email'
				   ),
				   'login_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user login name name'
				),
				'first_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user first name',
				),
				'last_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user last name',
				),
				'user_pass' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user login password',
				),
				'school_id' => array(
					'required' => true,
					'type' => 'integer',
					'description' => 'user school id',
				), 
			   )
			),
		));

        register_rest_route('lms/v1', '/get/teacher', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('Rest_Lxp_Teacher', 'get_teacher'),
				'permission_callback' => '__return_true',
				'args' => array(
					'id' => array(
						'required' => true,
						'type' => 'integer',
						'description' => 'user account id',
					), 
				   )
			),
		));
		
		register_rest_route('lms/v1', '/update/teacher', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Teacher', 'update_teacher'),
				'permission_callback' => '__return_true',
				'args' => array(
					'user_email' => array(
					   'required' => true,
					   'type' => 'string',
					   'description' => 'user login name',  
					   'format' => 'email'
				   ),
				   'login_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user login name name'
				),
				'first_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user first name',
				),
				'last_name' => array(
					'required' => true,
					'type' => 'string',
					'description' => 'user last name',
				),
				'id' => array(
					'required' => true,
					'type' => 'integer',
					'description' => 'user account id',
				),
				   
			   )
			),
		));
		
	}

	public static function store_teacher()
	{
        $user_data = array(
            'user_login' => $_POST['login_name'],
            'first_name' => $_POST['first_name'],
            'last_name' =>$_POST['last_name'],
            'user_email' =>$_POST['user_email'],
            'display_name' =>$_POST['first_name'] . ' ' .$_POST['last_name'],
            'user_pass' =>$_POST['login_pass'],
            'role' => 'teacher'
         );
          $user_id  = wp_insert_user($user_data);
		 if(isset( $user_id->errors)){
			return  wp_send_json_error($user_id->errors, 400);
		 }
		  return  wp_send_json_success (update_user_meta($user_id , 'lxp_school_id', $_POST['school_id']));
	}

    public static function get_teacher()
	{
        $users =  get_user_by('id', $_GET['id']
         );
         return  wp_send_json_success($users);
	}

    public static function update_teacher()
	{
        $user_data = array(
            'ID' => $_POST['id'],
            'user_login' => $_POST['login_name'],
            'first_name' => $_POST['first_name'],
            'last_name' =>$_POST['last_name'],
            'user_email' =>$_POST['user_email'],
            'display_name' =>$_POST['first_name'] . ' ' .$_POST['last_name'],
            'user_pass' =>$_POST['login_pass']
         );
         wp_send_json_success (wp_update_user($user_data));
		 
	}
}
