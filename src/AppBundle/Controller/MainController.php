<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MainController extends Controller
{

    /**
     * @Route("/verification-commande", name="check-order", methods={"GET"})
     */
    public function checkOrderAction(){
        $ticketManager = $this->get('app.manager.ticket_information');
        return $this->render('main/check-order.html.twig', [
            'booking' => $ticketManager->getCurrentBooking()
        ]);
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
