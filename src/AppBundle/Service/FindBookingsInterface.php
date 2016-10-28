<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Entity\Ticket;

interface FindBookingsInterface
{
	/**
	 * Return the current Booking Entity
	 *
	 * @return BookingEntityInterface
	 *
	 */
	public function getCurrentBooking();

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
}