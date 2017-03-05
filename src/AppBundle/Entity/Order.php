<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 09:45
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\ArrayObject;

/**
 * Class Order
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="booking_order")
 */
class Order extends ArrayObject
{
	const STATE_STANDBY     = 0;
	const STATE_PAYED       = 1;
	const STATE_REFUSED     = 2;
	const STATE_CANCELED    = 3;


    /**
     * @var integer
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    protected $id;

    /**
     * @var DateTime
     * @ORM\Column(name="order_date", type="date")
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;


	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderDetail", mappedBy="order", cascade={"persist", "remove"}, orphanRemoval=true)
	 */
	protected $orderDetails;


	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $state;

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Constructor
     */
    public function __construct()
    {
        $this->orderDetails = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->state = self::STATE_STANDBY;
    }

    /**
     * Add orderDetail
     *
     * @param \AppBundle\Entity\OrderDetail $orderDetail
     *
     * @return Order
     */
    public function addOrderDetail(\AppBundle\Entity\OrderDetail $orderDetail)
    {
	    $orderDetail->setOrder($this);
        $this->orderDetails[] = $orderDetail;

        return $this;
    }

    /**
     * Remove orderDetail
     *
     * @param \AppBundle\Entity\OrderDetail $orderDetail
     */
    public function removeOrderDetail(\AppBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetails->removeElement($orderDetail);
	    $orderDetail->setOrder(null);
    }

    /**
     * Get orderDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDetails()
    {
        return $this->orderDetails;
    }

	public function getAmount()
	{
        $sums = $this->getOrderDetails()->map(function(OrderDetail $orderDetail){
			return $orderDetail->getAmount();
		});

        return array_sum($sums->toArray());
	}


    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Order
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }
}
