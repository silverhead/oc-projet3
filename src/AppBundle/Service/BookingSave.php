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
        try{
            //remove all old tickets from booking
            $tickets = $booking->getTickets();

            foreach($tickets as $ticketToDel){
                $booking->removeTicket($ticketToDel);
            }

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

    /**
     * Return a array of error messages
     * @return array
     */
    public function getErrors(){
        return $this->errorMessages;
    }
}