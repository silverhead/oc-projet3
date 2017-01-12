<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 07/11/2016
 *
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class TicketPromoCondition
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketPromoConditionRepository")
 * @ORM\Table(name="ticket_promo_condition", uniqueConstraints={@UniqueConstraint(name="unique_condition", columns={"ticket_amount_id", "ticket_promo_id"})})
 *
 */
class TicketPromoCondition
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @var TicketAmount
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TicketAmount")
     * @ORM\JoinColumn(name="ticket_amount_id", referencedColumnName="id")
     */
    private $ticketAmount;

    /**
     * @var TicketPromoCondition
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TicketPromo", inversedBy="ticketPromoConditions")
     * @ORM\JoinColumn(name="ticket_promo_id", referencedColumnName="id")
     */
    private $ticketPromo;

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
     * Set count
     *
     * @param integer $count
     *
     * @return TicketPromoCondition
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set ticketPromo
     *
     * @param \AppBundle\Entity\TicketPromo $ticketPromo
     *
     * @return TicketPromoCondition
     */
    public function setTicketPromo(\AppBundle\Entity\TicketPromo $ticketPromo = null)
    {
        $this->ticketPromo = $ticketPromo;

        return $this;
    }

    /**
     * Get ticketPromo
     *
     * @return \AppBundle\Entity\TicketPromo
     */
    public function getTicketPromo()
    {
        return $this->ticketPromo;
    }

    /**
     * Set ticketAmount
     *
     * @param \AppBundle\Entity\TicketAmount $ticketAmount
     *
     * @return TicketPromoCondition
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
