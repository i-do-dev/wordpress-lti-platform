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
		
		// ============= Class Post =================================
		$class_teacher_id = $request->get_param('class_teacher_id');
		$class_post_id = intval($request->get_param('class_post_id'));
		$class_name = trim($request->get_param('class_name'));
		$class_description = trim($request->get_param('class_description'));
		
		$shool_post_arg = array(
			'post_title'    => wp_strip_all_tags($class_name),
			'post_content'  => $class_description,
			'post_status'   => 'publish',
			'post_author'   => $class_teacher_id,
			'post_type'   => TL_CLASS_CPT
		);
		if (intval($class_post_id) > 0) {
			$shool_post_arg['ID'] = "$class_post_id";
		}
		// Insert / Update
		$class_post_id = wp_insert_post($shool_post_arg);
		if(get_post_meta($class_post_id, 'grades', true)) {
			update_post_meta($class_post_id, 'grades', json_encode($request->get_param('grades')));
		} else {
			add_post_meta($class_post_id, 'grades', json_encode($request->get_param('grades')), true);
		}

		if(get_post_meta($class_post_id, 'lxp_class_teacher_id', true)) {
			update_post_meta($class_post_id, 'lxp_class_teacher_id', $class_teacher_id);
		} else {
			add_post_meta($class_post_id, 'lxp_class_teacher_id', $class_teacher_id, true);
		}
		
		$student_ids = json_encode($request->get_param('student_ids'));
		if(get_post_meta($class_post_id, 'lxp_student_ids', true)) {
			update_post_meta($class_post_id, 'lxp_student_ids', json_encode($student_ids));
		} else {
			add_post_meta($class_post_id, 'lxp_student_ids', json_encode($student_ids), true);
		}

		$schedule = json_encode($request->get_param('schedule'));
		if(get_post_meta($class_post_id, 'schedule', true)) {
			update_post_meta($class_post_id, 'schedule', json_encode($schedule));
		} else {
			add_post_meta($class_post_id, 'schedule', json_encode($schedule), true);
		}

        return wp_send_json_success("Class Saved!");
    }

    public static function get_one($request) {
		$class_id = $request->get_param('class_id');
		$class = get_post($class_id);
		$class->grades = json_decode(get_post_meta($class_id, 'grades', true));
		$class->lxp_class_teacher_id = get_post_meta($class_id, 'lxp_class_teacher_id', true);
		$class->lxp_student_ids = json_decode(get_post_meta($class_id, 'lxp_student_ids', true));
		$class->schedule = json_decode(get_post_meta($class_id, 'schedule', true));
		return wp_send_json_success(array("class" => $class));
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
