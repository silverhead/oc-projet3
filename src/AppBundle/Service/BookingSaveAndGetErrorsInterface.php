<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;

use AppBundle\Entity\BookingEntityInterface;

interface BookingSaveAndGetErrorsInterface extends BookingSaveInterface, BookingGetErrorsInterface
{
    public function save(BookingEntityInterface $booking);

    public function getErrors();
}