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

    public function save(Order $order)
    {
        try{
            $order->setDate(new \DateTime());
            $this->em->persist($order);
            $this->em->flush();

            return true;
        }
        catch (\Exception $e){
            $this->errors[] = $e->getMessage();

            return false;
        }

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
        return $this->errors;
    }
}