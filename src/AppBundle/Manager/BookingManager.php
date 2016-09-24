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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getForbiddenWeekDays()
    {
        return [0, 2];//0 -> Sunday, 2 -> Tuesday
    }

    public function getForbiddenDates()
    {
        return $this->getFullBookingDates();
    }

    public function save(BookingEntityInterface $bookingEntityFormData){
        //set booking to a doctrine Entity before
//        $this->em->persist($bookingEntityFormData);
//        $this->em->flush();
    }

    public function getHolidayDates(\DateTime $bookingDate){
        $holidayProvider = Yasumi::create('France', $bookingDate->format('Y'));
        return $holidayProvider->getHolidayDates();
    }


    private function getFullBookingDates()
    {
        return array();
    }


}