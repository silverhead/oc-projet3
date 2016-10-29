<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 28/10/16
 * Time: 13:57
 */

namespace AppBundle\Bridge;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\TicketType;
use Doctrine\ORM\EntityNotFoundException;

class BridgeBookingORM implements BridgeBookingORMInterface
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * @var SessionInterface
	 */
	private $session;


	/**
	 * @var \AppBundle\Repository\BookingRepository|\Doctrine\Common\Persistence\ObjectRepository
	 */
	private $bookingRepo;

	/**
	 * @var \AppBundle\Repository\TicketTypeRepository|\Doctrine\Common\Persistence\ObjectRepository
	 */
	private $ticketTypeRepo;


	/**
	 * @var \AppBundle\Repository\TicketAmountRepository|\Doctrine\Common\Persistence\ObjectRepository
	 */
	private $ticketAmount;

	/**
	 * @var array
	 */
	private $errors;


	public function __construct(EntityManagerInterface $em, SessionInterface $session)
	{

		$this->em  = $em;
		$this->session = $session;
		$this->bookingRepo      = $this->em->getRepository("AppBundle:Booking");
		$this->ticketTypeRepo   = $this->em->getRepository("AppBundle:TicketType");
		$this->ticketAmount     = $this->em->getRepository("AppBundle:TicketAmount");
	}

	public function find($id = null)
	{
		if(null === $id){
			return new Booking();
		}

		if(null === $booking = $this->bookingRepo->find($id)){

			$this->bridgeBookingORM->removeCurrent();//delete the id in session

			throw new EntityNotFoundException("Not Booking Entity found with the id ".$id."!");
		}

		return $booking;
	}

	public function getCurrentBooking()
	{
		$bookingId = $this->session->get('booking', null);

		return $this->find($bookingId);
	}

	public function getCurrent()
	{
		$this->getCurrentBooking();
	}


	public function getTicket()
	{
		return new Ticket();
	}

	public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets)
	{
		return  $this->bookingRepo->findAllFullBookingInPeriod($start, $end, $maxNumberOfBookedTickets);
	}

	/**
	 * find ticket type available for the date and hour
	 *
	 * @param \DateTime $date
	 * @return array
	 */
	public function findTicketTypeAvailableFor(\DateTime $date)
	{
		return $this->ticketTypeRepo->findTicketTypeAvailableFor($date->format('H'));
	}

	public function getBookingAmount($ticketTypeId, $ticketQuantity, \DateTime $birthday = null)
	{
		$ticketType     = $this->ticketTypeRepo->find($ticketTypeId);

		$ticketAmount = $this->getTicketAmountByTicketType($ticketType, $birthday);

		return $ticketAmount * $ticketQuantity;
	}

	public function getTicketAmountByTicketType(TicketType $ticketType, \DateTime $birthday = null)
	{
		if(null === $birthday){
			$ticketAmount   = $this->ticketAmount->findOneByDefault(true);
		}
		else{
			$ticketAmount   = $this->ticketAmount->findOneByAge($birthday);
		}

		return $ticketAmount->getAmount() * ($ticketType->getPercent() / 100);
	}

	/**
	 * @param BookingEntityInterface $booking
	 * @return bool
	 */
	public function save(BookingEntityInterface $booking)
	{
		try{

			$this->setTickets($booking);//if the booking has tickets save that

			$this->em->persist($booking);
			$this->em->flush();

			$this->session->set('booking', $booking->getId());

			return true;
		}
		catch(\Exception $e){
			$this->errors[] = "Une erreur est intervenue lors de ".
				"l'enregistrement dans la base ! Si le ".
				"problème persiste veuillez contacter ".
				"l'administrateur du site";
			return false;
		}
	}

	public function setTickets($booking){
		if(null !== $booking->getTickets()){
			foreach ($booking->getTickets() as $ticket){
				$ticketAmount = $this->em->getRepository("AppBundle:TicketAmount")->findOneByAge($ticket->getCustomer()->getBirthday());
				$ticket->setTicketAmount($ticketAmount);
			}
		}
	}

	public function deleteTickets(BookingEntityInterface $booking)
	{
		//delete all old tickets
		foreach ($booking->getTickets() as $ticket){
			$booking->removeTicket($ticket);
		}
		$this->em->persist($booking);
		$this->em->flush();
	}

	/**
	 * Return a array of error messages
	 * @return array
	 */
	public function getErrors(){
		return $this->errors;
	}


	public function removeCurrent(){
		$this->session->remove('booking');
	}
}