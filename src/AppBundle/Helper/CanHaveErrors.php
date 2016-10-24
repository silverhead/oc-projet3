<?php

namespace AppBundle\Helper;

/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 22:39
 */
interface CanHaveErrors
{
    /**
     * @return array of error messages if exists
     */
    public function getErrors();
}