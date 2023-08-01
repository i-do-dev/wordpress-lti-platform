<?php

class Rest_Lxp_Group
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

		register_rest_route('lms/v1', '/group/save', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Group', 'create'),
				'permission_callback' => '__return_true',
				'args' => array(
					'group_name' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'group name',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'sg_student_ids' => array(
						'required' => true,
						'description' => 'group students',
						'validate_callback' => function($param, $request, $key) {
							if (count( $param ) > 0) {
								return true;
							} else {
								return false;
							}
						}
					),
					'group_teacher_id' => array(
						'required' => true,
						'type' => 'integer',
						'description' => 'group teacher id',
						'validate_callback' => function($param, $request, $key) {
							return intval( $param ) > 0;
						}
					),
					'group_post_id' => array(
						'required' => true,
						'type' => 'integer',
						'description' => 'group post id',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 0;
						}
					)
			   )
			),
		));

		register_rest_route('lms/v1', '/group', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_Group', 'get_one'),
				'permission_callback' => '__return_true'
			)
		));
		
	}

	public static function create($request) {
		// ============= Group Post =================================
		$group_teacher_id = $request->get_param('group_teacher_id');
		$group_post_id = intval($request->get_param('group_post_id'));
		$group_name = trim($request->get_param('group_name'));
		$group_description = trim($request->get_param('group_description'));

		$group_post_arg = array(
			'post_title'    => wp_strip_all_tags($group_name),
			'post_content'  => $group_description,
			'post_status'   => 'publish',
			'post_author'   => $group_teacher_id,
			'post_type'   => TL_GROUP_CPT
		);
		if (intval($group_post_id) > 0) {
			$group_post_arg['ID'] = "$group_post_id";
		}
		
		// Insert / Update
		$group_post_id = wp_insert_post($group_post_arg);
		
		if(get_post_meta($group_post_id, 'lxp_group_teacher_id', true)) {
			update_post_meta($group_post_id, 'lxp_group_teacher_id', $group_teacher_id);
		} else {
			add_post_meta($group_post_id, 'lxp_group_teacher_id', $group_teacher_id, true);
		}
		
		delete_post_meta($group_post_id, 'lxp_group_student_ids');
		$student_ids = $request->get_param('sg_student_ids');
		foreach ($student_ids as $student_id) {
			add_post_meta($group_post_id, 'lxp_group_student_ids', $student_id);
		}

		if(get_post_meta($group_post_id, 'lxp_class_group_type', true)) {
			update_post_meta($group_post_id, 'lxp_class_group_type', $request->get_param('group_type'));
		} else {
			add_post_meta($group_post_id, 'lxp_class_group_type', $request->get_param('group_type'), true);
		}

		if(get_post_meta($group_post_id, 'lxp_class_group_id', true)) {
			update_post_meta($group_post_id, 'lxp_class_group_id', $request->get_param('classes_other_group'));
		} else {
			add_post_meta($group_post_id, 'lxp_class_group_id', $request->get_param('classes_other_group'), true);
		}

		if(get_post_meta($group_post_id, 'lxp_classe_group_name', true)) {
			update_post_meta($group_post_id, 'lxp_classe_group_name', $request->get_param('classes_other_group_name'));
		} else {
			add_post_meta($group_post_id, 'lxp_classe_group_name', $request->get_param('classes_other_group_name'), true);
		}

        return wp_send_json_success("Saved Group!");
    }

    public static function get_one($request) {
		$group_id = $request->get_param('group_post_id');
		$group = get_post($group_id);
		$group->lxp_group_student_ids = get_post_meta($group_id, 'lxp_group_student_ids');
		$group->lxp_class_group_id = get_post_meta($group_id, 'lxp_class_group_id', true);
		return wp_send_json_success(array("group" => $group));
	}
}
