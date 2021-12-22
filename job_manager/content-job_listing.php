<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.0.0
 * @version     1.34.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
global $current_user; 
 
get_currentuserinfo();

$users_preffered_job_type = array();
if ( $current_user ) {
    $user_job_types = get_user_meta( $current_user->ID, 'job_type' , true );
     
    if ( ! empty( $user_job_types ) ) {
       $users_preffered_job_type = $user_job_types;
    }
}


?>
<li <?php job_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
	<div class="job_detail_section">
    	<?php $salary = get_post_meta( get_the_id(), '_job_salary', true ); ?>
    	<div class="firstrow">
        	<?php the_company_logo(); ?>
            <div class="comdetail">
            	<h3><a href="<?php the_job_permalink(); ?>"><?php wpjm_the_job_title(); ?></a></h3>
                <div class="jobcatx"><a href="#">Shopify</a></div>
            </div>
            <?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
                <ul class="all_jobtypes">
                    <?php $types = wpjm_get_the_job_types(); ?>
                    <?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : 
						$selected = '';
						if($users_preffered_job_type && in_array($type->slug, $users_preffered_job_type)){$selected = 'selected';}
					?>
                        <li class="job-type <?php echo $selected . ' '. esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></li>
                    <?php endforeach; endif; ?>
                </ul>
            <?php } ?>
        </div>
        <div class="jobexcerpt"><?php echo get_the_excerpt(); ?></div>
        <ul class="jobsmeta">
            <li class="location"><i aria-hidden="true" class="fas fa-map-marker-alt"></i> <?php the_job_location( false ); ?></li>
            <li class="salary">
				<?php echo (!empty($salary)) ? '<img src="'.get_stylesheet_directory_uri().'/images/money.png" width="" height="" alt="" /> $ '.$salary : '-'; ?>
            </li>
            <li class="date"><?php echo '<img src="'.get_stylesheet_directory_uri().'/images/posted.png" width="" height="" alt="" /> '; the_job_publish_date(); ?></li>
        </ul>
    </div>
    
</li>
