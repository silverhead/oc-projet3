<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketInfoController extends Controller
{
    /**
     * @Route("/vos-coordonnees", name="user-informations", methods={"GET", "POST"})
     */
    public function ticketInformationsAction(Request $request){
        $ticketInfoManager = $this->get('app.manager.ticket_information');
        $formHandler = $this->get('app.form.handler.ticket_information');

        if($formHandler->process($request)){
            return $this->redirectToRoute('check-order');
        }

        return $this->render('main/user-informations.html.twig', [
            'form' => $formHandler->getForm()->createView(),
            'booking' => $ticketInfoManager->getCurrentBooking()
        ]);
    }
}
