<?php
/**
 * Handles the set up of The Events Calendar plugin on first boot (fresh install) only.
 *
 * In place of scattering defaults in redundant checks in the code, this provider should group anything
 * that fits into a "fresh install".
 * The provider will not hook any filter if The Events Calendar did init already.
 *
 * @since   TBD
 *
 * @package Tribe\Events\Service_Providers
 */

namespace Tribe\Events\Service_Providers;

/**
 * Class First_Boot
 *
 * @since   TBD
 *
 * @package Tribe\Events\Service_Providers
 */
class First_Boot extends \tad_DI52_ServiceProvider {

	/**
	 * Hooks the filters required to set up The Events Calendar after a fresh install.
	 *
	 * @since TBD
	 */
	public function register() {
		$options = \Tribe__Settings_Manager::get_options();

		if ( ! empty( $options['did_init'] ) ) {
			// If we did init already, bail.
			return;
		}

		add_action( 'tribe_events_bound_implementations', [ $this, 'set_default_options' ] );

		/**
		 * Fires on The Events Calendar first boot.
		 *
		 * @since TBD
		 */
		do_action( 'tribe_events_first_boot' );
	}

	/**
	 * Sets up The Events Calendar default options on first boot.
	 *
	 * @since TBD
	 */
	public function set_default_options() {
		$options['did_init'] = true;

		if ( ! isset( $options['tribeEventsTemplate'] ) ) {
			// Set the Events Template default to "Default Events Template".
			$options['tribeEventsTemplate'] = '';
		}

		\Tribe__Settings_Manager::set_options( $options );
	}
}
