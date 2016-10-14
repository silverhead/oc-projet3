<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ticket
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 *
 */
class Ticket
{
    /**
     * @var integer
     *
     * @ORM\Id;
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $bookingDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $serialNumber;


	/**
	 * @var Customer
	 *
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Customer", cascade={"persist", "remove"})
	 */
	protected $customer;

	/**
	 * @var TicketType
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TicketType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
	 */
	protected $type;

    /**
     * @var Booking
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking", inversedBy="tickets")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     */
    protected $booking;

    /**
     * @var TicketAmount
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TicketAmount")
     * @ORM\JoinColumn(name="ticket_amount_id", referencedColumnName="id")
     */
    protected $ticketAmount;

    /**
     * @var float
     * @ORM\Column(name="amount", type="float", nullable=false)
     */
    protected $amount;


    public function __construct()
    {
        $this->generateSerialNumber();
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
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Ticket
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\TicketType $type
     *
     * @return Ticket
     */
    public function setType(\AppBundle\Entity\TicketType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\TicketType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set booking
     *
     * @param \AppBundle\Entity\Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(\AppBundle\Entity\Booking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Ticket
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Generate random serial number
     */
    private function generateSerialNumber()
    {
        $this->serialNumber = uniqid();
    }

    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return Ticket
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     *
     * @return Ticket
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Set ticketAmount
     *
     * @param \AppBundle\Entity\TicketAmount $ticketAmount
     *
     * @return Ticket
     */
    public function setTicketAmount(\AppBundle\Entity\TicketAmount $ticketAmount = null)
    {
        $this->ticketAmount = $ticketAmount;

        return $this;
    }

    /**
     * Get ticketAmount
     *
     * @return \AppBundle\Entity\TicketAmount
     */
    public function getTicketAmount()
    {
        return $this->ticketAmount;
    }
}
