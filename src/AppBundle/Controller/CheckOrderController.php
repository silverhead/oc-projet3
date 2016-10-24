<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CheckOrderController extends Controller
{

    /**
     * @Route("/verification-commande", name="check-order", methods={"GET", "POST"})
     */
    public function checkOrderAction(Request $request){
        $checkOrderManager = $this->get('app.manager.check_order');

        $formHandler = $this->get("app.form.handler.check_order");

        $formHandler->process($request);

        return $this->render('checkOrder/check-order.html.twig', [
            'booking' => $checkOrderManager->getCurrentBooking(),
            'form' => $formHandler->getForm()->createView()
        ]);
    }
}
