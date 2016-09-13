<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Handler;

use AppBundle\Form\Model\Reservable;
use AppBundle\Form\Type\ReservationType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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


    public function __construct(FormFactory $formFactory, Reservable $reservationModel , RequestStack $request)
    {
        $this->form = $formFactory->create(ReservationType::class, $reservationModel);
        $this->request = $request->getCurrentRequest();
    }

    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm(){
        return  $this->form;
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