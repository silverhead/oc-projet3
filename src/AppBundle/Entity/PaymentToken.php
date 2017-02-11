<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 11/02/2017
 * Time: 22:29
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class PaymentToken extends Token
{
}