<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 07/11/2016
 *
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class TicketPromo
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ticket_promo")
 */
class TicketPromo
{
    /**
     * @var integer
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TicketPromoCondition", mappedBy="ticketPromo")
     */
    private $ticketPromoConditions;


    public function __construct()
    {
        $this->ticketPromoConditions = new ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return TicketPromoCondition
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return TicketPromoCondition
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
     * Set ticketPromoConditions
     *
     * @param \AppBundle\Entity\TicketPromoCondition $ticketPromoConditions
     *
     * @return TicketPromo
     */
    public function setTicketPromoConditions(\AppBundle\Entity\TicketPromoCondition $ticketPromoConditions = null)
    {
        $this->ticketPromoConditions = $ticketPromoConditions;

        return $this;
    }

    /**
     * Get ticketPromoConditions
     *
     * @return \AppBundle\Entity\TicketPromoCondition
     */
    public function getTicketPromoConditions()
    {
        return $this->ticketPromoConditions;
    }

    /**
     * Add ticketPromoCondition
     *
     * @param \AppBundle\Entity\TicketPromoCondition $ticketPromoCondition
     *
     * @return TicketPromo
     */
    public function addTicketPromoCondition(\AppBundle\Entity\TicketPromoCondition $ticketPromoCondition)
    {
        $this->ticketPromoConditions[] = $ticketPromoCondition;

        return $this;
    }

    /**
     * Remove ticketPromoCondition
     *
     * @param \AppBundle\Entity\TicketPromoCondition $ticketPromoCondition
     */
    public function removeTicketPromoCondition(\AppBundle\Entity\TicketPromoCondition $ticketPromoCondition)
    {
        $this->ticketPromoConditions->removeElement($ticketPromoCondition);
    }
}
