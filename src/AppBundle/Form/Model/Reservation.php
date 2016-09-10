<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Model;

use AppBundle\Entity\TicketType;

/**
 * Class Reservation
 * @package AppBundle\Form\Model
 *
 * Model object for Reservation Form
 */
class Reservation
{
    /**
     * @var \DateTime
     */
    private $reservationDate;

    /**
     * @var TicketType
     */
    private $ticketType;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @return \DateTime
     */
    public function getReservationDate()
    {
        return $this->reservationDate;
    }

    /**
     * @param \DateTime $reservationDate
     * @return Reservation
     */
    public function setReservationDate($reservationDate)
    {
        $this->reservationDate = $reservationDate;
        return $this;
    }

    /**
     * @return TicketType
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * @param TicketType $ticketType
     * @return Reservation
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Reservation
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }


}