<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 23/09/2016
 *
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TicketType;

/**
 * Class Booking
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking implements BookingEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $bookingDate;

    /**
     * @var TicketType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TicketType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketType;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $ticketQuantity;

    /**
     * @var ArrayCollection Ticket
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="booking")
     */
    private $tickets;

    public function __construct()
    {
        $this->bookingDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getBookingDate(){
        return $this->bookingDate;
    }

    /**
     * @param \DateTime $bookingDate
     */
    public function setBookingDate(\DateTime $bookingDate){
        $this->bookingDate = $bookingDate;
        return $this;
    }

    /**
     * @return TicketType
     */
    public function getTicketType(){
        return $this->ticketType;
    }

    /**
     * @param TicketType $ticketType
     */
    public function setTicketType(TicketType $ticketType){
        $this->ticketType = $ticketType;
        return $this;
    }

    /**
     * @return int
     */
    public function getTicketQuantity(){
        return $this->ticketQuantity;
    }

    /**
     * @param int $ticketQuantity
     */
    public function setTicketQuantity($ticketQuantity){
        $this->ticketQuantity = $ticketQuantity;
        return $this;
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
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
