<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TicketType
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketTypeRepository")
 */
class TicketType
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     */
    protected $label;

    /**
     * @var float
     *
     * @ORM\Column(type="float", length=5, scale=2, nullable=false)
     */
    protected $percent;

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
     * @return TicketType
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
     * Set percent
     *
     * @param float $percent
     *
     * @return TicketType
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }
}
