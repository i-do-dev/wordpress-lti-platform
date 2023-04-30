<?php

require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/teachers.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/students.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/schools.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/classes.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/assignments.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/assignment-submissions.php' );

class LMS_REST_API
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
		
		Rest_Lxp_Teacher::init();
		Rest_Lxp_Student::init();
		Rest_Lxp_School::init();
		Rest_Lxp_Class::init();
		Rest_Lxp_Assignment::init();
		Rest_Lxp_Assignment_Submission::init();

		register_rest_route('lms/v1', '/scores', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_grade'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/token', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'return_token'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/get/playlists', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_playlists'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/store/trek/section', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_trek_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/trek/section', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_trek_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/trek/section', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_trek_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/treks', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_treks'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/trek/sections', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_trek_sections'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/store/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_trek_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/trek/events', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_trek_events'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/update/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'update_trek_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/trek/event', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_trek_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_trek_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/school/lxp/user', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_school_lxp_user'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/assigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'trek_assigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/assigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'trek_section_assigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/unassign/student', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'trek_unassign_student'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/unassigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'trek_get_unassigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/assigned/students/store', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'trek_section_assigned_students_store'),
				'permission_callback' => '__return_true',
			),
		));
	}

	public static function trek_section_assigned_students_store($request = null) {
		$event_store_response = self::store_trek_event($request);
		$event_store_response['id'];
		
		$student_ids = $request->get_param('student_ids');
		foreach ($student_ids as  $student_id) {
			global $wpdb;
			$table = $wpdb->prefix.'student_assignments';
			$data = array('student_id' => $student_id, 'assignment_id' => $event_store_response['id']);
			$format = array('%d','%d');
			$wpdb->insert($table,$data,$format);
		}

		return self::trek_section_assigned_students($request);
	}

	public static function trek_get_unassigned_students($request = null) {
		$assigned_users = self::trek_section_assigned_students($request);
		global $wpdb;
		$user_ids = array();
		foreach ($assigned_users as $user) {
			array_push($user_ids, $user->id);
		}
		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'students';
		if (count($user_ids) > 0) {
			$sql .= ' WHERE id NOT IN (' . implode(', ', $user_ids) . ')';
		}
		return $wpdb->get_results($sql);
	}

	public static function trek_unassign_student($request = null) {
		$student_assignment_id = $request->get_param('student_assignment_id');
		
		global $wpdb;
		$wpdb->delete(
			$wpdb->prefix . 'student_assignments', 		// table name with dynamic prefix
			['id' => $student_assignment_id], 						// which id need to delete
			['%d'], 							// make sure the id format
		);
		return self::trek_section_assigned_students($request);
	}

	public static function trek_section_assigned_students($request = null) {
		$trek_section_id = $request->get_param('trek_section_id');
		$teacher_id = $request->get_param('teacher_id');
		global $wpdb;
		$query = "SELECT {$wpdb->prefix}students.*, {$wpdb->prefix}student_assignments.id as student_assignment_id FROM {$wpdb->prefix}students
			  JOIN {$wpdb->prefix}student_assignments ON {$wpdb->prefix}student_assignments.student_id = {$wpdb->prefix}students.id
			  JOIN {$wpdb->prefix}trek_events ON {$wpdb->prefix}trek_events.id = {$wpdb->prefix}student_assignments.assignment_id
			  WHERE {$wpdb->prefix}trek_events.trek_section_id = \"{$trek_section_id}\" AND {$wpdb->prefix}trek_events.user_id = \"{$teacher_id}\"
			";
		return $wpdb->get_results($query);
	}

	public static function trek_assigned_students($request = null) {
		$event_id = $request->get_param('event_id');
		global $wpdb;
		$query = "SELECT {$wpdb->prefix}students.* FROM {$wpdb->prefix}students
					JOIN {$wpdb->prefix}student_assignments ON {$wpdb->prefix}student_assignments.student_id = {$wpdb->prefix}students.id
					JOIN {$wpdb->prefix}trek_events ON {$wpdb->prefix}trek_events.id = {$wpdb->prefix}student_assignments.assignment_id
					WHERE {$wpdb->prefix}trek_events.id = {$event_id}
				";
		return $wpdb->get_results($query);
	}

	public static function return_token($request = null)
	{
		return [
			"access_token" => "12312u3hufbvfb29rb932b192e",
			"token_type" => "Bearer",
			"expires_in" => 12312312312,
			"scope" => array()
		];
	}

	public static function store_grade($request = null)
	{
		global $wpdb;
		$json = file_get_contents('php://input');
		$data = json_decode($json);
		parse_str($_SERVER['QUERY_STRING'], $queries);

		$respones = $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "tiny_lms_grades WHERE user_id = " . $data->userId . "
		AND lesson_id= " . $queries['lesson']);
		if ($respones) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "tiny_lms_grades SET score = " . $data->scoreGiven . " where id=" . $respones[0]->id);
		} else {
			$wpdb->insert($wpdb->prefix . 'tiny_lms_grades', array(
				'lesson_id' => $queries['lesson'],
				'user_id' => $data->userId,
				'score' => $data->scoreGiven,
			));
		}
	}

	public static function get_playlists($request = null)
	{
		global $wpdb;
		$playlists = get_post_meta($_GET['course_id'], "lxp_sections", true);
		$playlists = json_decode($playlists);
		if (!is_array($playlists)) {
			$playlists = array();
		}

		if (!boolval(count($playlists))) {
			$playlists = ["Overview", "Recall", "Practice A", "Practice B", "Apply"];
		}

		$records = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE trek_id = " .  $_GET['post_id']);
		foreach ($records as $record) {
			foreach ($playlists as $key => $playlist) {
				if (trim($record->title) == trim($playlist)) {
					unset($playlists[$key]);
				}
			}
		}
		
		return array_values($playlists);
	}

	public static function store_trek_section($request = null)
	{
		$post = get_post($_POST['post_id']);
		if ($post->post_status == "auto-draft") {
			return 0;
		}
		global $wpdb;
		if ($_POST['section_id'] != 0) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_sections SET content = '" . $_POST['content'] . "', title='" . $_POST['title'] . "', sort=". intval($_POST['sort']) ." where id=" . $_POST['section_id']);
			$recordId = $_POST['section_id'];   //update using wpdb->update
		} else {
			$wpdb->insert($wpdb->prefix . 'trek_sections', array(
				'trek_id' => $_POST['post_id'],
				'title' => $_POST['title'],
				'type' => 'content',
				'content' => $_POST['content'],
				'sort' => intval($_POST['sort'])
			));
			$recordId = $wpdb->insert_id;
		}
		return $recordId;
	}

	public static function get_trek_section($request = null)
	{
		global $wpdb;
		$respones = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $_GET['section_id']);
		$respones[0]->content = stripslashes($respones[0]->content);
		return $respones;
	}

	public static function delete_trek_section($request = null)
	{
		global $wpdb;
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "trek_sections WHERE id =" . $_POST['section_id']);
		return [];
	}

	public static function get_all_treks($request = null)
	{
		$args = array(
			'post_type' => 'tl_trek',
			'orderby'    => 'ID',
			'post_status' => 'publish',
			'order'    => 'DESC',
			'posts_per_page' => -1
		);
		$districts = get_posts($args);
		return $districts;
	}

	public static function get_all_trek_sections($request = null)
	{
		global $wpdb;
		$respones = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE trek_id = " . $_GET['trek_post_id']);
		return $respones;
	}

	public static function store_trek_event($request = null)
	{
		$start = $request->get_param('start');
		$end = $request->get_param('end');
		$trek_section_id = $request->get_param('trek_section_id');
		$user_id = $request->get_param('user_id');
		if (intval($user_id) == 0) {
			$user_id = 1;
		}

		global $wpdb;
		$wpdb->insert($wpdb->prefix . 'trek_events', array(
			'trek_section_id' => $trek_section_id,
			'start' =>  $start,
			'end' =>  $end,
			'user_id' => $user_id
		));

		$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $trek_section_id);
		$data[0]->title;
		$data[0]->trek_id;
		$trekPost = get_post($data[0]->trek_id);
		$response['title'] = $data[0]->title . " - " .  $trekPost->post_title;
		$response['start'] = explode(' ', $start)[0];
		$response['end'] = explode(' ', $end)[0];
		$response['id'] = $wpdb->insert_id;;
		$response['textColor'] = 'white';
		if (strtolower(trim($data[0]->title)) == 'recall') {
			$response['color'] = '#ca2738';
		} elseif (strtolower(trim($data[0]->title)) == 'apply') {
			$response['color'] = '#9fc33b;';
		} elseif (strtolower(trim($data[0]->title)) == 'overview') {
			$response['color'] = '#979797;';
		} else {
			$response['color'] = '#1fa5d4;';
		}
		return $response;
	}

	public static function get_all_trek_events($request = null)
	{
		
		return [
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Recall",
				"segment" => "recall",
				"trek" => "5.6A Physical Properties of Matter"
			)/* ,
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Overview",
				"segment" => "overview",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Practice A",
				"segment" => "practice-a",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Practice B",
				"segment" => "practice-b",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Apply",
				"segment" => "apply",
				"trek" => "5.6A Physical Properties of Matter"
			), */
			
		];
		
		global $wpdb;
		$result = array();
		$response = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_events where user_id=" . $_GET['user_id']);
		foreach ($response as $key => $row) {
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $row->trek_section_id);
			if (isset($data[0])) {
				$trekPost = get_post($data[0]->trek_id);
				// $response[$key]->title =  $data[0]->title . " - " . $trekPost->post_title;
				// $response[$key]->start = explode(' ', $row->start)[0];
				// $response[$key]->end = explode(' ', $row->end)[0];
				//$response[$key]->id = $row->id;
				//$response[$key]->textColor = 'white';
				//$response[$key]->allDay = false;
				/*
				if (strtolower(trim($data[0]->title)) == 'recall') {
					$response[$key]->color = '#ca2738';
				} elseif (strtolower(trim($data[0]->title)) == 'apply') {
					$response[$key]->color = '#9fc33b;';
				} elseif (strtolower(trim($data[0]->title)) == 'overview') {
					$response[$key]->color = '#979797;';
				} else {
					$response[$key]->color = '#1fa5d4;';
				}
				*/

				$obj = new \stdClass();
				$obj->id = $row->id;
				$obj->textColor = 'white';
				$obj->title = $data[0]->title . " - " . $trekPost->post_title;
				$obj->start = explode(' ', $row->start)[0];
				$obj->end = explode(' ', $row->end)[0]; //2023-02-28T01:30:00
				$obj->allDay=false;	
				if (strtolower(trim($data[0]->title)) == 'recall') {
					$obj->color = '#ca2738';
				} elseif (strtolower(trim($data[0]->title)) == 'apply') {
					$obj->color = '#9fc33b';
				} elseif (strtolower(trim($data[0]->title)) == 'overview') {
					$obj->color = '#979797';
				} else {
					$obj->color = '#1fa5d4';
				}	
				
				$obj->trekTitle = $trekPost->post_title;
				$obj->trekSectionId = $row->trek_section_id;
				$obj->trekSectionTitle = $data[0]->title;
				array_push($result, $obj);
			}
		}

		/* 
		$obj = new \stdClass();
		$obj->title ='event3';
		$obj->start ='2023-02-27T15:30:00';
		$obj->end ='2023-02-27T16:45:00'; //2023-02-28T01:30:00
		$obj->allDay=false;
		array_push($result, $obj);
		 */
		return $result;
	}


	public static function update_trek_event($request = null)
	{
		global $wpdb;
		if (isset($_POST['trek_section_id'])) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_events SET start = " . $_POST['start'] . ", end=" . $_POST['end'] . ", trek_section_id=" . $_POST['trek_section_id'] . " where id=" . $_POST['id']);
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $_POST['trek_section_id']);
			$trekPost = get_post($data[0]->trek_id);
			$response['title'] = $data[0]->title . " - " .  $trekPost->post_title;
			$response['textColor'] = 'white';
			if (strtolower(trim($data[0]->title)) == 'recall') {
				$response['color'] = '#ca2738';
			} elseif (strtolower(trim($data[0]->title)) == 'apply') {
				$response['color'] = '#9fc33b;';
			} elseif (strtolower(trim($data[0]->title)) == 'overview') {
				$response['color'] = '#979797;';
			} else {
				$response['color'] = '#1fa5d4;';
			}
			return $response;
		} else {
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_events SET start = " . $_POST['start'] . ", end=" . $_POST['end']  . " where id=" . $_POST['id']);
			return;
		}
	}

	public static function get_trek_event($request = null)
	{
		global $wpdb;
		$event = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_events WHERE id = " . $_GET['id']);
		$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $event[0]->trek_section_id);
		// $data->title;
		$trekPost = get_post($data[0]->trek_id);
		// $response['title'] = $data[0]->title ." - ".  $trekPost->post_title ;
		$response['trek_section_id'] = $event[0]->trek_section_id;
		$response['trek_id'] = $data[0]->trek_id;
		return $response;
	}

	public static function delete_trek_event($request = null)
	{
		global $wpdb;
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "trek_events WHERE id =" . $_POST['id']);
		return [];
	}

	public static function delete_school_lxp_user($request = null)
	{
		delete_user_meta($_POST['user_id'], 'lxp_school_id');
		return [];
	}
}


?>
