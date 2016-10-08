<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\TicketInformationsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/vos-coordonnees", name="user-informations", methods={"GET"})
     */
    public function ticketInformationsAction(){
        $session = $this->get('session');

        $bookingId = $session->get('booking');

        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')->find($bookingId);

        for($i = 0; $i < $booking->getTicketQuantity(); $i++){
            $booking->addTicket( new Ticket());
        }

        $form = $this->createForm(TicketInformationsType::class, $booking);

        return $this->render('main/user-informations.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/verification-commande", name="check-order", methods={"POST"})
     */
    public function checkOrderAction(){
        return $this->render('main/check-order.html.twig');
    }

    /**
     * @Route("/choix-paiement", name="payment-choice", methods={"POST"})
     */
    public function paymentChoiceAction(){
        return $this->render('main/payment-choice.html.twig');
    }

    /**
     * @Route("/confirmation-commande", name="order-confirmed", methods={"GET"})
     */
    public function orderConfirmedAction(){
        return $this->render('main/order-confirmed.html.twig');
    }

    /**
     * @Route("/annulation-commande", name="order-canceled", methods={"GET"})
     */
    public function orderCanceledAction(){
        return $this->render('main/order-canceled.html.twig');
    }
}
