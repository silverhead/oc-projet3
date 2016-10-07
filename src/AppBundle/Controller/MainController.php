<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $bookingHandler = $this->get('app.form.handler.booking');
        $form = $bookingHandler->getForm();

        if($bookingHandler->process($request)){
            return $this->redirectToRoute('user-informations');
        }

        return $this->render('main/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     *@Route("/ajax/get/ticket_type_list_by_date.json", name="ajax-get-ticket-type-list", methods={"GET"})
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
	 * @Route("/ajax/get/total_booking_amount.json", name="ajax-get-total-booking-amount", methods={"GET"})
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
