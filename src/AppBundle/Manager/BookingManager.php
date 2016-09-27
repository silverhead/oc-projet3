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
class BookingManager
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

    public function __construct(BookingSaveInterface $bookingSave, FindBookingsInterface $findBooking, HolidayProviderInterface $holidayProvider)
    {
        $this->bookingSave = $bookingSave;

        $this->findBooking = $findBooking;

        $this->holidayProvider = $holidayProvider;
    }

    /**
     * Define Booking entity data if they already has recorded and/or apply some constraints
     *
     * @param BookingEntityInterface $bookingEntity
     */
    public function updateData(BookingEntityInterface $bookingEntity){
        $bookingDate = $bookingEntity->getBookingDate();
        $updatedBookingDate = $this->getNextGoodDate($bookingDate);

        $bookingEntity->setBookingDate($updatedBookingDate);
    }

    /**
     * Define the valid next date in function of applied constraints (if the date of day is valid, it don't change.)
     *
     * @param \DateTime $bookingDate
     * @return \DateTime
     */
    public function getNextGoodDate(\DateTime $bookingDate)
    {
        $forbiddenWeekDays = $this->getForbiddenWeekDays();
        $forbiddenDates = $this->getForbiddenDates();

        while(in_array($bookingDate->format('Y-m-d'), $forbiddenDates) || in_array($bookingDate->format('w'), $forbiddenWeekDays)){
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
        return $this->getFullBookingDates();
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
    public function getHolidayDates(\DateTime $bookingDate){
        return $this->holidayProvider->getHolidayDatesFor($bookingDate->format('Y'));
    }

    /**
     * Get dates that have reached the 1 000 tickets sold
     * @return array
     */
    public function getFullBookingDates()
    {
        $start = new \DateTime();
        $end = $start->add(new \DateInterval("P1Y"));//By default the end period is current date + 1 year;

        return $this->findBooking->findAllFullBookingInPeriod($start, $end, self::MAX_NUMBER_OF_BOOKED_TICKETS);
    }

    /**
     * check if the selected date is forbidden or not
     */
    public function isForbiddenDate(\DateTime $date){
        $forbiddenWeekDay = $this->getForbiddenWeekDays();
        $forbiddenDates = $this->getForbiddenDates();

        if(in_array($date->format('w'), $forbiddenWeekDay)){
            return true;
        }

        if(in_array($date->format('Y-m-d'), $forbiddenDates)){
            return true;
        }

        return false;
    }
}
