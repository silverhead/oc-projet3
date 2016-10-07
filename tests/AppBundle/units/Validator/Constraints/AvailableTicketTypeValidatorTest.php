<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 07/10/16
 * Time: 09:27
 */

namespace AppBundle\units\Validator\Constraints;


use AppBundle\Entity\TicketType;
use AppBundle\Manager\BookingManager;
use AppBundle\Validator\Constraints\AvailableTicketType;
use AppBundle\Validator\Constraints\AvailableTicketTypeValidator;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\DomCrawler\Tests\Field\FormFieldTest;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class AvailableValidatorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException   Doctrine\ORM\EntityNotFoundException
	 * @expectedExceptionMessage  The field value have to implement a BookingEntityInterface!
	 */
	public function testIncorrectTicketTypeParam()
	{
		$bookingManager = $this->createMock(BookingManager::class);

		$constraint = $this->createMock(AvailableTicketType::class);

		$badValue = new \stdClass();

		$validator = new AvailableTicketTypeValidator($bookingManager);

		$validator->validate($badValue, $constraint); //the method require a TicketType entity as value
	}

	public function testCorrectTicketTypeParam()
	{
		$date  = new \DateTime();

		$fakeTicketTypeEntity = $this->createMock(TicketType::class);
		$fakeTicketTypeEntity->expects($this->once())
			->method("getId")
			->will($this->returnValue(1))
		;

		$fakeTicketTypeEntityForm = $this->createMock(TicketType::class);
		$fakeTicketTypeEntityForm->expects($this->once())
			->method("getId")
			->will($this->returnValue(1))
		;

		$bookingManager = $this->createMock(BookingManager::class);
		$bookingManager->expects($this->once())
			->method("getTicketTypeAvailableFor")
			->with($this->equalTo($date))
			->will($this->returnValue(array($fakeTicketTypeEntity)))
		;

		$constraint = $this->createMock(AvailableTicketType::class);
		$constraint->expects($this->once())
			->method("getFieldDate")
			->will($this->returnValue('BookingDate'))
		;

		$fakeFormField = $this->createMock(Form::class);
		$fakeFormField->expects($this->once())
			->method("getData")
			->will($this->returnValue($date))
		;
		$fakeForm = $this->createMock(Form::class);
		$fakeForm->expects($this->once())
			->method("get")
			->will($this->returnValue($fakeFormField))
		;

		$fakeContext = $this->createMock(ExecutionContextInterface::class);
		$fakeContext
			->expects($this->once())
			->method("getRoot")
			->will($this->returnValue($fakeForm))
		;

		$validator = new AvailableTicketTypeValidator($bookingManager);
		$validator->initialize($fakeContext);

		$validator->validate($fakeTicketTypeEntityForm, $constraint);
	}

	public function testNotAvailableTicketTypeParam()
	{
		$date  = new \DateTime();

		$fakeTicketTypeEntityFromForm = $this->createMock(TicketType::class);
		$fakeTicketTypeEntityFromForm->expects($this->any())
			->method("getId")
			->will($this->returnValue(1))
		;
		$fakeTicketTypeEntityAvaible = $this->createMock(TicketType::class);
		$fakeTicketTypeEntityAvaible->expects($this->any())
			->method("getId")
			->will($this->returnValue(2))
		;

		$bookingManager = $this->createMock(BookingManager::class);
		$bookingManager->expects($this->once())
			->method("getTicketTypeAvailableFor")
			->with($this->equalTo($date))
			->will($this->returnValue(array($fakeTicketTypeEntityAvaible)))
		;

		$fakeFormField = $this->createMock(Form::class);
		$fakeFormField->expects($this->once())
			->method("getData")
			->will($this->returnValue($date))
		;
		$fakeForm = $this->createMock(Form::class);
		$fakeForm->expects($this->once())
			->method("get")
			->will($this->returnValue($fakeFormField))
		;

		$constraint = $this->createMock(AvailableTicketType::class);
		$constraint->expects($this->once())
			->method("getFieldDate")
			->will($this->returnValue('BookingDate'))
		;
		$constraint->expects($this->once())
			->method("getMessage")
			->will($this->returnValue("Le type de ticket n'est pas disponible pour la date sélectionnée !"))
		;


		$fakeViolation = $this->createMock(ConstraintViolationBuilderInterface::class);
		$fakeViolation
			->expects($this->once())
			->method("AddViolation")
		;

		$fakeContext = $this->createMock(ExecutionContextInterface::class);
		$fakeContext
			->expects($this->once())
			->method("getRoot")
			->will($this->returnValue($fakeForm))
		;

		$fakeContext
			->expects($this->once())
			->method("buildViolation")
			->with($this->equalTo("Le type de ticket n'est pas disponible pour la date sélectionnée !"))
			->will($this->returnValue($fakeViolation))
		;

		$validator = new AvailableTicketTypeValidator($bookingManager);
		$validator->initialize($fakeContext);

		$validator->validate($fakeTicketTypeEntityFromForm, $constraint);
	}
}