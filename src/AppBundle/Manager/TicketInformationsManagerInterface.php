<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 13/10/16
 * Time: 10:55
 */

namespace AppBundle\Manager;


use AppBundle\Entity\BookingEntityInterface;

interface TicketInformationsManagerInterface
{
    /**
     * Return the Booking model
     * @return mixed
     */
    public function getCurrentBooking();

    public function setNewTicketsForBooking(BookingEntityInterface $booking);

    /**
     * Return the cost of ticket in function of the birthday
     * @param \DateTime $birthday
     * @return mixed
     */
    public function getTicketPriceByBirthday(\DateTime $birthday);

    /**
     * Return the special rate of ticket if a special rate is ckecked
     *
     * @param $id
     * @return mixed
     */
    public function getSpecialRateById($id);

    /**
     * Save all data for the tickets
     *
     * @return mixed
     */
    public function saveTickets();
}