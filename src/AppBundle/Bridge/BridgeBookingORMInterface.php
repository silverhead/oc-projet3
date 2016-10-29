<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 29/10/16
 * Time: 07:44
 */

namespace AppBundle\Bridge;


interface BridgeBookingORMInterface
{
	/**
	 * Return the current Booking Entity
	 *
	 * @return BookingEntityInterface
	 *
	 */
	public function getCurrentBooking();

	/**
	 * shortcut of  getCurrentBooking()
	 *
	 * @return mixed
	 */
	public function getCurrent();

	/**
	 * @return Ticket
	 */
	public function getTicket();

	/**
	 * Get the Booking model infos
	 */
	public function find($id);

	/**
	 * Find all full booking in a period with a number to indicate the max booking for a day
	 *
	 * @param \DateTime $start
	 * @param \DateTime $end
	 * @param $maxNumberOfBookedTickets
	 * @return mixed
	 */
	public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets);

	/**
	 * @param \DateTime $date
	 * @return array of TicketType
	 */
	public function findTicketTypeAvailableFor(\DateTime $date);

	/**
	 * Get the amount of the booking
	 *
	 * @param integer $ticketTypeId
	 * @param integer $ticketQuantity
	 *
	 * @return integer
	 */
	public function getBookingAmount($ticketTypeId, $ticketQuantity);


	public function save(BookingEntityInterface $booking);

	/**
	 * Delete the current booking
	 *
	 * @return mixed
	 */
	public function removeCurrent();
}