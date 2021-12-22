<?php
/**
 * Single job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.0.0
 * @version     1.28.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
?>
<?php 
$salary = get_post_meta( $post->ID, '_job_salary', true );
$bannersrc = get_field('banner');
if( !empty( $bannersrc['url'] ) ){$banner = $bannersrc['url'];} else{ $banner = get_stylesheet_directory_uri().'/images/jobs.png';} ?>

<div class="job_banner">
<img src="<?php echo esc_url($banner); ?>" alt="<?php echo esc_attr($banner); ?>" />
</div>

<div class="single_job_listing">
	
    <div class="eachjobdetail">
    	<div class="comlogo"><?php the_company_logo(); ?></div>
        <div class="comdetails">
        	<h3><?php wpjm_the_job_title(); ?></h3>
            <ul class="jobmeta">
            	<li class="location"><i aria-hidden="true" class="fas fa-map-marker-alt"></i> <?php the_job_location( false ); ?></li>
                
                <?php if(!empty($salary)){ ?>
                <li class="jobsalaryx"><i aria-hidden="true" class="fas fa-money-check-alt"></i><?php echo $salary; ?></li>
                <?php } ?>
				<?php do_action( 'job_listing_meta_start' ); ?>
                <li class="date"><i aria-hidden="true" class="fas fa-pen-square"></i> <?php the_job_publish_date(); ?></li>
                <?php do_action( 'job_listing_meta_end' ); ?>
            </ul>
            <?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
				<?php $types = wpjm_get_the_job_types(); ?>
                <?php if ( ! empty( $types ) ) : ?>
                    <ul class="jobcats">
						<?php foreach ( $types as $type ) : ?>
                            <li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></li>
                        <?php endforeach;?>
                    </ul>
                <?php endif; ?>
            <?php } ?>
        </div>
        <div class="comapply">
        	<?php if ( $apply = get_the_job_application_method() ) :
				wp_enqueue_script( 'wp-job-manager-job-application' );
				?>
				<div class="job_application application">
					<?php do_action( 'job_application_start', $apply ); ?>
			
					<input type="button" class="application_button button" value="<?php esc_attr_e( 'Apply for job', 'wp-job-manager' ); ?>" />
			
					<div class="application_details">
						<?php
							/**
							 * job_manager_application_details_email or job_manager_application_details_url hook
							 */
							do_action( 'job_manager_application_details_' . $apply->type, $apply );
						?>
					</div>
					<?php do_action( 'job_application_end', $apply ); ?>
				</div>
			<?php endif; ?>
        </div>
    </div>
    
    <div class="job_description">
		<?php wpjm_the_job_description(); ?>
    </div>
    

</div>