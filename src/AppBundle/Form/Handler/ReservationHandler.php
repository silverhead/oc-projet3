<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReservationHandler
 * @package AppBundle\Form\Handler
 *
 * Use for control the reservation page
 *
 */
class ReservationHandler
{

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Request
     */
    private $request;


    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function process(){
        if('POST' !== $this->request->getMethod()){
            return false;
        }

        $this->form->handleRequest($this->request);

        if(!$this->form->isSubmitted() || !$this->form->isValid()){
            return false;
        }

        return true;
    }
}