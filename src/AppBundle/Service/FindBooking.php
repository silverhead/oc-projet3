<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FindBooking implements FindBookingsInterface
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
	 * FindBooking constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param SessionInterface $session
	 */
    public function __construct
    (
    	EntityManagerInterface $em,
	    SessionInterface $session
    )
    {
        $this->em               = $em;
        $this->session          = $session;
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
           throw new EntityNotFoundException("Not Booking Entity found with the id ".$id."!");
        }

        return $booking;
    }

    public function getCurrentBooking()
    {
        $bookingId = $this->session->get('booking', null);

        return $this->find($bookingId);
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

		if(null === $birthday){
			$ticketAmount   = $this->ticketAmount->findOneByDefault(true);
		}
		else{
			$ticketAmount   = $this->ticketAmount->findOneByAge($birthday);
		}

		$ticketAmount = $ticketAmount->getAmount() * ($ticketType->getPercent() / 100);

		return $ticketAmount * $ticketQuantity;
	}
}