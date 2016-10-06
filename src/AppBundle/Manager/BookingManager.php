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


/**
 * Class BookingManager
 * @package AppBundle\Manager
 *
 * This class contain all business application logic
 */
class BookingManager implements BookingManagerInterface
{
    const MAX_NUMBER_OF_BOOKED_TICKETS = 1000;

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
        $this->bookingSave->save($bookingEntityFromForm);
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

	public function getErrorMessages()
	{
		return $this->errorMessages;
	}
}