<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 10:00
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderDetail
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="booking_order_detail")
 */
class OrderDetail
{
    /**
     * @var integer
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    protected $id;

    /**
     * @var Ticket
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ticket")
     */
    protected $ticket;

	/**
	 * @var Order
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="orderDetails")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
	 */
	protected $order;


    /**
     * Set ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return OrderDetail
     */
    public function setTicket(\AppBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \AppBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set booking
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderDetail
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function getAmount()
    {
	    return $this->ticket->getAmount();
    }
}
