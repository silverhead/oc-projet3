<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        $reservationHandler = $this->get('app.form.handler.reservation');
        $form = $reservationHandler->getForm();

        if($reservationHandler->process()){
            $data = $reservationHandler->getData();
            $this->get('session')->set('reservation', $data);
            return $this->redirectToRoute('user-informations');
        }


        return $this->render('main/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/vos-coordonnees", name="user-informations", methods={"GET"})
     */
    public function userInformationsAction(){
        return $this->render('main/user-informations.html.twig');
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
