<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Model;

use AppBundle\Entity\TicketType;
use Yasumi\Yasumi;



/**
 * Class Reservation
 * @package AppBundle\Form\Model
 *
 * Model object for Reservation Form
 */
class Reservation implements Reservable
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

    /**
     * Verif if the date is a holiday date, in case, it's a forbidden date
     *
     * @param \DateTime $date
     * @param string $country
     * @param string $locale
     *
     * @return bool
     */
    public function isForbiddenDay(\DateTime $date, $country = 'France', $locale = 'fr_FR'){
        //If it's a Sunday or Tuesday, it's not authorized date
        if(  0 == $date->format('w') || 2 == $date->format('w') ){
            return true;
        }
        //get The holidays dates by country and verif if the date is a holiday date
        $holidays = Yasumi::create($country, $date->format('Y'), $locale);
        return $holidays->isHoliday($date);
    }

    public function isOverReservationDay(){

    }
}