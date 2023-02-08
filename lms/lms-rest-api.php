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

		$respones = $wpdb->get_results("SELECT id FROM ".$wpdb->prefix."tiny_lms_grades WHERE user_id = " . $data->userId . "
		AND lesson_id= " . $queries['lesson']);
		if ($respones) {
			$wpdb->query("UPDATE ".$wpdb->prefix."tiny_lms_grades SET score = ".$data->scoreGiven." where id=" . $respones[0]->id);
		} else {
			$wpdb->insert($wpdb->prefix.'tiny_lms_grades', array(
				'lesson_id' => $queries['lesson'],
				'user_id' => $data->userId,
				'score' => $data->scoreGiven,
			));
		}
	}

	public static function get_playlists($request = null)
	{
		$playlists = get_post_meta($_GET['course_id'], "lxp_sections", true);
		return json_decode($playlists);
	}

}
