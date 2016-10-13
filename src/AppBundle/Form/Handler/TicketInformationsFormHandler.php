<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 13/10/16
 * Time: 11:23
 */

namespace AppBundle\Form\Handler;

use AppBundle\Manager\TicketInformationsManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class TicketInformationsFormHandler
{
    /**
     * @var TicketInformationsManagerInterface
     */
    private $ticketInfosManager;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormTypeInterface
     */
    private $ticketInfosType;

    public function __construct(
        TicketInformationsManagerInterface $ticketInfosManager ,
        FormFactory $formFactory,
        FormTypeInterface $ticketInfosType)
    {
        $this->ticketInfosManager = $ticketInfosManager;

        $this->formFactory = $formFactory;

        $this->ticketInfosType = $ticketInfosType;

        $this->setForm();
    }

    private function setForm(){
        $bookingEntity = $this->ticketInfosManager->getCurrentBooking();

        $this->form = $this->formFactory->create(
            get_class($this->ticketInfosType),
            $bookingEntity
        );
    }

    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm(){
        return  $this->form;
    }

    public function process(Request $request){
        if('POST' !== $request->getMethod()){
            return false;
        }

        $this->form->handleRequest($request);

        if(!$this->form->isSubmitted() || !$this->form->isValid()){
            return false;
        }

        $this->ticketInfosManager->save($this->form->getData());

        return true;
    }
}