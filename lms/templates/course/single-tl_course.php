<?php
/*
Template Name: Course-template
*/
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wp-site-blocks">
		<header class="wp-block-template-part site-header">
			<?php block_header_area(); ?>
		</header>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php  echo "<p>" . $post->post_content . " </p>"; ?>
				<h3>Lessons </h3>
				<?php
				// Start the loop.
				$args = array(
					'posts_per_page'   => -1,
					'post_type'        => 'tl_lesson',
					'meta_query' => array(
						array(
							'key'   => 'tl_course_id',
							'value' =>  $post->ID
						)
					)
				);
				$result = get_posts($args);
				echo "<ul>";
				foreach ($result as $result) {
					echo '<li>';
					echo '<a href="' . get_permalink($result->ID) . '" target="blank">' . $result->post_title . '</a>';
					echo '</li>';
				}
				echo "</ul>";
				?>
				<div>
					<?php
					$tags = get_the_terms($post->ID, 'tl_course_tag');
					if ($tags) {
						foreach ($tags as $tag) {
							$tag_link = get_tag_link($tag->term_id);
							$html = "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>{$tag->name}</a>";
							$tag_names[] = $html;
						}
						echo  "<span class='dashicons dashicons-tag'></span>&nbsp&nbsp" . implode(', ', $tag_names);
					}
					?>
				</div>
			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<footer class="wp-block-template-part site-footer">
			<?php block_footer_area(); ?>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>