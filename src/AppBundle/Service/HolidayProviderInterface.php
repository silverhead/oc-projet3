<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


interface HolidayProviderInterface
{

    /**
     * Get a dates list of holiday for the year
     *
     * @return array
     */
    public function getHolidayDatesFor($year);
}