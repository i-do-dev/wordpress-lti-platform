<?php

class Rest_Lxp_Assignment_Submission
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

        register_rest_route('lms/v1', '/assignment/submission', array(
			array(
				'methods' => WP_REST_Server::ALLMETHODS,
				'callback' => array('Rest_Lxp_Assignment_Submission', 'assignment_submission'),
				'permission_callback' => '__return_true'
			)
		));
    }

    public static function assignment_submission($request) {
        $assignmentId = $request->get_param('assignmentId');
        $assignment_post = get_post($assignmentId);

        $userId = $request->get_param('userId');
        $user_post_query = new WP_Query( array( 
            'post_type' => TL_STUDENT_CPT, 
            'post_status' => array( 'publish' ),
            'posts_per_page'   => -1,        
            'meta_query' => array(
                array('key' => 'lxp_student_admin_id', 'value' => $userId, 'compare' => '=')
            )
        ) );
        $user_posts = $user_post_query->get_posts();

        if ($user_posts) {   
            $user_post = $user_posts[0];
            $assignment_submission_post_title = $user_post->post_title . ' | ' . $assignment_post->post_title;
            
            $assignment_submission_post_arg = array(
				'post_title'    => wp_strip_all_tags($assignment_submission_post_title),
				'post_content'  => $assignment_submission_post_title,
				'post_status'   => 'publish',
				'post_author'   => $userId,
				'post_type'   => TL_ASSIGNMENT_SUBMISSION_CPT
			);
            
            $assignment_submission_post_id = wp_insert_post($assignment_submission_post_arg);
            if ($assignment_submission_post_id) {
                // add assignment submission post meta data for $assignmentId and $user_post
                add_post_meta($assignment_submission_post_id, 'lxp_assignment_id', $assignmentId);
                add_post_meta($assignment_submission_post_id, 'lxp_student_id', $user_post->ID);

                // get 'ltiUserId', 'submissionId from $request and add as post meta data
                $ltiUserId = $request->get_param('ltiUserId');
                $submissionId = $request->get_param('submissionId');
                add_post_meta($assignment_submission_post_id, 'lti_user_id', $ltiUserId);
                add_post_meta($assignment_submission_post_id, 'submission_id', $submissionId);

                // get array values for 'min', 'max', 'raw' and 'scaled' from 'score' array key of $request paramter 'result' and add as assignment submission post meta data
                $score = $request->get_param('result')['score'];
                add_post_meta($assignment_submission_post_id, 'score_min', $score['min']);
                add_post_meta($assignment_submission_post_id, 'score_max', $score['max']);
                add_post_meta($assignment_submission_post_id, 'score_raw', $score['raw']);
                add_post_meta($assignment_submission_post_id, 'score_scaled', $score['scaled']);

                // get 'completion' and 'duration' key values from 'result' $request parameter and add as assignment submission post meta data
                $completion = $request->get_param('result')['completion'];
                $duration = $request->get_param('result')['duration'];
                add_post_meta($assignment_submission_post_id, 'completion', intval($completion));
                add_post_meta($assignment_submission_post_id, 'duration', $duration);
                return wp_send_json_success("Assignment Submission Created!");
            } else {
                return wp_send_json_error("Assignment Submission Creation Failed!");
            }
        }
    }
}
