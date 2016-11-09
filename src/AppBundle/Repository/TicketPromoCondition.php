<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 09/11/16
 * Time: 22:48
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Order;
use Doctrine\ORM\EntityRepository;

class TicketPromoConditionRepository extends EntityRepository
{
    public function findTicketPromoByNbTicketAmount(Order $order)
    {
        $this->createQueryBuilder("tpc")
            ->where("")
        ;
    }
}