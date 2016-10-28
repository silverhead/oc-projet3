<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 27/10/16
 * Time: 17:18
 */

namespace AppBundle\Form\Handler;

use AppBundle\Manager\CheckAuthorOrderManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;



class CheckAuthorOrderFormHandler
{
	/**
	 * @var FormFactory
	 */
	private $formFactory;

	/**
	 * @var CheckAuthorOrderManager
	 */
	private $manager;

	/**
	 * @var FormInterface
	 */
	private $form;

	/**
	 * @var FormTypeInterface
	 */
	private $checkAuthorOrderFormType;

	public function __construct(
		FormFactory $formFactory,
		CheckAuthorOrderManager $manager,
		FormTypeInterface $checkAuthorOrderFormType)
	{
		$this->formFactory = $formFactory;

		$this->manager = $manager;

		$this->checkAuthorOrderFormType = $checkAuthorOrderFormType;

		$this->setForm();
	}

	private function setForm()
	{
		$this->form = $this->formFactory->create(get_class($this->checkAuthorOrderFormType), null);
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

		if(!$this->manager->checkEmail($this->form->getData())){
			foreach($this->manager->getErrors() as $errorMessage){
				$this->form->get('email')->addError(new FormError($errorMessage));
			}

			return false;
		}

		return true;
	}
}