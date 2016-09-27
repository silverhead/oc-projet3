<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


interface FindBookingsInterface
{

    /**
     * Get the Booking model infos
    */
    public function find($id);

    /**
     * Find all full booking in a period with a number to indicate the max booking for a day
     *
     * @param \DateTime $start
     * @param \DateTime $end
     * @param $maxNumberOfBookedTickets
     * @return mixed
     */
    public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets);
}