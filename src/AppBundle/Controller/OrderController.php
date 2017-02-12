<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{

    /**
     * @Route("/verification-commande", name="check-order", methods={"GET", "POST"})
     */
    public function checkOrderAction(Request $request){
        $checkOrderManager = $this->get('app.manager.check_order');

        $promo = $checkOrderManager->getAutoPromo();

        $booking = $checkOrderManager->getCurrentBooking();


        $tickets = [];
        $total = $booking->getAmount();

        $ticketAmountsIncluded = [];

        if(null !== $promo){
            foreach ($promo->getTicketPromoConditions() as $ticketPromoCondition ){
                $ticketAmountsIncluded[$ticketPromoCondition->getTicketAmount()->getId()] =  $ticketPromoCondition->getCount();
            }

            $total += $promo->getAmount();
        }

        foreach($booking->getTickets() as $ticket){
            $idTicketAmount = $ticket->getTicketAmount()->getId();
            $ticketAmount = $ticket->getAmount();
            $strike = false;

            if(isset($ticketAmountsIncluded[$idTicketAmount]) && $ticketAmountsIncluded[$idTicketAmount] > 0){
                $strike = true;

                $total -= $ticketAmount;

                $ticketAmountsIncluded[$idTicketAmount] -= 1;
            }

            $tickets[] = (object) [
                'ticket' => $ticket,
                'striked' => $strike
            ];

        }

        $formHandler = $this->get("app.form.handler.check_order");


        if($formHandler->process($request)){
            return $this->redirectToRoute("payment-choice");
        }

        return $this->render('order/check-order.html.twig', [
            'booking' => $booking,
            'tickets' => $tickets,
            'form' => $formHandler->getForm()->createView(),
            'promo' => $promo,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/choix-paiement", name="payment-choice", methods={"GET", "POST"})
     */
    public function paymentChoiceAction(Request $request){
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

        $gatewayName = 'paypal_express_checkout';

        $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($order->getAmount()); // 1.23 EUR
        $payment->setDescription('A description');
        $payment->setClientId($order->getId());
        $payment->setClientEmail($order->getEmail());
        $payment->setDetails(array(
            'AUTHORIZE_TOKEN_USERACTION' => '',
        ));

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'order-confirmed' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());


        return $this->render('order/payment-choice.html.twig', [
            'order' => $order,
//        	'formPayPal' => $formPayPal->createView()
        ]);
    }

    /**
     * @Route("/confirmation-commande", name="order-confirmed", methods={"GET"})
     */
    public function orderConfirmedAction(){
        return $this->render('order/order-confirmed.html.twig');
    }

    /**
     * @Route("/annulation-commande", name="order-canceled", methods={"GET"})
     */
    public function orderCanceledAction(){
        return $this->render('order/order-canceled.html.twig');
    }

	/**
	 * @Route("/recommencer-ou-terminer-votre-commande", name="check-author-order", methods={"GET", "POST"})
	 *
	 */
    public function checkAuthorOrderAction(Request $request)
    {
	    $formHandler = $this->get('app.form.handler.check_author_order');

	    $form = $formHandler->getForm();


	    if($formHandler->process($request)){
		    return $this->redirect($this->generateUrl('check-order'));
	    }


	    return $this->render('order/check-author-order.html.twig', [
	    	'form' => $form->createView()
	    ]);
    }


	/**
	 * @Route("/nouvelle-reservation", name="new-booking", methods={"GET"})
	 */
    public function newOrderAction()
    {
		$orderBridge = $this->get('app.bridge.order');
	    $bookingBridge = $this->get('app.bridge.booking');

	    $orderBridge->removeCurrent();
	    $bookingBridge->removeCurrent();

	    return $this->redirect($this->generateUrl('homepage'));
    }
}
