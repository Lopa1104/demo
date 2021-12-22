<?php
/**
 * Setup Child Theme Styles
 */
function built_by_hello_enqueue_styles() {
	wp_localize_script( 'ajax_url', 'ajax_url', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
	wp_enqueue_style( 'built_by_hello-style', get_stylesheet_directory_uri() . '/style.css', false, date('Ymdhis') );
	wp_enqueue_style( 'built_maincss', get_stylesheet_directory_uri() . '/css/main.css', false, date('Ymdhis') );
	wp_enqueue_style( 'slick_hello-style', get_stylesheet_directory_uri() . '/css/slick.min.css', false, '1.0.0' );
	wp_enqueue_script( 'script-name', get_stylesheet_directory_uri() . '/js/slick.min.js', array(), '1.0.0', true );
	
	wp_enqueue_style( 'range_hello-style', get_stylesheet_directory_uri() . '/css/ion.rangeSlider.min.css', false, '1.0.0' );
	wp_enqueue_script( 'range-name', get_stylesheet_directory_uri() . '/js/ion.rangeSlider.min.js', array(), '1.0.0', true );
	
	wp_enqueue_script( 'main-js-name', get_stylesheet_directory_uri() . '/js/main.js', array(), date('Ymdhis'), true );
}
add_action( 'wp_enqueue_scripts', 'built_by_hello_enqueue_styles', 20 );





add_filter( 'submit_job_form_fields', 'frontend_add_salary_field' );
/**
 * Submit job form.
 */
function frontend_add_salary_field( $fields ) {
	$fields['job']['job_salary'] = array(
		'label'       => __( 'Salary ($)', 'job_manager' ),
		'type'        => 'text',
		'required'    => true,
		'placeholder' => 'e.g. 20000',
		'priority'    => 7
	);
	return $fields;
}

add_filter( 'job_manager_job_listing_data_fields', 'admin_add_salary_field' );
/**
 * Filter job form.
 */
function admin_add_salary_field( $fields ) {
	$fields['_job_salary'] = array(
		'label'       => __( 'Salary ($)', 'job_manager' ),
		'type'        => 'text',
		'placeholder' => 'e.g. 20000',
		'description' => ''
	);
	return $fields;
}

add_action( 'single_job_listing_meta_end', 'display_job_salary_data' );
/**
 * Single job meta data.
 */
function display_job_salary_data() {
	global $post;

	$salary = get_post_meta( $post->ID, '_job_salary', true );

	if ( $salary ) {
		echo '<li>' . __( 'Salary:' ) . ' $' . esc_html( $salary ) . '</li>';
	}
}

add_filter( 'wpjm_get_job_listing_structured_data', 'add_basesalary_data' );
/**
 * Save job salary format.
 */
function add_basesalary_data( $data ) {
	global $post;
	
	$data['baseSalary']                      = array();
	$data['baseSalary']['@type']             = 'MonetaryAmount';
	$data['baseSalary']['currency']          = 'USD';
	$data['baseSalary']['value']             = array();
	$data['baseSalary']['value']['@type']    = 'QuantitativeValue';
	$data['baseSalary']['value']['value'] 	 = get_post_meta( $post->ID, '_job_salary', true );
	$data['baseSalary']['value']['unitText'] = 'YEAR';
	
	return $data;
}

add_action( 'job_manager_job_filters_search_jobs_end', 'filter_by_salary_field' );
/**
 * Custom query to filter job using roles, experoences etc.
 */
function filter_by_salary_field() {
	$terms = get_terms( array(
		'taxonomy' => 'jobroles',
		'hide_empty' => true,
	) );
	
	$termexperiences = get_terms( array(
		'taxonomy' => 'jobexperiences',
		'hide_empty' => true,
	) );
	?>
    
    <div class="search_salary ">
		<label class="labelheading" for="search_range"><?php _e( 'Salary range', 'wp-job-manager' ); ?></label>
        
        <div class="pleft searchsalary">
        	<label for="search_min"><?php _e( 'Min, USD', 'wp-job-manager' ); ?></label>
        	<input name="salarymin" type="number"  value="1000" class="from"/>
        </div>
        
        <div class="pright searchsalary">
        	<label for="search_max"><?php _e( 'Max, USD', 'wp-job-manager' ); ?></label>
        	<input name="salarymax" type="number"  value="9999000" class="to"/>
        </div>
        
        <div class="job_types">
            <input type="text" class="js-range-slider" name="my_range" value=""
                data-skin="round"
                data-type="double"
                data-min="0"
                data-max="9999000"
                data-grid="false"
            />
    	</div>
        <!--<input name="salarymin" type="number" maxlength="4" value="1000" class="from"/>
        <input name="salarymax" type="number" maxlength="4" value="9999000" class="to"/>

		<select  class="job-manager-filter">
			<option value=""><?php _e( 'Any Salary', 'wp-job-manager' ); ?></option>
			<option value="upto20"><?php _e( 'Up to $20,000', 'wp-job-manager' ); ?></option>
			<option value="20000-40000"><?php _e( '$20,000 to $40,000', 'wp-job-manager' ); ?></option>
			<option value="40000-60000"><?php _e( '$40,000 to $60,000', 'wp-job-manager' ); ?></option>
			<option value="over60"><?php _e( '$60,000+', 'wp-job-manager' ); ?></option>
		</select>-->
	</div>
	<div class="search_jobroles job-manager-filter">
		<label class="labelheading" for="search_jobroles"><?php _e( 'Role level', 'wp-job-manager' ); ?></label>
        <ul>
        	<?php foreach ( $terms as $term ) {
            	echo '<li><input type="checkbox" name="filter_by_role[]" value="' . esc_html($term->slug) . '" checked="checked" id="job_"' . esc_html($term->slug) . '"> ' . $term->name . '
</li>';
			}
			?>
		</ul>
	</div>
    <div class="search_jobexperiences job-manager-filter">
		<label class="labelheading" for="search_jobexperiences"><?php _e( 'Marketing automation experience', 'wp-job-manager' ); ?></label>
        <ul>
        	<li><input type="checkbox" name="" value="" checked="checked" id="job_any_exp"> Any</li>
        	<?php foreach ( $termexperiences as $termexperience ) {
            	echo '<li><input type="checkbox" class="search_checkbox" name="filter_by_experiences[]" value="' . $termexperience->slug . '" id="job_"' . $termexperience->slug . '"> ' . $termexperience->name . '
</li>';
			}
		?>
		</ul>
	</div>
	<?php
}

add_filter( 'job_manager_get_listings', 'filter_by_salary_field_query_args', 10, 2 );
/**
 * This code gets your posted field and modifies the job search query
 */
function filter_by_salary_field_query_args( $query_args, $args ) {
	if ( isset( $_POST['form_data'] ) ) {
		parse_str( $_POST['form_data'], $form_data );

		// If this is set, we are filtering by salary.
		if ( ! empty( $form_data['filter_by_salary'] ) ) {
			$selected_range = sanitize_text_field( $form_data['filter_by_salary'] );
			switch ( $selected_range ) {
				case 'upto20':
					$query_args['meta_query'][] = array(
						'key'     => '_job_salary',
						'value'   => '20000',
						'compare' => '<',
						'type'    => 'NUMERIC',
					);
					break;
				case 'over60':
					$query_args['meta_query'][] = array(
						'key'     => '_job_salary',
						'value'   => '60000',
						'compare' => '>=',
						'type'    => 'NUMERIC',
					);
					break;
				default:
					$query_args['meta_query'][] = array(
						'key'     => '_job_salary',
						'value'   => array_map( 'absint', explode( '-', $selected_range ) ),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					);
					break;
			}

			// This will show the 'reset' link.
			add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
		}
		
		if ( ! empty( $form_data['filter_by_role'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'jobroles',
				'field'    => 'slug',
				'terms'    => $form_data['filter_by_role'],
			];
		}
		
		if ( ! empty( $form_data['filter_by_experiences'] ) ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'jobexperiences',
				'field'    => 'slug',
				'terms'    => $form_data['filter_by_experiences'],
			];
		}
		
		if ( ! empty( $form_data['my_range'] ) ) {
			$my_range = sanitize_text_field( $form_data['my_range'] );	
			$query_args['meta_query'][] = array(
				'key'     => '_job_salary',
				'value'   => array_map( 'absint', explode( ';', $my_range ) ), //array($salarymin_range,$salarymax_range),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC'
			);
		}
		
	}
	return $query_args;
}

add_shortcode( 'hiringcompanies', 'hiringcompanies' );
/**
 * Static Companies slider.
 */
function hiringcompanies(){
	
	ob_start();
	?>
    <div class=" hrow elementor-container elementor-column-gap-default carousel">
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/1.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Dropbox</h3>
			<div class="composition">6 positions · UK, USA, Remote</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/2.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Shopify</h3>
			<div class="composition">4 positions · UK, USA, Remote</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/3.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>SAP</h3>
			<div class="composition">8 positions · AU, Brisbane</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/4.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Paypal</h3>
			<div class="composition">4 positions · NY, New York</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/5.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Zoom</h3>
			<div class="composition">3 positions · IL, Chicago</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/6.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Nvidia</h3>
			<div class="composition">12 positions · CA, Los Angeles</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/1.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Dropbox</h3>
			<div class="composition">6 positions · UK, USA, Remote</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
			<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/2.png';?>" width="" height="" alt="" /></div>
			<div class="compdetail">
			<h3>Shopify</h3>
			<div class="composition">4 positions · UK, USA, Remote</div>
			</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/3.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>SAP</h3>
                	<div class="composition">8 positions · AU, Brisbane</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/4.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Paypal</h3>
                	<div class="composition">4 positions · NY, New York</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/5.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Zoom</h3>
                	<div class="composition">3 positions · IL, Chicago</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/6.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Nvidia</h3>
                	<div class="composition">12 positions · CA, Los Angeles</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/1.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Dropbox</h3>
                	<div class="composition">6 positions · UK, USA, Remote</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/2.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Shopify</h3>
                	<div class="composition">4 positions · UK, USA, Remote</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/3.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>SAP</h3>
                	<div class="composition">8 positions · AU, Brisbane</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
			<div class="card-content">
				<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/4.png';?>" width="" height="" alt="" /></div>
				<div class="compdetail">
					<h3>Paypal</h3>
					<div class="composition">4 positions · NY, New York</div>
				</div>
			</div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/5.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Zoom</h3>
                	<div class="composition">3 positions · IL, Chicago</div>
                </div>
            </div>
        </div>
        
        <div class="card elementor-column elementor-col-20">
        	<div class="card-content">
            	<div class="comlogo"><img src="<?php echo get_stylesheet_directory_uri() . '/images/6.png';?>" width="" height="" alt="" /></div>
                <div class="compdetail">
                	<h3>Nvidia</h3>
                	<div class="composition">12 positions · CA, Los Angeles</div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
	$out2 = ob_get_contents();
	ob_end_clean();
	
	return $out2;
}


/**
 * Truncate a string but end with complete word.
 */
function truncate( $str, $len ) {
	$tail  = max( 0, $len-10 );
	$trunk = substr( $str, 0, $tail );
	$trunk .= strrev( preg_replace( '~^..+?[\s,:]\b|^...~', '...', strrev( substr( $str, $tail, $len-$tail ) ) ) );
	return $trunk;
}

/******************** Member Directory ****************************/

add_action( 'wp_ajax_nopriv_members_ajax_call', 'members_ajax_call' );
add_action( 'wp_ajax_members_ajax_call', 'members_ajax_call' );
/**
 * Ajax for members and filters.
 */
if ( !function_exists( 'members_ajax_call' ) ) {
    function members_ajax_call() {
		
 		$search_term 	  = ( ! empty( $_POST['search_term'] ) ) ? sanitize_text_field( $_POST['search_term'] ) : '';
		$current_page 	  = ( ! empty( $_POST['paged'] ) ) ? sanitize_text_field( $_POST['paged'] ) : 1;
		$categories 	  = ( ! empty( $_POST['category'] ) ) ? $_POST['category'] : '';
		$experiences 	  = ( ! empty( $_POST['experience'] ) ) ? $_POST['experience'] : '';
		$experience_years = ( ! empty( $_POST['experience_years'] ) ) ? $_POST['experience_years'] : '';
		$roles 			  = ( ! empty( $_POST['roles'] ) ) ? $_POST['roles'] : '';
		$skills  		  = ( ! empty( $_POST['skills'] ) ) ? $_POST['skills'] : '';
		
		$upload_dir   	  = wp_upload_dir();
		$users_per_page   = 10;
		
		$result = '';
		// WP_User_Query arguments
		$args = array (
			'order'			 => 'ASC',
			'orderby'		 => 'display_name',
			'posts_per_page' => max( 1, $users_per_page ),
			'number'		 => $users_per_page,
			'offset'		 => ( $current_page - 1 ) * $users_per_page,
		);
		
		if ( ! empty($search_term ) ){
			$args['search'] = esc_attr( $search_term ) . '*'; //'*'.
		}
		
		if ( ! empty( $categories ) || ! empty( $experiences ) || ! empty( $experience_years ) || ! empty( $roles ) || ! empty( $skills ) ) {
			$args['meta_query']['relation'] = 'OR';
		}
		
		if ( ! empty( $skills ) ) {
			
			foreach ( $skills as $skill ) {
				$args['meta_query'][] = array(
					'key'     => 'skills',
					'value'   => $skill,
					'compare' => 'LIKE'
				);
			}
		}
		
		if ( ! empty( $categories ) ) {
			
			foreach ( $categories as $category ) {
				$args['meta_query'][] = array(
					'key'     => 'category',
					'value'   => $category,
					'compare' => 'LIKE'
				);
			}
		}
		
		if ( ! empty( $experiences ) ) {
			foreach ( $experiences as $experience ) {
				$args['meta_query'][] = array(
					'key'     => 'experience',
					'value'   => $experience,
					'compare' => 'LIKE'
				);
			}
		}
		
		if ( ! empty( $roles ) ) {
			foreach ( $roles as $role ) {
				$args['meta_query'][] = array(
					'key'     => 'role_level',
					'value'   => $role,
					'compare' => 'LIKE'
				);
			}
		}
		
		if ( ! empty( $experience_years ) ) {
			foreach( $experience_years as $experience_year ) {
				switch ( $experience_year ) {
					case '15+' :
						$args['meta_query'][] = array(
							'key'     => 'experience_years',
							'value'   => '15',
							'compare' => '>=',
							'type'    => 'NUMERIC'
						);
					break;
					default :
						$args['meta_query'][] = array(
							'key'     => 'experience_years',
							'value'   => array_map( 'absint', explode( '-', $experience_year ) ),
							'compare' => 'BETWEEN',
							'type'    => 'NUMERIC'
						);
					break;
				}
			}
		}
		
		
		//print_r($args); die();
		$wp_user_query = new WP_User_Query( $args );
		$authors       = $wp_user_query->get_results();
		$total_users   = $wp_user_query->get_total(); // How many users we have in total (beyond the current page)
		$num_pages     = ceil( $total_users / $users_per_page ); // How many pages of users we will need
		
		if ( $total_users < $users_per_page ) { $users_per_page = $total_users; }
		
		if ( ! empty( $authors ) ) {
			foreach ( $authors as $author ) { 
				$author_info		= get_userdata( $author->ID );
				$profile_url 		= ppress_get_frontend_profile_url( $author->ID );
				$description 		= get_user_meta( $author->ID, 'description', true );
				$experience_years 	= get_user_meta( $author->ID, 'experience_years', true );
				$experience 		= get_user_meta( $author->ID, 'experience', true );
				$short_title 		= get_user_meta( $author->ID, 'short_title', true );
				$pp_uploaded_files 	= get_user_meta( $author->ID, 'pp_uploaded_files', true );
				
				
				if ( $experience_years > 1 ) {
					$experience_years = $experience_years . ' years';	
				} else if ( $experience_years == 1 ) {
					$experience_years = $experience_years . ' year';	
				}
				
				$experiences = '';
				if ( ! empty( $experience ) ) {
					$experiences = implode( ',', $experience );	
				}
				$result .=
				'<li>
					<div class="memberleft">
						<a class="profileimg" href="' . $profile_url . '">' . do_shortcode( "[pp-user-avatar user='" . $author->ID . "' size=96 original=true]" ) . '
						</a>';
						if ( $pp_uploaded_files ) {
							$user_logo = $upload_dir['baseurl'] . '/pp-files/' . $pp_uploaded_files['logo']; //echo $user_logo;
							$result .= '<img class="profiletype" src="' . $user_logo . '" width="" height="" alt="" />';
						}
						
					$result .='
					</div>
					
					<div class="memberright">
						<a class="profileimg" href="' . $profile_url . '"><h3>' . $author->display_name . '</h3></a>
						<div class="memberposition">' . $short_title . '</div>
						<div class="memberexcerpt">' . truncate( $description, 150 ) . '</div>
						<div class="membermeta">
							<span class="pleft">';
							if ( ! empty( $experience_years ) ) {
								$result .= '<img class="meta_img" src="' . get_stylesheet_directory_uri() . '/images/timer.png" width="" height="" alt="" /> ' . $experience_years;
							}
							$result .= '</span><span class="pright">';
							if ( ! empty( $experience ) ) {
								$result .= '<img class="meta_img" src="' . get_stylesheet_directory_uri() . '/images/target.png" width="" height="" alt="" /> ' . $experiences;
							}
						$result .= '</span>
						</div>
					</div>
				</li>';
			 
			} 
			
			
			
			$end_size    	= 3;
			$mid_size    	= 3;
			$max_num_pages 	= $num_pages;
			$start_pages 	= range( 1, $end_size );
			$end_pages   	= range( $max_num_pages - $end_size + 1, $max_num_pages );
			$mid_pages   	= range( $current_page - $mid_size, $current_page + $mid_size );
			$pages       	= array_intersect( range( 1, $max_num_pages ), array_merge( $start_pages, $end_pages, $mid_pages ) );

			$result .= '<nav class="member-directory-pagination"><ul>';
					if ( $current_page && $current_page > 1 ) :
						$result .= '<li><a href="#" data-page="' . esc_attr( $current_page - 1 ) . '">&larr;</a></li>';
					endif;
			
					
					foreach ( $pages as $page ) {
						if ( $prev_page != $page - 1 ) {
							$result .= '<li><span class="gap">...</span></li>';
						}
						if ( $current_page == $page ) {
							$result .= '<li><span class="current" data-page="' . esc_attr( $page ) . '">' . esc_html( $page ) . '</span></li>';
						} else {
							$result .= '<li><a href="#" data-page="' . esc_attr( $page ) . '">' . esc_html( $page ) . '</a></li>';
						}
						$prev_page = $page;
					}
					
			
					if ( $current_page && $current_page < $max_num_pages ) :
						$result .= '<li><a href="#" data-page="' . esc_attr( $current_page + 1 ) . '">&rarr;</a></li>';
					endif;
                    
			$result .= '</ul></nav>';


		} else {
			$result = 'No authors found';
		}
		echo json_encode( array( 'success'=>'success', 'result'=> $result, 'total_users' => $total_users ) );
        wp_die();
	}
}


add_shortcode( 'member_directory', 'member_directory' );
/**
 * Shortcode use for members display.
 */
function member_directory(){
	ob_start();
	?>
	<ul class="members_directory"></ul>
	<?php
	$out2 = ob_get_contents();
	ob_end_clean();	
	
	return $out2;
}



add_shortcode( 'member_search', 'member_search' );
/**
 * Shortcode for members search form.
 */
function member_search( $atts ) {
	global $wpdb;
	ob_start();
	echo '<div class="directory_search_form">';
	
	$atts = shortcode_atts( 
		array(
			'key'       => '', 
			'limit'     => 10,
			'searchbar' => 'false',
		),
		$atts,
		'member_search'
	);
	
	if ( ! empty($atts['key'] ) ) {
		$keyarray			= explode( ',', $atts['key'] );
		$fieldkeys			= implode( '","', $keyarray );
		
		$input_fields_array = array( 'text', 'password', 'email', 'tel', 'number', 'hidden' );
		$sql				= 'SELECT * FROM ' . $wpdb->prefix . 'ppress_profile_fields '; 
		$sql				.= 'where field_key IN( "' . $fieldkeys . '" )';	
			
		
		$results			= $wpdb->get_results( $sql ); 
		foreach ( $results as $result ) {
			$key  = $result->field_key;
			$type = $result->type;
			if ( $type === 'checkbox' ) {
				echo '<h3>' . htmlspecialchars_decode( $result->label_name ) . '</h3>';
				$checkbox_values  = array_map( 'trim', explode( ',', $result->options ) );
				$checkbox_tag_key = "{$key}[]";
				
				if ( $checkbox_values ) {
					echo '<ul>';
					foreach ( $checkbox_values as $i => $checkbox_value ) {
						echo sprintf( '<li><input id="%1$s" type="checkbox" name="%2$s" value="%1$s" /> <label for="%1$s">%1$s</label></li>', esc_attr( $checkbox_value ), esc_attr( $checkbox_tag_key ), esc_attr( $checkbox_value ) );
						
						if ( $i >= $atts['limit'] ) break;
					}
					
					if ( $key == 'experience' ) {
						echo '<li><input type="checkbox" name="" value="" checked="checked" id="member_any_exp"> <label for="member_any_exp">Any</label></li>';
					}
					
					echo '</ul>';
				}
			} else if ( $key == 'experience_years' ) {
				echo '<h3>Years of experience</h3>	
					<ul>		
						<li><input type="checkbox" name="experience_years[]" value="0-1"><label for="0-1">0-1 years</label></li>
						<li><input type="checkbox" name="experience_years[]" value="2-5"><label for="2-5">2-5 years</label></li>
						<li><input type="checkbox" name="experience_years[]" value="6-9"><label for="6-9">6-9 years</label></li>
						<li><input type="checkbox" name="experience_years[]" value="10-14"><label for="10-14">10-14 years</label></li>
						<li><input type="checkbox" name="experience_years[]" value="15+"><label for="15+">15+ years</label></li>
					</ul>';
			
			} else if ( $key == 'role_level' ) {
				$sql_role = 'SELECT * FROM ' . $wpdb->prefix . 'usermeta where meta_key="role_level" LIMIT ' . $atts['limit']; 		
				$result_roles = $wpdb->get_results( $sql_role );
				if ( $result_roles ) {
					echo '<h3>Role level</h3>';
					echo '<ul>';
					foreach ( $result_roles as $result_role ) {
						if ( ! empty( $result_role->meta_value ) ) {
							echo '<li><input type="checkbox" name="role_level[]" value="' . esc_attr( $result_role->meta_value ) . '"><label for="' . $result_role->meta_value . '">'.$result_role->meta_value . '</label></li>';
						}
					}
					echo '</ul>';
				}
			} elseif ( $key === 'skills' ) {
				
				$sql_skills = 'SELECT * FROM ' . $wpdb->prefix . 'usermeta where meta_key="skills" LIMIT ' . $atts['limit']; 		
				$result_skills = $wpdb->get_results( $sql_skills );
				if ( $result_skills ) {
					echo '<h3>Skills</h3>';
					echo '<ul>';
					foreach ( $result_skills as $result_skill ) {
						if ( ! empty( $result_skill->meta_value ) ) {
							echo '<li><input type="checkbox" name="skills[]" value="' . esc_attr( $result_skill->meta_value ) . '"><label for="' . $result_skill->meta_value . '">' . $result_skill->meta_value . '</label></li>';
						}
					}
					echo '</ul>';
				}
				
			} else if ( in_array( $type, $input_fields_array ) ) {
				echo '<h3>'.htmlspecialchars_decode( $result->label_name ).'</h3>';
				echo '<input type="' . $type . '" name="' . esc_attr( $key ) . '" id="' . $key . '" value="" class="regular-text"/>';
			}
		}
	}
	if ( $atts['searchbar'] === 'true' ) {
	?>
		<form class="member-search-form" role="search" action="" method="post">
			<div class="member_directory_container">
            	<div class="moc_input_field">
                    <input placeholder="" class="member-search-form__input" type="search" id="member_s" name="member_s" title="Search" value="">
                    <div class="moc_members_count_value_div">
                    	<span class="moc_jobs_search_keyword">Search...</span>
                        <span class="moc_members_count_value number_of_search moc_jobs_count_value"><?php echo esc_html( $fouded_posts_text ); ?></span>
                    </div>
                </div>
                <button class="member_search_form__submit" type="submit" title="Search" aria-label="Search">Search</button>
                
			</div>
		</form>
	<?php
	}
	echo '</div>';
	$out2 = ob_get_contents();
	ob_end_clean();
	return $out2;
}



add_action( 'wp_logout', 'auto_redirect_after_logout' );
function auto_redirect_after_logout() {
	wp_safe_redirect( home_url() );
	exit;
}


add_action( 'admin_menu', 'admin_menu_jobs', 12 );
/**
 * Quick filter menu item in job listing.
 */
function admin_menu_jobs() {
		add_submenu_page( 'edit.php?post_type=job_listing', __( 'Quick Filter', 'wp-job-manager' ), __( 'Quick Filter', 'wp-job-manager' ), 'manage_options', 'job-quick-filter', 'quick_filter_jobs' );
}

/**
 * Actual code for quick filter values in admin.
 */
function quick_filter_jobs(){
	global $wpdb;
	
	$filter_list_array = array();
	if (isset($_POST['form_submitted'])){
		foreach ($_POST as $key => $value) { 
			$filter_list_array[$key] = $value;
		}
		//print_r($filter_list_array);
		update_option( 'quick_filter_list', $filter_list_array );
	}
	
	$saved_list_array = get_option( 'quick_filter_list' );
	$max_filters      = isset( $saved_list_array[ 'max_filters' ] ) ? $saved_list_array[ 'max_filters' ]	: '';
	$quick_options    = array();
	$profile_fields   = array();
	$filterrow        = '';
	$sql              = 'SELECT * FROM ' . $wpdb->prefix . 'ppress_profile_fields WHERE type IN ("select", "checkbox")';
	$results          = $wpdb->get_results( $sql );
	foreach ( $results as $result ) {
		$key              = $result->field_key;
		$type             = $result->type;
		$checkbox_values  = array_map( 'trim', explode( ',', $result->options ) );
		$checkbox_tag_key = "{$key}[]";
		if ( $checkbox_values ) {
			$savedoption = isset( $saved_list_array[ $key ] ) ? $saved_list_array[ $key ] : array();
			$filterrow  .= '<tr valign="top" class=""><th scope="row"><label for="setting-job_experiences_type">Select ' . htmlspecialchars_decode( $result->label_name ) . '</label></th><td>';
			foreach ( $checkbox_values as $i => $checkbox_value ) {
				$checked = '';
				if ( in_array( $checkbox_value, $savedoption, true ) ) {
					$checked = ' checked';
				}
				$filterrow .= sprintf( '<input id="%1$s" type="checkbox" name="%2$s" value="%1$s" ' . $checked . '/> <label for="%1$s">%1$s</label><br/>', esc_attr( $checkbox_value ), esc_attr( $checkbox_tag_key ), esc_attr( $checkbox_value ) );
			}
			$filterrow .= '</td></tr>';
		}
	}
	?>
	<h3>Quick Filter</h3>
	<form action="" method="post">
		<table class="form-table settings parent-settings">
		<tbody>
		<tr valign="top" class="">
		<th scope="row">
		<label for="setting-max_filters">Maximum number of filters</label>
		</th>
		<td>		
		<input id="setting-max_filters" class="regular-text" type="text" name="max_filters" value="<?php echo esc_attr( $max_filters ); ?>">
		</td>
		</tr>
		<?php echo $filterrow; ?>
		<tr valign="top" class="">
		<td colspan="2">
		<input type="hidden" name="form_submitted" value="1" />
		<input type="submit" value="Submit">
		</td>
		</tr>
		</tbody>
		</table>
	</form>
    <?php
}


add_shortcode( 'member_quick_filter', 'member_quick_filter' );
/**
 * Static quick filter for members directory.
 */
function member_quick_filter() {
	global $wpdb;
	ob_start();
	$saved_list_array = get_option( 'quick_filter_list' );
	$quicklist        = '';
	foreach ( $saved_list_array as $key => $values ) {
		foreach ( $values as $value ) {
			$quicklist .= '<li>
			<input type="checkbox" name="filter[]" data-value="' . $value . '" data-type="' . $key . '" value="' . $value . '"> <label for="' . $value . '">' . strtoupper( $value ) . '</label>
			</li>';
		}
	}
	?>
	<div class="quicktitle">Quick filters</div>
	<ul class="quickvalues">
		<?php echo $quicklist; ?>
	</ul>
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
}
