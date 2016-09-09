<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/vos-coordonnees", name="user-informations", methods={"POST"})
     */
    public function userInformationsAction(){
        return $this->render('main/user-informations.html.twig');
    }

    /**
     * @Route("/verification-commande", name="check-order", methods={"POST"})
     */
    public function checkOrderAction(){}

    /**
     * @Route("/choix-paiement", name="paiement-choice", methods={"POST"})
     */
    public function paymentChoiceAction(){}

    /**
     * @Route("/confirmation-commande", name="order-confirmed", methods={"POST"})
     */
    public function orderConfirmedAction(){}

    /**
     * @Route("/annulation-commande", name="order-canceled", methods={"POST"})
     */
    public function orderCanceledAction(){}
}
