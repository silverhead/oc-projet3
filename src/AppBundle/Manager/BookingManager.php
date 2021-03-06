<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 23/09/2016
 *
 */

namespace AppBundle\Manager;

use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Service\BookingSaveInterface;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Service\HolidayProviderInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;


/**
 * Class BookingManager
 * @package AppBundle\Manager
 *
 * This class contain all business application logic
 */
class BookingManager implements BookingManagerInterface
{
    const MAX_NUMBER_OF_BOOKED_TICKETS = 1000;

	const REGEX_DATE_US = '/^[0-9]{2,4}-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[01])$/';

    /**
     * @var BookingSaveInterface
     */
    private $bookingSave;

    /**
     * @var FindBookingsInterface
     */
    private $findBooking;

    /**
     * @var HolidayProviderInterface
     */
    private $holidayProvider;

	private $errorMessages = [];


    public function __construct(
    	BookingSaveInterface $bookingSave,
	    FindBookingsInterface $findBooking,
	    HolidayProviderInterface $holidayProvider)
    {
        $this->bookingSave = $bookingSave;

        $this->findBooking = $findBooking;

        $this->holidayProvider = $holidayProvider;
    }

    public function getCurrentBooking()
    {
        $booking = $this->findBooking->getCurrentBooking();

	    if(!($booking instanceof BookingEntityInterface)){
	    	throw new \Exception("You must use a entity who implement the AppBundle\Entity\BookingEntityInterface !");
	    }

	    //When the user return on the homepage, we must delete the tickets information
	    if(null !== $booking->getId()){
	        //@todo change the place method
	        $this->bookingSave->deleteTickets($booking);
        }

        $bookingDate =  $this->getNextGoodDate($booking->getBookingDate());
        $booking->setBookingDate($bookingDate);

        return $booking;
    }

    /**
     * Define the valid next date in function of applied constraints (if the date of day is valid, it don't change.)
     *
     * @param \DateTime $bookingDate
     * @return \DateTime
     */
    public function getNextGoodDate(\DateTime $bookingDate)
    {
        while($this->isForbiddenDate($bookingDate)){
            $bookingDate->add( new \DateInterval("P1D"));
        }

        return $bookingDate;
    }

    /**
     * Define the forbidden week days constraints
     *
     * @return array
     */
    public function getForbiddenWeekDays()
    {
        return [0, 2];//0 -> Sunday, 2 -> Tuesday
    }

    /**
     * Define the forbidden dates constraints
     *
     * @return array
     */
    public function getForbiddenDates()
    {
        $fullBookingDates   =  $this->getFullBookingDates();
        $holidayDates       =  $this->getHolidayDates();

        return array_merge($fullBookingDates, $holidayDates);
    }

    /**
     * Save Booking Entity
     * @param BookingEntityInterface $bookingEntityFromForm
     */
    public function save(BookingEntityInterface $bookingEntityFromForm){
        if(true !== $this->bookingSave->save($bookingEntityFromForm)){
            array_merge($this->errorMessages, $this->bookingSave->getErrors());
            return false;
        }
        return true;
    }

    /**
     * The holidays dates are forbidden dates for our application business logic
     *
     * @param \DateTime $bookingDate
     * @return array
     */
    public function getHolidayDates(\DateTime $bookingDate = null){
        if(null === $bookingDate){
            $bookingDate = new \DateTime();
        }
        return $this->holidayProvider->getHolidayDatesFor($bookingDate->format('Y'));
    }

    /**
     * Get dates that have reached the 1 000 tickets sold
     * @return array
     */
    public function getFullBookingDates()
    {
        $start = new \DateTime();
        $end = clone $start;
        $end->add(new \DateInterval("P1Y"));//By default the end period is current date + 1 year.

        return $this->findBooking->findAllFullBookingInPeriod($start, $end, self::MAX_NUMBER_OF_BOOKED_TICKETS);
    }

    /**
     * Get all ticketTypes available for the date (for our business logic, selected that in function of hour )
     *
     * @param \DateTime $date
     * @return array
     */
    public function getTicketTypeAvailableFor(\DateTime $date)
    {
		$now = new \DateTime();

	    if($now > $date){
		    $date = $now;
	    }

        return $this->findBooking->findTicketTypeAvailableFor($date);
    }

    /**
     * check if the selected date is forbidden or not
     */
    public function isForbiddenDate(\DateTime $date){
        $forbiddenWeekDay = $this->getForbiddenWeekDays();
        $holidayDates = $this->getHolidayDates($date);
        $fullBookingDates = $this->getFullBookingDates();

	    $today = new \DateTime();

	    //if the user select a date inferior on today
	    if($date->format('Y-m-d') < $today->format('Y-m-d')){
		    $this->errorMessages[] = "Vous ne pouvez pas réserver une date inférieur à la date du jour !";
		    return true;
	    }

        if(in_array($date->format('w'), $forbiddenWeekDay)){
	        $this->errorMessages[] = "Le musée est fermé le mardi et le dimanche !";
            return true;
        }

        if(in_array($date->format('Y-m-d'), $holidayDates)){
	        $this->errorMessages[] = "Le ".$date->format('d/m/Y')." est un jour férié !";
            return true;
        }

        if(in_array($date->format('Y-m-d'), $fullBookingDates)){
	        $this->errorMessages[] = "Désolé les réservations sont complètes pour le ".$date->format('d/m/Y')." !";
            return true;
        }

        $this->errorMessages = [];//re-init error messages

        return false;
    }

	/**
	 * Return list of errors (for especially "isForbiddenDate" method)
	 *
	 * @return array
	 */
	public function getErrorMessages()
	{
		return $this->errorMessages;
	}

	/**
	 * Get the total booking amount and format it
	 *
	 * @param int $ticketTypeId
	 * @param int $ticketQuantity
	 */
	public function getBookingAmount($ticketTypeId, $ticketQuantity)
	{
		$amount = $this->findBooking->getBookingAmount($ticketTypeId, $ticketQuantity);
		return number_format($amount, 2, ",", " ");
	}
}