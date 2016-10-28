<?php

namespace AppBundle\Bridge;

/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 15:34
 */
interface BridgeORMInterface
{
    public function find($id);

    public function getCurrent();

    public function getErrors();
}