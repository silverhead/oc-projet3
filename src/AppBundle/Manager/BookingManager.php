<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 23/09/2016
 *
 */

namespace AppBundle\Manager;

use AppBundle\Entity\BookingEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Yasumi\Yasumi;

class BookingManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Booked
     */
    private $bookingEntity;

    /**
     * @var FormTypeInterface
     */
    private $bookingFormType;

    public function __construct
    (
        EntityManagerInterface $em,
        FormTypeInterface $bookingFormType,
        BookingEntityInterface $bookingEntity
    )
    {
        $this->em = $em;

        $this->bookingFormType = $bookingFormType;

        $this->bookingEntity = $bookingEntity;
    }

    public function getFormType()
    {
        return $this->bookingFormType;
    }

    public function getEntity()
    {
        return $this->bookingEntity;
    }

    public function getForbiddenWeekDays()
    {
        return [0, 2];//0 -> Sunday, 2 -> Tuesday
    }

    public function getForbiddenDates()
    {
        $holidays = $this->getHolidays();
        $fullBookingDates = $this->getFullBookingDates();

        return array_merge($holidays, $fullBookingDates);
    }

    public function save(BookingEntityInterface $bookingEntityFormData){
        //set booking to a doctrine Entity before
//        $this->em->persist($bookingEntityFormData);
//        $this->em->flush();
    }


    private function getFullBookingDates()
    {
        return array();
    }

    private function getHolidays()
    {
        //get The holidays dates by country and verif if the date is a holiday date
        $holidays  = Yasumi::create('France', $this->bookingEntity->getBookingDate()->format('Y'), 'fr_FR');

        return $holidays->getHolidayDates();
    }
}