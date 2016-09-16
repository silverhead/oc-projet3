<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Model;

use AppBundle\Entity\TicketType;
use Yasumi\Provider\AbstractProvider;
use Yasumi\Yasumi;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Reservation
 * @package AppBundle\Form\Model
 *
 * Model object for Reservation Form
 */
class Reservation implements Reservable
{

	/**
	 * @var AbstractProvider
	 */
	private $holidayDates;

	private $repoOrder;

	public function __construct()
	{

	}

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
    	$this->isForbiddenDate($reservationDate);
	    $this->isLimitReachedTicketSoldDate($reservationDate);

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
	 * @Assert\IsFalse(message= "La date choisie est un jour férié, veuillez choisir une autre date !")
	 *
	 * @param \DateTime $date
	 * @return bool
	 */
    public function isForbiddenDate(\DateTime $date){
        //If it's a Sunday or Tuesday, it's not authorized date
        if(  0 == $date->format('w') || 2 == $date->format('w') ){
            return true;
        }
        //get The holidays dates by country and verif if the date is a holiday date
        $holidays = Yasumi::create('France', $date->format('Y'), 'fr_FR');
        return $holidays->isHoliday($date);
    }

	/**
	 * @Assert\IsFalse(message= "Nous sommes désolés mais le nombre maximum de billet vendu pour cette date a été atteint !")
	 *
	 * @param \DateTime $date
	 * @return bool
	 */
    public function isLimitReachedTicketSoldDate(\DateTime $date){

    }
}