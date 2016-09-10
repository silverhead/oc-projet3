<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketAmount
 *
 * @ORM\Table(name="ticket_amount")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketAmountRepository")
 */
class TicketAmount
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="ageConditionStart", type="integer")
     */
    private $ageConditionStart;

    /**
     * @var int
     *
     * @ORM\Column(name="ageConditionEnd", type="integer")
     */
    private $ageConditionEnd;


    /**
     * Get id
     *
     * @return int
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
     * @return TicketAmount
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
     * @return TicketAmount
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
     * Set ageConditionStart
     *
     * @param integer $ageConditionStart
     *
     * @return TicketAmount
     */
    public function setAgeConditionStart($ageConditionStart)
    {
        $this->ageConditionStart = $ageConditionStart;

        return $this;
    }

    /**
     * Get ageConditionStart
     *
     * @return int
     */
    public function getAgeConditionStart()
    {
        return $this->ageConditionStart;
    }

    /**
     * Set ageConditionEnd
     *
     * @param integer $ageConditionEnd
     *
     * @return TicketAmount
     */
    public function setAgeConditionEnd($ageConditionEnd)
    {
        $this->ageConditionEnd = $ageConditionEnd;

        return $this;
    }

    /**
     * Get ageConditionEnd
     *
     * @return int
     */
    public function getAgeConditionEnd()
    {
        return $this->ageConditionEnd;
    }
}

