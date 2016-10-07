<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


interface BookingGetErrorsInterface
{
    /**
     * @return array of error messages if exists
     */
    public function getErrors();
}