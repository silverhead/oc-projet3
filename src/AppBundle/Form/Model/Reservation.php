<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Model;

use AppBundle\Entity\TicketType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Yasumi\Yasumi;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Reservation
 * @package AppBundle\Form\Model
 *
 * Model object for Reservation Form
 *
 * @Assert\Callback(callback="isNotReservationWeekDayDateConstraint")
 * @Assert\Callback(callback="isHolidaysDateConstraint")
 * //, "isHolidaysDate"
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

    public function __construct()
    {
        $this->setGoodDefaultDate();
    }

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
    public function setReservationDate(\DateTime $reservationDate)
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

    public function setGoodDefaultDate()
    {
        $this->reservationDate = new \DateTime();

        while($this->isNotReservationWeekDayDate() || $this->isHolidaysDate()){
            $this->reservationDate->add( new \DateInterval("P1D"));
        }
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function isNotReservationWeekDayDateConstraint(ExecutionContextInterface $context)
    {
        //If it's a Sunday or Tuesday, it's not authorized date
        if($this->isNotReservationWeekDayDate()){
            $context->buildViolation('La date choisie est un jour non autorisé pour la réservation !')
                ->atPath('reservationDate')
                ->addViolation();
        }
    }

    /**
     * Verified if the reservation date is a holiday and return true if is it.
     *
     * @param ExecutionContext $context
     */
    public function isHolidaysDateConstraint(ExecutionContextInterface $context)
    {
        if($this->isHolidaysDate()){
            $context
                ->buildViolation('La date choisie est un jour férié, veuillez choisir une autre date !')
                ->atPath('reservationDate')
                ->addViolation();
        }
    }

    /**
     * Verified if the reservation date is not a Tuesday or Sunday date and return true if is it.
     *

     */
    public function isNotReservationWeekDayDate()
    {
        return (0 == $this->reservationDate->format('w') || 2 == $this->reservationDate->format('w'));
    }

    /**
     * Verified if the reservation date is a holiday and return true if is it.
     *
     */
    public function isHolidaysDate()
    {
        //get The holidays dates by country and verif if the date is a holiday date
        $holidays = Yasumi::create('France', $this->reservationDate->format('Y'), 'fr_FR');
        return $holidays->isHoliday($this->reservationDate);
    }

    public function isLimitReachedTicketSoldDate()
    {

    }


    public function getForbiddenDates(){
        //get The holidays dates by country and verif if the date is a holiday date
        $holidays = Yasumi::create('France', $this->reservationDate->format('Y'), 'fr_FR');

        return [
            'weekDates' => [0, 2],
            'holidaysDates' => $holidays->getHolidayDates()
        ];
    }
}