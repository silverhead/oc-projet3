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


//       $qb=  $this->createQueryBuilder("tpc");
//
//
//	    $promo[1] -> condition 1 -> ticket adulte x 2 check dans la commande 1 = ok
//	    $promo[1] -> condition 2 -> ticket enfant x 2 check dans la commande 1 = ok
//	    $promo[1] -> condition 3 -> ticket bebe x 0 check dans la commande 1 = ok
//
//
//		$promo[1] -> condition 1 -> ticket adulte x 2 check 2 adulte dans la commande 2 = ok
//	    $promo[1] -> condition 2 -> ticket enfant x 2 check 2 enfant dans la commande 2 = ok
//	    $promo[1] -> condition 3 -> ticket bebe x 0 check 1 bebe dans la commande 2 = non ok
//
//
//
//   	    $promo[2] -> condition 1 -> ticket adulte x 2 check dans la commande = ok
//	    $promo[2] -> condition 2 -> ticket enfant x 2 check dans la commande = ok
//	    $promo[2] -> condition 3 -> ticket bebe x 2 check dans la commande = non ok
//
//
//	    $promos = [];
//
//	    $promos[1] = [5];
//	    $promos[2] = [4];
//	    /**
//	     * ticket = adulte and nb = 2
//	     * and
//	     * ticket = enfant and nb = 2
//	     * and
//	     * ticket bebe and nb = 0
//	     */
//
//	    $ticketAmounts = "";
//	    foreach($ticketAmounts as $ticketAmount){
//		    $qb->where("tpc.ticketAmount = :ticketAmount") //type de tarif exemple adulte
//		    ->andWhere("tpc.count >= :countTicket") // nombre de ticket de ce type montant dans la commande
//		    ;
//	    }

    }
}