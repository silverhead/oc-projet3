<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 14:16
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Order;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Bridge\BridgeOrderORMInterface;

class CheckOrderManager
{

    /**
     * @var FindBookingsInterface
     */
    private $findBookings;

    /**
     * @var BridgeOrderORMInterface
     */
    private $bridgeOrder;

    public function __construct
    (
        FindBookingsInterface $findBookings,
        BridgeOrderORMInterface $bridgeOrder
    )
    {
        $this->findBookings = $findBookings;
        $this->bridgeOrder = $bridgeOrder;
    }

    public function getCurrentBooking()
    {
        return $this->findBookings->getCurrentBooking();
    }

    public function getCurrentOrder()
    {
        return $this->bridgeOrder->getCurrent();
    }


    public function saveOrder(Order $order)
    {
        return $this->bridgeOrder->save($order);
    }
}