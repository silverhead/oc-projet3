<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 15:39
 */

namespace AppBundle\Bridge;

use AppBundle\Entity\Order;

interface BridgeOrderORMInterface extends BridgeORMInterface
{
    public function save(Order $order);
}