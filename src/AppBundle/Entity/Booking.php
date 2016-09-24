<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 23/09/2016
 *
 */

namespace AppBundle\Entity;

use AppBundle\Entity\TicketType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Booking
 * @package AppBundle\Entity
 *
 *
 */
class Booking implements BookingEntityInterface
{
    /**
     * @var \DateTime
     */
    private $bookingDate;

    /**
     * @var TicketType
     */
    private $ticketType;

    /**
     * @var integer
     */
    private $ticketQuantity;


    public function __construct()
    {
//        $this->setGoodDefaultDate();
        $this->bookingDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getBookingDate(){
        return $this->bookingDate;
    }

    /**
     * @param \DateTime $BookingDate
     */
    public function setBookingDate(\DateTime $BookingDate){
        $this->BookingDate = $BookingDate;
        return $this;
    }

    /**
     * @return TicketType
     */
    public function getTicketType(){
        return $this->ticketType;
    }

    /**
     * @param TicketType $ticketType
     */
    public function setTicketType(TicketType $ticketType){
        $this->ticketType = $ticketType;
        return $this;
    }

    /**
     * @return int
     */
    public function getTicketQuantity(){
        return $this->ticketQuantity;
    }

    /**
     * @param int $ticketQuantity
     */
    public function setTicketQuantity($ticketQuantity){
        $this->ticketQuantity = $ticketQuantity;
        return $this;
    }



//    public function setGoodDefaultDate()
//    {
//        $this->bookingDate = new \DateTime();
//
//        while($this->isNotReservationWeekDayDate() || $this->isHolidaysDate()){
//            $this->bookingDate->add( new \DateInterval("P1D"));
//        }
//    }
}