<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 25/09/2016
 *
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BookingRepository extends EntityRepository
{
    /**
     * Find all dates contain the max number of booked tickets
     *
     * @param \DateTime $start
     * @param \DateTime $end
     * @param $maxNumberOfBookedTickets
     * @return array
     */
    public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets)
    {
        return $this->createQueryBuilder("b")
	        ->select('b.bookingDate')
            ->where("b.bookingDate >= :start")->setParameter(':start', $start)
            ->andWhere("b.bookingDate <= :end")->setParameter(':end', $end)
            ->groupBy("b.bookingDate")
            ->having("sum(b.ticketQuantity) >= :maxNumberOfBookedTickets")->setParameter(":maxNumberOfBookedTickets", $maxNumberOfBookedTickets)
	        ->getQuery()
	        ->getArrayResult()
        ;
    }
}