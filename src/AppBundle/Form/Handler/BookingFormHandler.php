<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 10/09/2016
 *
 */

namespace AppBundle\Form\Handler;

use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Entity\BookingInterface;
use AppBundle\Manager\BookingManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @var FormTypeInterface
     */
    private $bookingFormType;

    /**
     * @var BookingEntityInterface
     */
    private $bookingEntity;

    /**
     * @var array
     */
    private $data;

    public function __construct(BookingManager $bookingManager ,FormFactory $formFactory, FormTypeInterface $bookingFormType, BookingEntityInterface $bookingEntity)
    {
        $this->bookingManager = $bookingManager;

        $this->formFactory = $formFactory;

        $this->bookingFormType = $bookingFormType;

        $this->bookingEntity = $bookingEntity;

        $this->setForm();
    }

    private function setForm(){
        $this->bookingManager->updateData($this->bookingEntity);//Set if the entity has stocked in memory

        $this->form = $this->formFactory->create(
            get_class($this->bookingFormType),
            $this->bookingEntity,
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

    public function process(Request $request){
        if('POST' !== $request->getMethod()){
            return false;
        }

        $this->form->handleRequest($request);

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
