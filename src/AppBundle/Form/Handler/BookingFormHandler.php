<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Handler;

use AppBundle\Entity\BookingInterface;
use AppBundle\Manager\BookingManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ReservationHandler
 * @package AppBundle\Form\Handler
 *
 * Use for control the reservation page
 *
 */
class BookingFormHandler
{
    /**
     * @var BookingManager
     */
    private $bookingManager;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $data;

    public function __construct(BookingManager $bookingManager ,FormFactory $formFactory, RequestStack $request)
    {
        $this->bookingManager = $bookingManager;

        $this->formFactory = $formFactory;

        $this->request = $request->getCurrentRequest();

        $this->setForm();
    }

    private function setForm(){
        $this->form = $this->formFactory->create(
            get_class($this->bookingManager->getFormType()),
            $this->bookingManager->getEntity(),
            [
                'booking_manager' => $this->bookingManager
            ]
        );
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

        $this->bookingManager->save($this->form->getData());

        return true;
    }

    public function getData()
    {
        return $this->data;
    }
}