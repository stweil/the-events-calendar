<?php
/**
 * Class Ticket.
 *
 * @since   TBD
 *
 * @package TEC\Events\Integrations\Plugins\Event_Tickets\Emails
 */

namespace TEC\Events\Integrations\Plugins\Event_Tickets\Emails\Email;

use TEC\Events\Integrations\Plugins\Event_Tickets\Emails\Emails as TEC_Email_Handler;
use TEC\Tickets\Emails\Dispatcher;
use TEC\Tickets\Emails\Email\Ticket as Ticket_Email;
use TEC\Tickets\Emails\Email_Abstract;
use Tribe\Events\Views\V2\iCalendar\Links\Google_Calendar;
use TEC\Events\Integrations\Plugins\Event_Tickets\Emails\Template;

/**
 * Class Ticket.
 *
 * @since   TBD
 *
 * @package TEC\Events\Integrations\Plugins\Event_Tickets
 */
class Ticket {
	/**
	 * The option key for the Event calendar links.
	 *
	 * @see   Email_Abstract::get_option_key() for option key format.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $option_add_event_links = 'tec-tickets-emails-ticket-add-event-links';

	/**
	 * The option key for the Event calendar invite.
	 *
	 * @see   Email_Abstract::get_option_key() for option key format.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $option_add_event_ics = 'tec-tickets-emails-ticket-add-event-ics';

	/**
	 * Filter the email settings and add TEC specific settings.
	 *
	 * @since TBD
	 *
	 * @param array<array<string,mixed>> $settings The email settings.
	 *
	 * @return array<array<string,mixed>> $settings The modified email settings.
	 */
	public function include_settings( $settings ): array {

		$settings[ static::$option_add_event_links ] = [
			'type'            => 'checkbox_bool',
			'label'           => esc_html__( 'Calendar links', 'the-events-calendar' ),
			'tooltip'         => esc_html__( 'Include iCal and Google event links in this email.', 'the-events-calendar' ),
			'default'         => true,
			'validation_type' => 'boolean',
		];

		$settings[ static::$option_add_event_ics ] = [
			'type'            => 'checkbox_bool',
			'label'           => esc_html__( 'Calendar invites', 'the-events-calendar' ),
			'tooltip'         => esc_html__( 'Attach calendar invites (.ics) to the ticket email.', 'the-events-calendar' ),
			'default'         => true,
			'validation_type' => 'boolean',
		];

		return $settings;
	}

	/**
	 * Filters the attachments for the Tickets Emails and maybe add the calendar ics file.
	 *
	 * @since TBD
	 *
	 * @param array<string,string> $attachments The placeholders for the Tickets Emails.
	 * @param Dispatcher           $dispatcher  The Email dispatcher object.
	 *
	 * @return array<string,string> The filtered attachments for the Tickets Emails.
	 */
	public function include_attachments( $attachments, $dispatcher ) {
		$email_class = $dispatcher->get_email();
		if ( ! $email_class->is_enabled() ) {
			return $attachments;
		}

		if ( ! tribe_is_truthy( tribe_get_option( static::$option_add_event_ics, true ) ) ) {
			return $attachments;
		}

		$post_id = $email_class->get( 'post_id' );

		if ( ! tribe_is_event( $post_id ) ) {
			return $attachments;
		}

		$event = tribe_get_event( $post_id );

		if ( empty( $event ) ) {
			return $attachments;
		}

		$attachments = tribe( TEC_Email_Handler::class )->add_event_ics_to_attachments( $attachments, $post_id );

		return $attachments;
	}

	/**
	 * Includes event links in email body for The Events Calendar Tickets.
	 *
	 * This function adds Google Calendar and iCal links to the email body for the
	 * specified event if the email class is enabled and the option to add event links is true.
	 *
	 * @since TBD
	 *
	 * @param \Tribe__Template $parent_template Event Tickets template object.
	 *
	 * @return void
	 */
	public function include_calendar_links( $parent_template ): void {
		$args = $parent_template->get_local_values();

		if ( ! $args['email'] instanceof Ticket_Email ) {
			return;
		}

		$this->render_calendar_links( $args );
	}

	/**
	 * Renders the calendar links for the email body.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args The email arguments.
	 *
	 * @return void
	 */
	public function render_calendar_links( array $args): void {
		// Bail if the option to add calendar links is false.
		if ( ! tribe_is_truthy( tribe_get_option( self::$option_add_event_links, true ) ) ) {
			return;
		}

		if ( ! isset( $args['event'] ) ) {
			return;
		}

		$args['event_gcal_link'] = tribe( Google_Calendar::class )->generate_single_url( $args['event']->ID );
		$args['event_ical_link'] = tribe_get_single_ical_link( $args['event']->ID );

		if ( ! empty( $args['preview'] ) ) {
			$args['event_gcal_link'] = '#';
			$args['event_ical_link'] = '#';
		}

		tribe( Template::class )->template( 'template-parts/body/event/links', $args, true );
	}

	/**
	 * Includes event link styles in email body for The Events Calendar Tickets.
	 *
	 * @since TBD
	 *
	 * @param \Tribe__Template $parent_template Event Tickets template object.
	 *
	 * @return void
	 */
	public function include_event_link_styles( $parent_template ): void {
		tribe( Template::class )->template( 'template-parts/header/head/tec-styles', $parent_template->get_local_values(), true );
	}
}
