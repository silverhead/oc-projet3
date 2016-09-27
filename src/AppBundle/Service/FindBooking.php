<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManagerInterface;

class FindBooking implements FindBookingsInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \AppBundle\Repository\BookingRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $bookingRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->bookingRepo = $this->em->getRepository("AppBundle:Booking");
    }

    public function find($id)
    {
       return  $this->bookingRepo->find($id);
    }

    public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets)
    {
        return  $this->bookingRepo->findAllFullBookingInPeriod($start, $end, $maxNumberOfBookedTickets);
    }

}