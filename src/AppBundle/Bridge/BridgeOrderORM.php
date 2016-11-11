<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 15:42
 */

namespace AppBundle\Bridge;


use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BridgeOrderORM implements BridgeOrderORMInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $errors = array();

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }


    public function find($id)
    {
        return $this->em->getRepository("AppBundle:Order")->find($id);
    }

    public function save(Order $order, BookingEntityInterface $booking)
    {

        try{
	        foreach ($booking->getTickets() as $ticket){
		        $orderDetail = new OrderDetail();
		        $orderDetail->setTicket($ticket);

		        $order->addOrderDetail($orderDetail);
	        }

            $order->setDate(new \DateTime());


            $this->em->persist($order);
            $this->em->flush();

	        $this->session->set('order', $order->getId());

            return true;
        }
        catch (\Exception $e){
            $this->errors[] = $e->getMessage();

            return false;
        }

    }

    public function deleteLines(Order $order)
    {

	    foreach ($order->getOrderDetails() as $line){
		    $order->removeOrderDetail($line);
	    }

	    $this->em->flush();
    }

    public function getCurrent()
    {
        $orderId = $this->session->get('order', null);

        if(null === $orderId){
            return new Order();
        }

        return $this->find($orderId);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function removeCurrent(){
	    $this->session->remove('order');
    }

    public function addAutoPromo(Order $order)
    {
//        $ticketPromo = $this->em->getRepository("AppBundle:TicketPromoCondition")->findTicketPromoByNbTicketAmount($order);

	    $promos = $this->em->getRepository("AppBundle:TicketPromo")->findAll();


    }

    public function checkBelonging($email)
    {
		$orderId = $this->getCurrent();

	    $order = $this->em->getRepository("AppBundle:Order")->findOneBy([
	    	'id'    => $orderId,
		    'email' => $email
	    ]);

	    return (null !== $order);//if not object is returned then the checking has failed
    }

	public function isStandby($order)
	{
		return (Order::STATE_STANDBY === $order->getState());
	}

	public function isPayed($order)
	{
		return (Order::STATE_PAYED === $order->getState());
	}

	public function isCanceled($order)
	{
		return (Order::STATE_CANCELED === $order->getState());
	}

	public function isRefused($order)
	{
		return (Order::STATE_REFUSED === $order->getState());
	}
}