<?php
/**
 * Filter in `[jobs]` shortcode for job types.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-filter-job-types.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="search_jobs lastfield">
<div>
<?php if ( ! is_tax( 'job_listing_type' ) && empty( $job_types ) ) : ?>
	<label class="labelheading" for="search_jobtypes"><?php _e( 'Type', 'wp-job-manager' ); ?></label>
	<ul class="job_types">
    	<!--<li><input type="checkbox" name="" value="" id="job_any_type"> Any</li>-->
		<?php foreach ( get_job_listing_types() as $type ) : ?>
			<li><input type="checkbox" class="search_checkbox2" name="filter_job_type[]" value="<?php echo esc_attr( $type->slug ); ?>" <?php checked( in_array( $type->slug, $selected_job_types ), true ); ?> id="job_type_<?php echo esc_attr( $type->slug ); ?>" /> <?php echo esc_html( $type->name ); ?></li>
		<?php endforeach; ?>
	</ul>
	<input type="hidden" name="filter_job_type[]" value="" />
<?php elseif ( $job_types ) : ?>
	<?php foreach ( $job_types as $job_type ) : ?>
		<input type="hidden" name="filter_job_type[]" value="<?php echo esc_attr( sanitize_title( $job_type ) ); ?>" />
	<?php endforeach; ?>
<?php endif; ?>
</div>
</div>