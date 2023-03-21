<?php

class Rest_Lxp_Class
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

		register_rest_route('lms/v1', '/classes', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Class', 'get_one'),
				'permission_callback' => '__return_true'
			)
		));
		
		register_rest_route('lms/v1', '/classes/save', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Class', 'create'),
				'permission_callback' => '__return_true',
				'args' => array(
					'class_name' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'class name',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),					
					'class_description' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'class description',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),					
					'schedule' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'class schedule',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'grade' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'class schedule',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'student_ids' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'class schedule',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'class_teacher_id' => array(
						'required' => true,
						'type' => 'integer',
						'description' => 'class teacher id',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'class_post_id' => array(
						'required' => true,
						'type' => 'integer',
						'description' => 'class post id',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 0;
						}
					)
			   )
			),
		));
		
		register_rest_route('lms/v1', '/update/class', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Class', 'update_class'),
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

	public static function create($request) {		
		
		var_dump($request->get_params());
		die();

		// ============= Class Post =================================
		$school_admin_id = $request->get_param('school_admin_id');
		$class_post_id = intval($request->get_param('class_post_id'));
		$class_name = trim($request->get_param('user_email'));
		$class_description = trim($request->get_param('about'));
		
		$shool_post_arg = array(
			'post_title'    => wp_strip_all_tags($class_name),
			'post_content'  => $class_description,
			'post_status'   => 'publish',
			'post_author'   => $school_admin_id,
			'post_type'   => TL_CLASS_CPT
		);
		if (intval($class_post_id) > 0) {
			$shool_post_arg['ID'] = "$class_post_id";
		}
		// Insert / Update
		$class_post_id = wp_insert_post($shool_post_arg);
		if(get_post_meta($class_post_id, 'grades', json_encode($request->get_param('grades')))) {
			update_post_meta($class_post_id, 'grades', json_encode($request->get_param('grades')));
		} else {
			add_post_meta($class_post_id, 'grades', json_encode($request->get_param('grades')), true);
		}

		// ========== Class Admin ===========
		$class_admin_data = array(
			'user_login' => trim($request->get_param('user_email')),
			'user_email' => trim($request->get_param('user_email')),
			'first_name' => trim($request->get_param('first_name')),
			'last_name' => trim($request->get_param('last_name')),
			'display_name' => trim($request->get_param('first_name')) . ' ' . trim($request->get_param('last_name')),
			'role' => 'lxp_class'
		);
		
		if (trim($request->get_param('user_password'))) {
			$class_admin_data['user_pass'] = trim($request->get_param('user_password'));
		}

		$lxp_class_admin_id = get_post_meta($class_post_id, 'lxp_class_admin_id', true);
		if ($lxp_class_admin_id) {
			$class_admin_data["ID"] = $lxp_class_admin_id;
		}
		$class_admin_id  = wp_insert_user($class_admin_data);
		if (trim($request->get_param('user_password'))) {
			wp_set_password( trim($request->get_param('user_password')), $class_admin_id );
		}

		if (!boolval($lxp_class_admin_id) && $class_admin_id) {
			if(get_post_meta($class_post_id, 'lxp_class_admin_id', $class_admin_id)) {
				update_post_meta($class_post_id, 'lxp_class_admin_id', $class_admin_id);
			} else {
				add_post_meta($class_post_id, 'lxp_class_admin_id', $class_admin_id, true);
			}
			
			if(get_post_meta($class_post_id, 'lxp_class_school_id', true)) {
				update_post_meta($class_post_id, 'lxp_class_school_id', trim($request->get_param('class_school_id')));
			} else {
				add_post_meta($class_post_id, 'lxp_class_school_id', trim($request->get_param('class_school_id')), true);
			}
		}

        return wp_send_json_success("Class Saved!");
    }

    public static function get_one($request) {
		$class_id = $request->get_param('class_id');
		$class = get_post($class_id);
		$class->grades = json_decode(get_post_meta($class_id, 'grades', true));
		$admin = get_userdata(get_post_meta($class_id, 'lxp_class_admin_id', true));
		$admin->data->first_name = get_user_meta($admin->ID, 'first_name', true);
		$admin->data->last_name = get_user_meta($admin->ID, 'last_name', true);
		return wp_send_json_success(array("class" => $class, "admin" => $admin));
	}

    public static function update_class()
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
