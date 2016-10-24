<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 15:42
 */

namespace AppBundle\Bridge;


use AppBundle\Entity\Order;
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

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }


    public function find($id)
    {
        return $this->em->getRepository("AppBundle:Order")->find($id);
    }

    public function save(Order $order)
    {
        $order->setDate(new \DateTime());

        $this->em->persist($order);
        $this->em->flush();
    }

    public function getCurrent()
    {
        $order = $this->session->get('order', null);

        if(null === $order){
            return new Order();
        }

        return $this->find($order);
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }
}