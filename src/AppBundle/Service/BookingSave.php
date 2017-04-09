<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 27/09/2016
 *
 */

namespace AppBundle\Service;

use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingSave implements BookingSaveAndGetErrorsInterface
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
     * @var array
     */
    private $errorMessages;

    public function __construct
    (
    	EntityManagerInterface $em,
	    SessionInterface $session
    )
    {
        $this->em  = $em;
        $this->session  = $session;
    }

    /**
     * @param BookingEntityInterface $booking
     * @return bool
     */
    public function save(BookingEntityInterface $booking)
    {

//        dump($booking);

        try{

            $this->setTickets($booking);//if the booking has tickets save that

            $this->em->persist($booking);
            $this->em->flush();

            $this->session->set('booking', $booking->getId());

            return true;
        }
        catch(\Exception $e){
            $this->errorMessages[] = "Une erreur est intervenue lors de ".
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
        return $this->errorMessages;
    }
}