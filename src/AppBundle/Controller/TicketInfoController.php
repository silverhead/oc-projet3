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

//        dump($ticketInfoManager->getCurrentBooking());

        if($formHandler->process($request)){
            return $this->redirectToRoute('check-order');
        }

        return $this->render('booking/user-informations.html.twig', [
            'form' => $formHandler->getForm()->createView(),
            'booking' => $ticketInfoManager->getCurrentBooking()
        ]);
    }

//    /**
//     *@Route("/ajax/get/ticket_amount_by_birthday.{_format}", defaults={"_format": "json"}, requirements={"_format": "json" }, name="ajax-get-ticket-amount-by-birthday", methods={"GET"})
//     */
//    public function ticketTypeListAction(Request $request)
//    {
//        $getDate    = $request->get('birthday', null);
//        $getSpecialAmount  = (bool) $request->get('specialAmount', 0);
//
//        if(null === $getDate){
//            throw new ResourceNotFoundException("Resource not found!");
//        }
//
//        $birthday = \DateTime::createFromFormat('Y-m-d H:i', $getDate.' 00:00');
//
//        $ticketInfoManager = $this->get('app.manager.ticket_information');
//        $amount = $ticketInfoManager->getTicketPriceByBirthday($birthday, $getSpecialAmount);
//
//        return new JsonResponse($amount);
//    }

    /**
     *@Route("/ajax/get/ticket_amount_by_birthday.{_format}", defaults={"_format": "json"}, requirements={"_format": "json" }, name="ajax-get-ticket-amount-by-birthday", methods={"GET"})
     */
    public function ajaxTicketAmountInfoAction(Request $request)
    {
        $getDate    = $request->get('birthday', null);
        $getSpecialAmount  = (bool) $request->get('specialAmount', 0);

        if(null === $getDate){
            throw new ResourceNotFoundException("Resource not found!");
        }

        $birthday = \DateTime::createFromFormat('Y-m-d H:i', $getDate.' 00:00');

        $ticketInfoManager = $this->get('app.manager.ticket_information');

        $ticket = $ticketInfoManager->getTicketByBirthday($birthday, $getSpecialAmount);

        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($ticket, 'json'));
    }
}
