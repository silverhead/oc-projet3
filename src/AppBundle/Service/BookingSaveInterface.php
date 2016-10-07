<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\BookingEntityInterface;

interface BookingSaveInterface
{
    /**
     * Save the model of Booking
     * @param BookingEntityInterface $booking
     * @return mixed
     */
    public function save(BookingEntityInterface $booking);
}