<?php

class Rest_Lxp_School
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

        register_rest_route('lms/v1', '/shools/save', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('Rest_Lxp_School', 'create'),
				'permission_callback' => '__return_true',
                'args' => array(
                    'school_name' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'School name',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
                    'user_email' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'user login name',  
						'format' => 'email',
					),
					'school_about' => array(
						'required' => false,
						'type' => 'string',
						'description' => 'user about description',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'first_name' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'user first name',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'last_name' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'user last name',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'user_password' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'user login password',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 1;
						}
					),
					'user_id' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'user id',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 0;
						}
					),
					'post_id' => array(
						'required' => true,
						'type' => 'string',
						'description' => 'post id',
						'validate_callback' => function($param, $request, $key) {
							return strlen( $param ) > 0;
						}
					)
                )
            )
        ));
    }

    public static function create($request) {

		// ============= School Post =================================
		$user_id = $request->get_param('user_id');
		$post_id = "168"; //intval($request->get_param('post_id'));
		$school_name = trim($request->get_param('school_name'));
		$school_description = trim($request->get_param('school_about'));
		
		$shool_post_arg = array(
			'post_title'    => wp_strip_all_tags($school_name) . " - WWWW 111 xx",
			'post_content'  => $school_description . " - WWWW 222 xx",
			'post_status'   => 'publish',
			'post_author'   => $user_id,
			'post_type'   => "tl_school"
		);
		if (intval($post_id) > 0) {
			$shool_post_arg['ID'] = "$post_id";
		}
		// Insert / Update
		$post_id = wp_insert_post($shool_post_arg);
		
		// ============= Profile Picture =============================
		$file = $request->get_file_params();
		$profilePicture = isset($file['profile_picture']) ? $file['profile_picture'] : null;
		if ($profilePicture['size'] > 0) {
			$mimes = array(
				'bmp'  => 'image/bmp',
				'gif'  => 'image/gif',
				'jpe'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg'  => 'image/jpeg',
				'png'  => 'image/png',
				'tif'  => 'image/tiff',
				'tiff' => 'image/tiff'
			);
			
			$overrides = array(
				'mimes'     => $mimes,
				'test_form' => false
			);
			 
			$upload = wp_handle_upload( $file['profile_picture'], $overrides );

			if ( $upload && !isset( $upload['error'] ) ){
				// File uploaded successfully. 
				$uploadedFileURL = $upload['url'];
				$uploadedFileName = basename($upload['url']);
				/* 
				echo $uploadedFileURL;
				echo $uploadedFileName;

				$uploadedFileURL = "http://localhost/wordpress/wp-content/uploads/2023/03/bc-dimentions.jpg";
				$uploadedFileName = "bc-dimentions.jpg";
				 */
	
	
				// Add Featured Image to Post
				$image_url        = $uploadedFileURL; // Define the image URL here
				$image_name       = $uploadedFileName;
				$upload_dir       = wp_upload_dir(); // Set upload folder
				$image_data       = file_get_contents($image_url); // Get image data
				$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
				$filename         = basename( $unique_file_name ); // Create image file name
	
				// Check folder permission and define file location
				if( wp_mkdir_p( $upload_dir['path'] ) ) {
					$file = $upload_dir['path'] . '/' . $filename;
				} else {
					$file = $upload_dir['basedir'] . '/' . $filename;
				}
				
				// Create the image  file on the server
				file_put_contents( $file, $image_data );
	
				// Check image file type
				$wp_filetype = wp_check_filetype( $filename, null );
				
				// Set attachment data
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_file_name( $filename ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				
				// Create the attachment
				$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	
				// Include image.php
				require_once(ABSPATH . 'wp-admin/includes/image.php');
	
				// Define attachment metadata
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	
				// Assign metadata to attachment
				wp_update_attachment_metadata( $attach_id, $attach_data );
	
				// And finally assign featured image to post
				set_post_thumbnail( $post_id, $attach_id );	
			}
 			
		}

        return wp_send_json_success("YEesss!!");
    }
}
?>