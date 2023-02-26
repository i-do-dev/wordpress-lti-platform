<?php

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
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_sections SET content = '" . $_POST['content'] . "', title='" . $_POST['title'] . "' where id=" . $_POST['section_id']);
			$recordId = $_POST['section_id'];   //update using wpdb->update
		} else {
			$wpdb->insert($wpdb->prefix . 'trek_sections', array(
				'trek_id' => $_POST['post_id'],
				'title' => $_POST['title'],
				'type' => 'content',
				'content' => $_POST['content'],
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
		global $wpdb;
		$wpdb->insert($wpdb->prefix . 'trek_events', array(
			'trek_section_id' => $_POST['trek_section_id'],
			'start' =>  $_POST['start'],
			'end' =>  $_POST['end']
		));
		$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $_POST['trek_section_id']);
		$data[0]->title;
		$data[0]->trek_id;
		$trekPost = get_post($data[0]->trek_id);
		$response['title'] = $data[0]->title . " - " .  $trekPost->post_title;
		$response ['start'] = (int) $_POST['start'];
		$response ['end'] = (int)  $_POST['end'];
		$response ['id'] = $wpdb->insert_id;;
		$response ['textColor'] = 'white';
		if (strtolower(trim($data[0]->title)) == 'recall') {
			$response ['color'] = '#ca2738';
		} elseif (strtolower(trim($data[0]->title)) == 'apply') {
			$response ['color'] = '#9fc33b;';
		} elseif (strtolower(trim($data[0]->title)) == 'overview') {
			$response ['color'] = '#979797;';
		} else {
			$response ['color'] = '#1fa5d4;';
		}
		return $response;
	}

	public static function get_all_trek_events($request = null)
	{
		global $wpdb;
		$response = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_events");
		foreach ($response as $key => $row) {
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_sections WHERE id = " . $row->trek_section_id);
			$trekPost = get_post($data[0]->trek_id);
			$response[$key]->title =  $data[0]->title . " - " . $trekPost->post_title;
			$response[$key]->start = (int) $row->start;
			$response[$key]->end = (int) $row->end;
			$response[$key]->id = $row->id;
			$response[$key]->textColor = 'white';
			if (strtolower(trim($data[0]->title)) == 'recall') {
				$response[$key]->color = '#ca2738';
			} elseif (strtolower(trim($data[0]->title)) == 'apply') {
				$response[$key]->color = '#9fc33b;';
			} elseif (strtolower(trim($data[0]->title)) == 'overview') {
				$response[$key]->color = '#979797;';
			} else {
				$response[$key]->color = '#1fa5d4;';
			}
		}
		return $response;
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
}
