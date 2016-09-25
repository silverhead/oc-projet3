<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 23/09/2016
 *
 */

namespace AppBundle\Manager;

use AppBundle\Entity\BookingEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Yasumi\Yasumi;

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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $this->em->persist($bookingEntityFromForm);
        $this->em->flush();
    }

    /**
     * The holidays dates are forbidden dates for our application business logic
     *
     * @param \DateTime $bookingDate
     * @return array
     */
    public function getHolidayDates(\DateTime $bookingDate){
        $holidayProvider = Yasumi::create('France', $bookingDate->format('Y'));
        return $holidayProvider->getHolidayDates();
    }

    /**
     * Get dates that have reached the 1 000 tickets sold
     * @return array
     */
    public function getFullBookingDates()
    {
        $start = new \DateTime();
        $end = $start->add(new \DateInterval("P1Y"));//By default the end period is current date + 1 year;

        return $this->em->getRepository("AppBundle:Booking")->findAllFullBookingInPeriod($start, $end, self::MAX_NUMBER_OF_BOOKED_TICKETS);
    }


}