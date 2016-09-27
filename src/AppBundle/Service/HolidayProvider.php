<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


use Yasumi\Yasumi;

class HolidayProvider implements HolidayProviderInterface
{
    public function getHolidayDatesFor($year)
    {
        $holidayProvider  = Yasumi::create('France',$year);

        return $holidayProvider->getHolidayDates();
    }

}