<?php
/**
 * Day View Template
 * The wrapper template for day view.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/day.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( tec_events_views_v1_should_display_deprecated_notice() ) {
	_deprecated_file( __FILE__, 'TBD', null, 'On version 6.0.0 this file will be removed. Please refer to <a href="https://evnt.is/v1-removal">https://evnt.is/v1-removal</a> for template customization assistance.' );
}

do_action( 'tribe_events_before_template' );
?>

	<!-- Title Bar -->
<?php tribe_get_template_part( 'day/title-bar' ); ?>

<!-- Tribe Bar -->
<?php tribe_get_template_part( 'modules/bar' ); ?>

<!-- Main Events Content -->
<?php tribe_get_template_part( 'day/content' ) ?>

<div class="tribe-clear"></div>

<?php
do_action( 'tribe_events_after_template' );
