<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 13/09/2016
 *
 */

namespace AppBundle\Form\Model;

/**
 * Interface for Reservation Model to use with ReservationHandle
 * Interface Reservable
 * @package AppBundle\Form\Model
 */
interface Reservable
{
    /**
     * @return \DateTime
     */
    public function getReservationDate();

    /**
     * @param \DateTime $reservationDate
     */
    public function setReservationDate(\DateTime $reservationDate);

    /**
     * @return TicketType
     */
    public function getTicketType();

    /**
     * @param TicketType $ticketType
     */
    public function setTicketType($ticketType);

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity);

    /**
     * @return mixed
     */
    public function getForbiddenDates();
}