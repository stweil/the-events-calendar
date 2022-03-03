<?php

use TEC\Events\Custom_Tables\V1\Migration\Admin\Upgrade_Tab;

/**
 * @var $template_directory string The base migration template directory.
 */
?>
<div class="tec-ct1-upgrade__row">
	<div class="content-container">
		<h3>
			<?php include $template_directory . '/upgrade-logo.php'; ?>
			<?php esc_html_e( 'Migration in progress', 'the-events-calendar' ); ?>
		</h3>

		<p>
			<?php
			echo sprintf(
				esc_html__( 'Your events are being migrated to the new system. During this migration, %1$syou cannot make changes to your calendar or events.%2$s Your calendar is still visible on your site. ', 'the-events-calendar' ),
				'<strong>',
				'</strong>'
			);

			if ( $addendum = tribe( Upgrade_Tab::class )->get_migration_prompt_addendum() ) {
				?>
				<strong><?php echo esc_html( $addendum ); ?></strong>
				<?php
			}

			echo sprintf(
				esc_html__( '%1$s%3$sLearn more about the migration%4$s.%2$s', 'the-events-calendar' ),
				'<strong>',
				'</strong>',
				'<a href="https://evnt.is/recurrence-2-0" target="_blank" rel="noopener">',
				'</a>'
			);
			?>
		</p>

		<div class="tec-ct1-upgrade-update-bar-container"><p><?php echo __( 'Loading...', 'the-events-calendar' );?></p></div>
		<div>
			<a href="#" class="tec-ct1-upgrade-cancel-migration tec-ct1-upgrade__link-danger"><?php esc_html_e( 'Cancel Migration', 'the-events-calendar' ); ?></a>
		</div>
	</div>
	<div class="image-container">
		<img class="screenshot" src="<?php echo esc_url( plugins_url( 'src/resources/images/upgrade-views-screenshot.png', TRIBE_EVENTS_FILE ) ); ?>" alt="<?php esc_attr_e( 'screenshot of updated calendar views', 'the-events-calendar' ); ?>" />
	</div>
</div>
