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
    public function findTicketPromoByNbTicketAmount(Booking $booking)
    {
        $promos = $this->_em->getRepository("AppBundle:TicketPromo")->findAll();
        $testPromo = [];
        foreach($promos as $promo){
            $testPromo[$promo->getId()] = $this->createQueryBuilder("tpc")
                ->select("count(tpc)")
                ->where("tpc.ticketPromo= :ticketPromo")->setParameter(":ticketPromo",$promo->getId())
                ->andWhere("tpc.count <= (SELECT COUNT(DISTINCT(t)) FROM AppBundle\Entity\Booking b JOIN b.tickets t 
                            WHERE b = :booking AND t.ticketAmount = tpc.ticketAmount)")
                            ->setParameter(':booking', $booking)
                ->getQuery()->getSingleScalarResult()
            ;
        }
        dump($testPromo);

    }
}