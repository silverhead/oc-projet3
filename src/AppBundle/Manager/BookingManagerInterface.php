<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 30/09/16
 * Time: 09:14
 */

namespace AppBundle\Manager;

use AppBundle\Entity\BookingEntityInterface;

interface BookingManagerInterface
{
	/**
	 * Define Booking entity data if they already has recorded and/or apply some constraints
	 *
	 * @return BookingEntityInterface $bookingEntity
	 */
	public function getCurrentBooking();

	/**
	 * Define the forbidden week days constraints
	 *
	 * @return array
	 */
	public function getForbiddenWeekDays();

	/**
	 * Define the forbidden dates constraints
	 *
	 * @return array
	 */
	public function getForbiddenDates();

	/**
	 * Save Booking Entity
	 *
	 * @param BookingEntityInterface $bookingEntityFromForm
	 */
	public function save(BookingEntityInterface $bookingEntityFromForm);

	/**
	 * check if the selected date is forbidden or not
	 * It has used into Validator
	 *
	 * @return bool
	 */
	public function isForbiddenDate(\DateTime $date);

	/**
	 * get all errors message if a constraint violation has been declared
	 * It has used into Validator
	 *
	 * @return array of string
	 */
	public function getErrorMessages();


	/**
	 * Get the total booking amount
	 *
	 * @param int $ticketTypeId
	 * @param int $ticketQuantity
	 */
	public function getBookingAmount($ticketTypeId, $ticketQuantity);

	/**
	 * Get the ticket types list in function of the date
	 *
	 * @param \DateTime $date
	 * @return mixed
	 */
	public function getTicketTypeAvailableFor(\DateTime $date);
}