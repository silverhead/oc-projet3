<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    /**
     *@Route("/ajax/get/ticket_amoun_by_birthday.{_format}", defaults={"_format": "json"}, requirements={"_format": "json" }, name="ajax-get-ticket-amount-by-birthday", methods={"GET"})
     */
    public function ticketTypeListAction(Request $request)
    {
        $getDate = $request->get('birthday', null);

        if(null === $getDate){
            throw new ResourceNotFoundException("Resource not found!");
        }

        $birthday = \DateTime::createFromFormat('Y-m-d H:i', $getDate.' 00:00');

        $ticketInfo = $this->get('app.manager.ticket_information');
        $amount = $ticketInfo->getTicketPriceByBirthday($birthday);

        return new JsonResponse($amount);
    }
}
