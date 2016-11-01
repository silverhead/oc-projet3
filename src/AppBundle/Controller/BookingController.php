<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/10/16
 * Time: 23:06
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class BookingController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

//	    $orderMgr = $this->get("app.manager.order");
//
//	    if($orderMgr->hasCurrentOrder() && $orderMgr->isStandby()){
//		    return $this->redirectToRoute('check-order');
//	    }


        $bookingHandler = $this->get('app.form.handler.booking');

        if($bookingHandler->process($request)){
            return $this->redirectToRoute('user-informations');
        }

        $errors = $bookingHandler->getErrorMessages();

        if(count($errors) > 0 ){
            $this->addFlash("error", implode("<br />", $errors));
        }

        return $this->render('booking/index.html.twig', array(
            'form' => $bookingHandler->getForm()->createView()
        ));
    }

    /**
     *@Route("/ajax/get/ticket_type_list_by_date.{_format}", defaults={"_format": "json"}, requirements={"_format": "json" }, name="ajax-get-ticket-type-list", methods={"GET"})
     */
    public function ticketTypeListAction(Request $request)
    {
        $getDate = $request->get('date', null);

        if(null === $getDate){
            throw new ResourceNotFoundException("Resource not found!");
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i', $getDate.' 00:00');

        $bookingManager = $this->get('app.manager.booking');
        $ticketTypes = $bookingManager->getTicketTypeAvailableFor($date);

        $serializer = $this->get('serializer');
        return new JsonResponse($serializer->serialize($ticketTypes, 'json'));
    }

    /**
     * Return the total booking amount in AJAX call
     *
     * @param Request $request birthday
     *
     * @Route("/ajax/get/total_booking_amount.{_format}", defaults={"_format": "json"}, requirements={"_format" = "json" }, name="ajax-get-total-booking-amount", methods={"GET"})
     */
    public function getBookingAmountsAction(Request $request)
    {
        $ticketQuantity = $request->get('ticketQuantity', null);
        $ticketTypeId = $request->get('ticketTypeId', null);

        if(null === $ticketQuantity || null === $ticketTypeId){
            throw new ResourceNotFoundException("ticketQuantity and/or ticketTypeId not found!");
        }

        $bookingManager = $this->get('app.manager.booking');
        $amount = $bookingManager->getBookingAmount($ticketTypeId, $ticketQuantity);

        return new JsonResponse($amount);
    }
}