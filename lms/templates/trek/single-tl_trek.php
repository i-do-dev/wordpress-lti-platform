<?php
$treks_src = plugin_dir_url( __FILE__ ) . 'treks-src';
// Start the loop.
$courseId =  isset($_GET['courseid']) ? $_GET['courseid'] : get_post_meta($post->ID, 'tl_course_id', true);
$args = array(
	'posts_per_page'   => -1,
	'post_type'        => 'tl_lesson',
	'meta_query' => array(
		array(
			'key'   => 'tl_course_id',
			'value' =>  $courseId
		)
	)
);
$lessons = get_posts($args);
$button_styles = array();
while (have_posts()) : the_post();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php the_title(); ?></title>
    <link href="<?php echo $treks_src; ?>/style/common.css" rel="stylesheet" />
    <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />

    <style type="text/css">
      .trek-section-nav-anchor {
        text-decoration: none;
      }
      .trek-main-heading {
        font-size: 1.5rem;
      }

      .digital-student-journal-section {
        justify-content: end;
      }
      .digital-student-journal-btn {
        width: 110% !important;
      }
      .trek-main-heading-wrapper {
        display: flex;
        width: 100%;
        justify-content: space-between;
        margin-bottom: 10px;
      }
      .trek-main-heading-top-link {
        margin-left: auto;
        background-color: #eaedf1;
        color: #979797;
        border: 1.5px solid #979797;
        padding: 6px;
        text-decoration: auto;
        font-size: 0.85rem;
      }
      .central-cncpt-section h1 {
        font-size: 1.6rem;
      }
      .central-cncpt-section h2 {
        font-size: 1.4rem;
      }
      .central-cncpt-section h3 {
        font-size: 1.3rem;
      }
      .copy-anchor-icon-img {
        margin-left: 5px;
      }
      a:target {
        background-color: yellow !important;
      }
    </style>
  </head>
  <body>
    <!-- Menu -->
    <nav class="navbar navbar-expand-lg treks-nav">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <div class="header-logo-search">
            <!-- logo -->
            <div class="header-logo">
              <img src="<?php echo $treks_src; ?>/assets/img/header_logo.svg" alt="svg" />
            </div>
          </div>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="navbar-nav me-auto mb-2 mb-lg-0">
            <div class="header-logo-search">
              <!-- searching input -->
              <div class="header-search">
                <img src="<?php echo $treks_src; ?>/assets/img/header_search.svg" alt="svg" />
                <input placeholder="Search" />
              </div>
            </div>
          </div>
          <div class="d-flex" role="search">
            <div class="header-notification-user">
              <!-- notification -->
              <div class="header-notification">
                <img
                  src="<?php echo $treks_src; ?>/assets/img/header_bell-notification.svg"
                  alt="svg"
                />
              </div>
              <!-- user detail & Image  -->
              <div class="header-user">
                <!-- User Avatar -->
                <div class="user-avatar">
                  <img src="<?php echo $treks_src; ?>/assets/img/header_avatar.svg" alt="svg" />
                </div>
                <!-- User short detail -->
                <div class="user-detail">
                  <span class="user-detail-name">Kristin Watson</span>
                  <span>Science teacher</span>
                </div>
                <!-- Arrow for open menu -->
                <div class="user-options">
                  <img src="<?php echo $treks_src; ?>/assets/img/header_arrow open.svg" alt="svg" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Basic Container -->
    <section class="main-container">
      <!-- Nav Section -->
      <nav class="nav-section">
        <ul>
          <li>
            <img src="<?php echo $treks_src; ?>/assets/img/nav_dashboard-dots_gray.svg" />
            <a href="<?php echo site_url("dashboard") ?>">Dashboard</a>
          </li>
          <li class="nav-section-selected">
            <img src="<?php echo $treks_src; ?>/assets/img/nav_treks_selected.svg" />
            <a href="/">TREKs</a>
          </li>
          <li>
            <img src="<?php echo $treks_src; ?>/assets/img/nav_students.svg" />
            <a href="/">Students</a>
          </li>
          <li>
            <img src="<?php echo $treks_src; ?>/assets/img/nav_reports.svg" />
            <a href="/">Reports</a>
          </li>
        </ul>
      </nav>

      <!-- My TREKs breadcrumbs -->
      <section class="my-trk-bc-section">
        <div class="my-trk-bc-section-div">
          <!-- breadcrumbs -->
          <img class="bc-img-1" src="<?php echo $treks_src; ?>/assets/img/bc_img.svg" />
          <p>My TREKs</p>
          <img class="bc-img-2" src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
          <p><?php the_title(); ?></p>
        </div>
      </section>
      <!-- My TREKs Detail -->
      <section class="my-trk-detail-section">
        <div class="my-trk-detail-section-div">
          <!-- TREKs image  -->
          <div class="my-trk-detail-img">
            <!-- <img src="<?php //echo $treks_src; ?>/assets/img/tr_main.png" /> -->
            <?php the_post_thumbnail('medium', array( 'class' => 'rounded' )); ?>
          </div>
          <!-- TREKs detail -->
          <div class="my-trk-detail-prep">
            <!-- Title -->
            <div class="detail-prep-title">
              <h2><?php the_title(); ?></h2>
            </div>
            <!-- Description -->
            <div class="detail-prep-desc">
				      <p><?php echo $post->post_content; ?></p>
            </div>

            <!-- Navigation -->
            <div class="detail-prep-tags">
              <?php
                global $wpdb;
                $trek_sections = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}trek_sections WHERE trek_id={$post->ID}");
                if ( $trek_sections ) {
                  foreach ( $trek_sections as $trek_section ) {
                    $button_style = strtolower($trek_section->title);
                    $defined_button_styles = ['overview', 'recall', 'apply'];
                    $button_style = in_array($button_style, $defined_button_styles) ? $button_style : 'pa';
                    $button_styles[trim($trek_section->title)] = "$button_style-poly-body";
              ?>
                  <!-- Navigation Button -->
                  <a href="#<?php echo implode('_', explode(' ', $trek_section->title));?>" class="trek-section-nav-anchor"> 
                    <div class="tags-body <?php echo $button_style; ?>-poly-body">
                      <div class="tags-body-polygon">
                        <span><?php echo substr($trek_section->title, 0, 1);?></span>
                      </div>
                      <div class="tags-body-detail">
                        <span><?php echo $trek_section->title;?></span>
                      </div>
                    </div>
                  </a>
              <?php
                    }
                }
              ?>
            </div>
          </div>
        </div>
      </section>

      <!-- TREKs assigned to -->
      <section class="trk-assign-section">
        <div class="trk-assign-section-div">
          <p>TREKs assigned to</p>
          <a href="#">0 Students</a>
        </div>
      </section>
      <?php 
        if ( $trek_sections ) {
          foreach ( $trek_sections as $trek_section ) {
      ?>
            <section class="central-cncpt-section">
              <!-- section heading -->
              <div class="trek-main-heading-wrapper">
                <h1 class="trek-main-heading" id="<?php echo implode('_', explode(' ', $trek_section->title));?>"><?php echo $trek_section->title;?></h1>
                <a href="#" class="trek-main-heading-top-link">&uarr;Top</a>
              </div>
              
              <!-- digital journal link -->
              <?php
                $digital_journal_link = null;
                foreach($lessons as $lesson){ if (trim($trek_section->title) === trim($lesson->post_title)) { $digital_journal_link = get_permalink($lesson->ID); }; }
              ?>

              <div class="digital-student-journal-section my-trk-detail-section-div">
                <div class="detail-prep-tags">
                  <a href="<?php echo $digital_journal_link ?>" target="_blank" class="trek-section-nav-anchor"> 
                    <div class="digital-student-journal-btn tags-body <?php echo $button_styles[trim($trek_section->title)]; ?>">
                      <div class="tags-body-detail">
                        <span>Digital Student Journal</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <?php echo stripslashes($trek_section->content);?>

            </section>    
      <?php
          }
        }
      ?>
      <!-- Central Concepts  -->
      <!-- <section class="central-cncpt-section">
        <div class="central-cncpt-section-div">
          <h3 class="text-style-heading-s2-cm">Central Concepts</h3>
          <div class="central-cncpt-list-div">
            <ul>
              <li class="text-style-prep-cm">
                All of life depends on basic needs including food, shelter, air,
                and space for habitat.
              </li>
              <li class="text-style-prep-cm">
                All living organisms interact with other living and nonliving
                parts of their ecosystems.
              </li>
              <li class="text-style-prep-cm">
                Living organisms rely on this integration of living and
                nonliving components to grow and reproduce.
              </li>
            </ul>
          </div>
        </div>
      </section>
 -->
      <!--  Central Concepts  4 topic detail -->
      <!-- <section class="central-topic-section">
        <div class="central-topic-section-div">
          
          <div class="central-topic-each-list">
            <div class="list-heading">
              <h4 class="text-style-heading-s2-cm recall-cc-heading">Recall</h4>
            </div>

            <div class="list-detail">
              <h5>Review: “What Do Living Things Need?”</h5>
              <p class="text-style-prep-cm">
                Students recall prior knowledge of the basic needs of all
                organisms in their environment with transparent thinking.
              </p>
            </div>
          </div>
         
          <div class="central-topic-each-list">
            <div class="list-heading">
              <h4 class="text-style-heading-s2-cm pa-cc-heading">Practice A</h4>
            </div>
            <div class="list-detail">
              <h5>Investigation: Rain & Shine</h5>
              <p class="text-style-prep-cm">
                Students collect and analyze data in a simulated comparative
                investigation to answer the research question, “How does
                sunlight and water affect plant growth?” An optional STEAM
                extension on creating non-linguistic visualizations of data.
              </p>
            </div>
          </div>
         
          <div class="central-topic-each-list">
            <div class="list-heading">
              <h4 class="text-style-heading-s2-cm pb-cc-heading">Practice B</h4>
            </div>
            <div class="list-detail">
              <h5>In the Field: Billie the Birdwatcher</h5>
              <p class="text-style-prep-cm">
                Students actively read and reflect as field scientists, support
                a second hand field investigation with Billie the Birdwatcher,
                and identify appropriate habitats for three North American bird
                species.
              </p>
            </div>
          </div>
          
          <div class="central-topic-each-list">
            <div class="list-heading">
              <h4 class="text-style-heading-s2-cm apply-cc-heading">Apply</h4>
            </div>
            <div class="list-detail">
              <h5>Mission: The Great Turtle Rescue</h5>
              <p class="text-style-prep-cm">
                Students embark on a task-based problem-solving real-world
                scenario with a mission for a wildlife release in a nearby
                wildlife refuge using habitat maps adapted from Brazoria
                National Wildlife Refuge of coastal eastern Texas.
              </p>
            </div>
          </div>
        </div>
      </section>
 -->
      <!--Integrated Standards Alignment  -->
      <!-- <section class="intg-stand-section">
        <div class="intg-stand-section-div">
          
          <div class="intg-stand-heading">
            <h3 class="text-style-heading-s2-cm">
              Integrated Standards Alignment
            </h3>
          </div>

          
          <div class="intg-stand-detail-list">
            <div class="intg-stand-list">
              <h4>Looking Behind:</h4>
              <p class="text-style-prep-cm">Grades 3 and 4</p>
            </div>
            
            <div class="intg-stand-detail">
              <ul class="main-detail">
                <li class="text-style-prep-cm">Science</li>
              </ul>
              <ul class="sub-detal">
                <li class="text-style-prep-cm">
                  3.9A Investigate that most producers need sunlight, water, and
                  carbon dioxide to make their own food, while consumers are
                  dependent on other organisms for food.
                </li>
                <li class="text-style-prep-cm">
                  3.9A Investigate that most producers need sunlight, water, and
                  carbon dioxide to make their own food, while consumers are
                  dependent on other organisms for food.
                </li>
              </ul>
            </div>
          </div>
          
          <div class="intg-stand-detail-list">
            <div class="intg-stand-list">
              <h4>Looking Ahead::</h4>
              <p class="text-style-prep-cm">Grades 3 and 4</p>
            </div>
           
            <div class="intg-stand-detail">
              <ul class="main-detail">
                <li class="text-style-prep-cm">Science</li>
              </ul>
              <ul class="sub-detal">
                <li class="text-style-prep-cm">
                  6.12E Describe biotic and abiotic parts of an ecosystem in
                  which organisms interact.
                </li>
                <li class="text-style-prep-cm">
                  7.10A Observe and describe how different environments,
                  including microhabitats in schoolyards and biomes, support
                  different varieties of organisms.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section> -->
    </section>

    <script
      src="https://code.jquery.com/jquery-3.6.3.js"
      integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
      crossorigin="anonymous"
    ></script>
    <script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    
    <script type="text/javascript">

      jQuery(document).ready(function() {
        
        var trekUrl = "<?php echo get_permalink($post->ID); ?>";

        // set copy icons to links
        jQuery("a").each(function() {
          if ($(this).attr("id")) {
            // prevent link action from navigation if it is #
            if ($(this).attr("href") === "#") {
              $(this).on('click', function(e) {
                e.preventDefault();
              });
            }

            $( '<a class="copy-anchor" href="#' + $(this).attr("id") + '" id="' + $(this).attr("id") + '_Copy_Link"><img class="copy-anchor-icon-img" src="<?php echo $treks_src; ?>/assets/img/link_icon.png" width="20" height="20" /></a>' ).insertAfter( $(this) );
            jQuery('#' + jQuery(this).attr("id") + '_Copy_Link').on('click', function(e) {
              e.preventDefault();

              var targetLinkArr = jQuery(this).attr("href").split('#');
              targetLinkId = targetLinkArr.length === 2 ? targetLinkArr[1] : null;
              if (targetLinkId) {
                var trekTargetUrl = trekUrl + "#" + targetLinkId;
                console.log("copy -- ", trekTargetUrl);

                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', trekTargetUrl);
                    e.preventDefault();
                }, true);

                document.execCommand('copy');  
              }
            })
          }
        });

      });


    </script>
  </body>
</html>
<?php endwhile; ?>