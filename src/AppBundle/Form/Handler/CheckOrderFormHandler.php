<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 14:02
 */

namespace AppBundle\Form\Handler;

use AppBundle\Manager\CheckOrderManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;


class CheckOrderFormHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var CheckOrderManager
     */
    private $manager;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormTypeInterface
     */
    private $bookingFormType;

    public function __construct(
        FormFactory $formFactory,
        CheckOrderManager $manager,
        FormTypeInterface $bookingFormType
    )
    {
        $this->formFactory = $formFactory;

        $this->manager = $manager;

        $this->bookingFormType = $bookingFormType;

        $this->setForm();
    }

    private function setForm()
    {
        $entity = $this->manager->getCurrentOrder();
        $this->form = $this->formFactory->create(get_class($this->bookingFormType), $entity);
    }


    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return  $this->form;
    }

    public function process(Request $request)
    {
        if('POST' !== $request->getMethod()){
            return false;
        }

        $this->form->handleRequest($request);

        if(!$this->form->isSubmitted() || !$this->form->isValid()){
            return false;
        }

        if(!$this->manager->saveOrder($this->form->getData())){
            foreach($this->manager->getErrors() as $errorMessage){
                $this->form->addError(new FormError($errorMessage));
            }

	        return false;
        }

        return true;
    }
}