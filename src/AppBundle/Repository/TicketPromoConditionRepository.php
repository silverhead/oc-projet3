<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 09/11/16
 * Time: 22:48
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Booking;
use Doctrine\ORM\EntityRepository;

class TicketPromoConditionRepository extends EntityRepository
{
	/**
	 * @param $promos
	 * @param $booking
	 * @return array (key => id of ticketPromo and value => count matching ticketAmount
	 */
    public function getMatchingTicketPromoByPromoAndBooking($promos, $booking)
    {
	    $testPromo = [];
	    foreach($promos as $promo){
		    $testPromo[$promo->getId()] = $this->createQueryBuilder("tpc")
			    ->select("count(tpc)")
			    ->where("tpc.ticketPromo= :ticketPromo")->setParameter(":ticketPromo",$promo->getId())
			    ->andWhere("tpc.count <= 0 OR tpc.count <= (SELECT COUNT(DISTINCT(t)) FROM AppBundle\Entity\Booking b JOIN b.tickets t 
                            WHERE b = :booking AND t.ticketAmount = tpc.ticketAmount GROUP BY b)")
			    ->setParameter(':booking', $booking)
			    ->getQuery()->getSingleScalarResult()
		    ;
	    }

	    return $testPromo;
    }


    public function getTicketPromoIdHavingMaxCountByIds($ticketPromoIds)
    {
	    $qb = $this->createQueryBuilder("tpc")
		    ->select("tp.id, SUM(tpc.count) as sumCount")
		    ->join("tpc.ticketPromo", "tp")
		    ->addGroupBy("tp.id")
		    ->setMaxResults(1)
		    ->addOrderBy("sumCount", "DESC")
	    ;

	    return $qb->add('where', $qb->expr()->in('tp.id', $ticketPromoIds))
		    ->getQuery()->getOneOrNullResult()['id']
	    ;
    }
}