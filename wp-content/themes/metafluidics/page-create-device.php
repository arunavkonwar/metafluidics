<?php
/**
 * Template Name: Create Device Page
 * The template for displaying the create device page.
 *
 * @package metafluidics
 */

get_header(); ?>


	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				$billOfMaterialsURL = 'http://metafluidics-stage.s3.amazonaws.com/wp-content/uploads/2015/10/14223139/Bill-of-Materials.xlsx';

				// if not logged in, redirect to login
				if ( !is_user_logged_in() ) {
				  wp_redirect( wp_login_url() );
				}
				else {

				  if ( isset($_POST['device-name']) ) {

				    $nameText = sanitize_text_field( $_POST['device-name'] );

				    if ( isset( $_POST['device-description'] ) ) {
				      $descriptionText = sanitize_text_field( $_POST['device-description'] );
				    }


				    // edit arunav step 2
				    if ( isset( $_POST['device-license'] ) ) {
				      $deviceLicense = $_POST['device-license'];
				    }




						if ( isset( $_POST['is-draft'] ) && $_POST['is-draft'] === 'true' ) {
							$postStatus = 'draft';
						}
						else {
							$postStatus = 'publish';
						}

				    // save tags
				    function getMetafluidicsTerms( $taxonomy, $terms) {
				      $termsArray = [];
				      foreach ( $terms as $term ) {
				        if ( is_numeric($term) ) {
				          array_push($termsArray, $term);
				        }
				        else {
				          $termExists = term_exists( $term, $taxonomy );

				          if ( isset( $termExists['term_id'] ) ) {
				            array_push($termsArray, $termExists['term_id']);
				          }
				          else {
				            $newTerm = wp_insert_term( $term, $taxonomy );

				            if ( !is_wp_error($newTerm) ) {
				              array_push($termsArray, $newTerm['term_taxonomy_id']);
				            }
				            else {
				              wp_die( $newTerm->get_error_message() );
				            }
				          }
				        }
				      }

				      return $termsArray;
				    }

				    $keywordsArray = [];
				    $technologyArray = [];
				    $hardwareArray = [];
				    $materialsArray = [];

				    if ( isset( $_POST['keywords'] ) ) {
				      $keywordsTags = $_POST['keywords'];
				      $keywordsArray = getMetafluidicsTerms( 'metafluidics_device_keywords', $keywordsTags );
				    }
				    if ( isset( $_POST['technology'] ) ) {
				      $technologyTags = $_POST['technology'];
				      $technologyArray = getMetafluidicsTerms( 'metafluidics_device_technology', $technologyTags );
				    }
				    if ( isset( $_POST['hardware'] ) ) {
				      $hardwareTags = $_POST['hardware'];
				      $hardwareArray = getMetafluidicsTerms( 'metafluidics_device_hardware', $hardwareTags );
				    }
				    if ( isset( $_POST['materials'] ) ) {
				      $materialsTags = $_POST['materials'];
				      $materialsArray = getMetafluidicsTerms( 'metafluidics_device_material', $materialsTags );
				    }

				    // create post
				    // edit arunav step 3
				    $post = array(
				      'post_content'   => $descriptionText,
				      'license'			=> $deviceLicense,
				      'post_title'     => $nameText,
				      'comment_status' => 'open',
				      'post_status'    => $postStatus,
				      'post_type'      => 'metafluidics_device',
				      'tax_input'      => array(
				                            'metafluidics_device_keywords' => $keywordsArray,
				                            'metafluidics_device_technology' => $technologyArray,
				                            'metafluidics_device_hardware' => $hardwareArray,
				                            'metafluidics_device_material' => $materialsArray
				                          ),
				    );

				    $newPostID = wp_insert_post( $post );

				    if ( $newPostID === 0 ) {
				      echo 'There was an error in creating your device. Try again or contact the administrator for help.';
				    }
				    else {

				      // post successfully created! add custom meta data
				      update_post_meta( $newPostID, 'metafluidics-device-description', $descriptionText );

				      // edit arunav step 4
				      update_post_meta( $newPostID, 'metafluidics-license', $deviceLicense );

							$metaArray = array('thumbnail','images','design-files','software','bill-of-materials','publications','tutorials','remixed','parts');

							foreach( $metaArray as $meta ) {
								if ( isset( $_POST['metafluidics-device-' . $meta] ) ) {
									$value = $_POST['metafluidics-device-' . $meta];
									update_post_meta( $newPostID, 'metafluidics-device-' . $meta, $value );
								}
							}

				      $postLink = get_permalink( $newPostID );

							if ( $postStatus === 'publish' ) {
								echo 'You successfully created your device! <a href="' . $postLink . '">Click here to check it out!</a>';
							}
							else {
								echo 'You successfully created a draft! <a href="' . $postLink . '">Click here to preview it!</a>';
							}
				    }

				  } // end "if device creation confirmation page"
				  else {

				    // create device page
				    global $current_user;
				    get_currentuserinfo();
				    wp_enqueue_media();

				    require_once(ABSPATH . 'wp-admin/includes/image.php');
				    require_once(ABSPATH . 'wp-admin/includes/file.php');
				    require_once(ABSPATH . 'wp-admin/includes/media.php');

				    $mediaUploadLink = esc_url( get_upload_iframe_src() );

				    $tagArgs = array(
				     'orderby' => 'count',
				     'order' => 'DESC',
				     'hide_empty' => false,
				    );

				    $tagMax = 15;

				?>

				    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				    	<div class="entry-content">

								<p class="intro">Add a new fluidic part. This can be a device or other piece of hardware (e.g. a fluidic controller).</p>

				      	<form id="create-device" method="post">

				        	<!-- DEVICE NAME -->

				        	<div class="postbox required" >
				          	<label for="name" class="name">What is your part called?
				          	  <input type="text" name="device-name" id="device-name" placeholder="Device name" />
				          	  <p class="tip">Tip: be as concise as possible</p>
				          	</label>
				        	</div>

				        	<!-- DEVICE DESCRIPTION -->

				        	<div class="postbox required">
				          	<label for="description" class="description">What does your part do?
				          	  <textarea name="device-description" id="device-description" placeholder="Write a short summary of the purpose of your part, what functions it performs, and who you think will want to use it." maxlength="500"></textarea>
				          	  <p class="tip">Short and sweet - think of this as an overview or abstract.</p>
				          	</label>
				        	</div>


				        	<!-- DEVICE TAGS - KEYWORDS -->

				        	<div class="postbox">
				          	<label for="keywords" class="keywords tags">What are some keywords to describe the purpose of your part and what field of inquiry it impacts?
				          	  <p class="tip">Select as many tags as applicable, or create your own.</p>

				          	  <?php
				                $keywordsArray = get_terms('metafluidics_device_keywords', $tagArgs);
				                $keywordsMax = ( count( $keywordsArray ) < $tagMax ) ? count($keywordsArray) : $tagMax;
				              ?>

				          	  <ul>
				            	  <?php
				              	  for ( $i = 0; $i < $keywordsMax; $i++ ) {
				                    // display existing keywords
				                    echo '<li><label><input type="checkbox" name="keywords[]" value="' . $keywordsArray[$i]->term_id . '"> <span>' . $keywordsArray[$i]->name . '</span></li>';
				                  }
				                ?>
				          	  </ul>

				          	  <div class="new-tags keywords">
				            	  <div class="add-tag keywords">
				              	  <button role="button">+</button> <input type="text" placeholder="Add a keyword" value="" />
				            	  </div>
				          	  </div>

				          	</label>
				        	</div>

				        	<!-- DEVICE TAGS - MATERIALS -->

				        	<div class="postbox">
				          	<label for="materials" class="materials tags">What material(s) is your part composed of?
				          	  <p class="tip">Select as many tags as applicable, or create your own.</p>

				          	  <?php
				                $materialsArray = get_terms('metafluidics_device_material', $tagArgs);
				                $materialsMax = ( count( $materialsArray ) < $tagMax ) ? count($materialsArray) : $tagMax;
				              ?>

				          	  <ul id="device-tags-materials">
				            	  <?php
				              	  for ( $i = 0; $i < $materialsMax; $i++ ) {
				                    // display existing materials
				                    echo '<li><label><input type="checkbox" name="materials[]" value="' . $materialsArray[$i]->term_id . '"> <span>' . $materialsArray[$i]->name . '</span></li>';
				                  }
				                ?>
				          	  </ul>


				          	  <div class="new-tags materials">
				            	  <div class="add-tag materials">
				              	  <button role="button">+</button> <input type="text" placeholder="Add a keyword" value="" />
				            	  </div>
				          	  </div>

				          	</label>
				        	</div>

				        	 <!-- DEVICE TAGS - FABRICATION technology -->

				        	<div class="postbox">
				          	<label for="technology" class="technology tags">What fabrication technologies are required to make your part?
				          	  <p class="tip">Select as many tags as applicable, or create your own.</p>

				          	  <?php
				                $technologyArray = get_terms('metafluidics_device_technology', $tagArgs);
				                $technologyMax = ( count( $technologyArray ) < $tagMax ) ? count($technologyArray) : $tagMax;
				              ?>

				          	  <ul id="device-tags-fabrication-technologies">
				            	  <?php
				              	  for ( $i = 0; $i < $technologyMax; $i++ ) {
				                    // display existing technology
				                    echo '<li><label><input type="checkbox" name="technology[]" value="' . $technologyArray[$i]->term_id . '"> <span>' . $technologyArray[$i]->name . '</span></li>';
				                  }
				                ?>
				          	  </ul>

				          	  <div class="new-tags technology">
				            	  <div class="add-tag technology">
				              	  <button role="button">+</button> <input type="text" placeholder="Add a keyword" value="" />
				            	  </div>
				          	  </div>

				          	</label>
				        	</div>

				          <!-- DEVICE TAGS - HARDWARE -->

				          <div class="postbox">
				          	<label for="hardware" class="hardware tags">What supporting hardware (if any) is required to operate your part?
				          	  <p class="tip">Select as many tags as applicable, or create your own.</p>

				          	  <?php
				                $hardwareArray = get_terms('metafluidics_device_hardware', $tagArgs);
				                $hardwareMax = ( count( $hardwareArray ) < $tagMax ) ? count($hardwareArray) : $tagMax;
				              ?>

				          	  <ul id="device-tag-hardware">
				            	  <?php
				              	  for ( $i = 0; $i < $hardwareMax; $i++ ) {
				                    // display existing hardware
				                    echo '<li><label><input type="checkbox" name="hardware[]" value="' . $hardwareArray[$i]->term_id . '"> <span>' . $hardwareArray[$i]->name . '</span></li>';
				                  }
				                ?>
				          	  </ul>

				          	  <div class="new-tags hardware">
				            	  <div class="add-tag hardware">
				              	  <button role="button">+</button> <input type="text" placeholder="Add a keyword" value="" />
				            	  </div>
				          	  </div>

				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - THUMBNAIL -->

				        	<div id="metafluidics-device-thumbnail" class="postbox required">
				          	<label for="thumbnail" class="thumbnail">Please upload the part thumbnail
				          	  <p class="tip">This is the image that will be shown when the part is displayed on the homepage and other galleries.</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-thumbnail">Upload</a>

				          	  <div class="uploaded-items"></div>
				          	  <input class="upload-input" id="device-thumbnail" name="metafluidics-device-thumbnail" type="hidden" value="" />

				          	  <p class="tip sub">The file should be a .gif, .jpeg or .png file and no larger than 20MB</p>
				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - IMAGES -->

				        	<div id="metafluidics-device-images" class="postbox">
				          	<label for="images" class="images">Now, upload your set of part visuals
				          	  <p class="tip">These are the images that will visualize your part, so they should be high quality whenever possible.</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-images">Upload</a>

				          	  <div class="uploaded-items"></div>
				          	  <input class="upload-input" id="device-images" name="metafluidics-device-images" type="hidden" value="" />

				          	  <p class="tip sub">The file should be a .gif, .jpeg or .png file and no larger than 20MB</p>
				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - DESIGN FILES -->

				        	<div id="metafluidics-device-design-files" class="postbox required">
				          	<label for="design" class="design">Share your design files
				          	  <p class="tip">These are the digital design files for your part!</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-design">Upload</a>

				          	  <ul class="uploaded-items"></ul>
				          	  <input class="upload-input" id="device-design-files" name="metafluidics-device-design-files" type="hidden" value="" />

				          	  <p class="tip sub">The file should be a .zip file and no larger than 20MB</p>
				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - BILL OF MATERIALS -->

				        	<div id="metafluidics-device-bill-of-materials" class="postbox">
				          	<label for="bill" class="bill">Next, upload your Bill of Materials
				          	  <p class="tip">This should be a complete list of all materials that someone might need to make your part. Please fill out the <a href="<?php echo $billOfMaterialsURL; ?>">Bill of Materials spreadsheet</a> and upload.</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-bill">Upload</a>

				          	  <ul class="uploaded-items"></ul>
				          	  <input class="upload-input" id="device-bill-of-materials" name="metafluidics-device-bill-of-materials" type="hidden" value="" />

				          	  <p class="tip sub"><a href="<?php echo $billOfMaterialsURL; ?>">Click here to download the Bill of Materials spreadsheet.</a></p>
				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - SOFTWARE -->

				          <div id="metafluidics-device-software" class="postbox">
				          	<label for="software" class="software">Next, upload any Software
				          	  <p class="tip">This is the compressed file of any software required to operate your part.</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-software">Upload</a>

				          	  <ul class="uploaded-items"></ul>
				          	  <input class="upload-input" id="device-software" name="metafluidics-device-software" type="hidden" value="" />

				          	  <p class="tip sub">The file should be .zip file and no larger than 20MB</p>
				          	</label>
				        	</div>

				        	<!-- DEVICE UPLOADS - INSTRUCTIONS -->

				          <div id="metafluidics-device-build-instructions" class="postbox">
				          	<label for="instructions" class="instructions">On to the juicy stuff, upload your instructions
				          	  <p class="tip">This should be a detailed how-to guide for making your part. Upload here any text-based instructions.</p>

				          	  <a href="<?php echo $mediaUploadLink; ?>" class="button upload-items" id="upload-instructions">Upload</a>

				          	  <ul class="uploaded-items"></ul>
				          	  <input class="upload-input" id="device-build-instructions" name="metafluidics-device-build-instructions" type="hidden" value="" />

				          	  <p class="tip sub">The file should be a .doc or .txt file and no larger than 20MB</p>
				          	</label>
				        	</div>

  								<!-- DEVICE URLS - PUBLICATIONS -->

									<div id="metafluidics-device-publications" class="postbox">
										<label for="publications" class="publications urls cf">Is your part published? If so, where?
											<p class="tip">Add as many URLs where your part has been published.</p>

											<ul id="device-urls-publications" class="cf">
												<li>
													<input type="text" class="url-input name" placeholder="Title">
													<input type="text" class="url-input url" placeholder="URL">
													<button role="button" class="url-delete">X</button>
												</li>
											</ul>

											<input type="hidden" class="url-value" name="metafluidics-device-publications" />
											<button role="button" class="button url-add">+ Add Another URL</button>
										</label>
									</div>

									<!-- DEVICE URLS - TUTORIALS -->
									<div id="metafluidics-device-tutorials" class="postbox">
										<label for="tutorials" class="tutorials urls cf">Would you like to share a tutorial on how to make or use your part?
											<p class="tip">Enter as many URLs for tutorials (e.g. vimeo, youtube, instructables) and give them titles.</p>

											<ul id="device-urls-tutorials">
												<li>
													<input type="text" class="url-input name" placeholder="Title">
													<input type="text" class="url-input url" placeholder="URL">
													<button role="button" class="url-delete">X</button>
												</li>
											</ul>

											<input type="hidden" class="url-value" name="metafluidics-device-tutorials" />
											<button role="button" class="button url-add">+ Add Another URL</button>
										</label>
									</div>

									<!-- DEVICE URLS - REMIXED -->
									<div id="metafluidics-device-remixed" class="postbox">
										<label for="remixed" class="remixed urls cf">Is your part remixed from another part in Metafluidics? If so, which one(s)?
											<p class="tip">Add URLs for each part you have remixed.</p>

											<ul id="device-urls-remixed">
												<li>
													<input type="text" class="url-input name" placeholder="Title">
													<input type="text" class="url-input url" placeholder="URL">
													<button role="button" class="url-delete">X</button>
												</li>
											</ul>

											<input type="hidden" class="url-value" name="metafluidics-device-remixed" />
											<button role="button" class="button url-add">+ Add Another URL</button>
										</label>
									</div>

									<!-- DEVICE URLS - PARTS -->
									<div id="metafluidics-device-parts" class="postbox">
										<label for="parts" class="parts urls cf">Is your part designed to work in conjunction with other parts? If so, which one(s)?
											<p class="tip">Add URLs for additional parts required to operate the fluidic system.</p>

											<ul id="device-urls-parts">
												<li>
													<input type="text" class="url-input name" placeholder="Title">
													<input type="text" class="url-input url" placeholder="URL">
													<button role="button" class="url-delete">X</button>
												</li>
											</ul>

											<input type="hidden" class="url-value" name="metafluidics-device-parts" />
											<button role="button" class="button url-add">+ Add Another URL</button>
										</label>
									</div>

							<!-- LICENSE -->
				        	<!--edit arunav step 1-->
				        	<div>
				          	<label for="description" class="description">Type of license? <br><br>
								  <select id="device-license" name="device-license">
								    <option value="Attribution: CC BY">Attribution: CC BY</option>
								    <option value="Attribution-ShareAlike: CC BY-SA">Attribution-ShareAlike: CC BY-SA</option>
								    <option value="Attribution-NoDerivs: CC BY-ND">Attribution-NoDerivs: CC BY-ND</option>
								    <option value="Attribution-NonCommerical: CC BY-NC">Attribution-NonCommerical: CC BY-NC</option>
								    <option value="Attribution-NonCommercial-ShareAlike: CC BY-NC-SA">Attribution-NonCommercial-ShareAlike: CC BY-NC-SA</option>
								    <option value="Attribution-NonCommercial-NoDerivs: CC BY-NC-ND">Attribution-NonCommercial-NoDerivs: CC BY-NC-ND</option>
								  </select>	
								  <p class="tip sub">If you have questions about which license best suits your preference in open sourcing your device, please refer to this site for more information:

<a href="https://creativecommons.org/licenses/" target="_blank">https://creativecommons.org/licenses/</a> </p>
							</label>					          
				        	</div>

				        	<!-- DEVICE CREATE SUBMISSION -->

				        	<div class="postbox">
				          	<label for="submit" class="submit">Phew, you made it to the end!
				          	  <p class="tip">Once you click this button, your device will be created. <br />
				            	  Fear not, you can always edit the device in your dashboard.</p>

				          	  <input type="submit" name="submit" value="Add Your Part" class="button submit" id="submit-device" />

											<p>or</p>

											<input type="hidden" name="is-draft" id="is-draft" />
											<input type="submit" name="submit-draft" value="Save Part as Draft" class="button submit draft" id="submit-draft" />

				          	  <div id="alert-message"></div>
				          	</label>
				        	</div>
				        </form>

				    	</div><!-- .entry-content -->

				    </article><!-- #post-## -->

				<?php
				  } // end else create device page
				} // end if logged in
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
