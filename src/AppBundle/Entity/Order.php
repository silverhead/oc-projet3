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
use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;

/**
 * Class Order
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="booking_order")
 */
class Order
{
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

	/** @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction") */
	private $paymentInstruction;


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
        $sums = $this->getOrderDetails()->map(function($orderDetail){
			return $orderDetail->getAmount();
		});

        return array_sum($sums->toArray());
	}

	public function getPaymentInstruction()
	{
		return $this->paymentInstruction;
	}

	public function setPaymentInstruction(PaymentInstructionInterface $instruction)
	{
		$this->paymentInstruction = $instruction;
	}
}
