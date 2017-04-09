<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Payment;
use AppBundle\Entity\PaymentDetails;
use AppBundle\Form\PaymentType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
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
        return $this->render('order/payment-choice.html.twig', [
            'order' => $order,
            'stripePublishableKey' => $this->container->getParameter('stripe.publishable_key')
        ]);
    }

    /**
     * @Route("/paiement/{choice}", name="order-payment", methods={"GET"})
     */
    public function orderPaymentAction(Request $request, $choice)
    {
//        dump($choice);

        $response = $this->redirect('payment-choice');//return to the payment choice if nothing corresponding

        switch ($choice){
            case 'paypal':
                $response = $this->paypalPayment();
                break;
            case 'stripe':
                $response = $this->stripePayment($request);
                break;
        }

        return $response;
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

    private function paypalPayment()
    {
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

        $config = array(
            'mode' => 'sandbox',
            'acct1.UserName' => 'jb-us-seller_api1.paypal.com',
            'acct1.Password' => 'WX4WTU3S8MY44S7F'
        );
        $service  = new PayPalAPIInterfaceServiceService($config);


//        $gatewayName = 'paypal_express_checkout';
//
//        $storage = $this->get('payum')->getStorage(PaymentDetails::class);
//
//
//        /** @var $payment Order */
//        $payment = $storage->create();
//        $payment['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';
//        $payment['PAYMENTREQUEST_0_AMT'] = $order->getAmount();
//        $payment['AUTHORIZE_TOKEN_USERACTION'] = '';
//        $storage->update($payment);
//
//        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
//            $gatewayName,
//            $payment,
//            'order-confirmed'
//        );
//
////        $payment['INVNUM'] = $payment->getId();
////        $storage->update($payment);
//
//        return $this->redirect($captureToken->getTargetUrl());
    }

    private function stripePayment($request)
    {
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

        $token = $request->get('stripeToken');
//        $type = $request->get('stripeTokenType');
//        $customerEmail = $request->get('stripeEmail');
//        dump($request);exit();

        $stripeSK = $this->container->getParameter('stripe.secret_key');
        $amount = $order->getAmount() * 100;

        $test = \Stripe\Stripe::setApiKey($stripeSK);

        $charge = \Stripe\Charge::create(array('amount' => $amount, 'currency' => 'eur', 'source' => $token));

        dump($charge);exit();

        return new Response($charge);



//        $gatewayName = 'stripe';
//
//        $payum = $this->get('payum');
//
//        $storage = $payum->getStorage(PaymentDetails::class);
//
//        /** @var $payment PaymentDetails */
//        $payment = $storage->create();
//        $payment["amount"] = $order->getAmount() * 100;// in centime
//        $payment["currency"] = 'EUR';
////        $payment["local"] = ['save_card' => true, 'customer' => ['plan' => 'gold']];
//        $storage->update($payment);
//
//        $captureToken = $payum->getTokenFactory()->createCaptureToken(
//            $gatewayName,
//            $payment,
//            'order-confirmed'
//        );

//        return $this->redirect($captureToken->getTargetUrl());

    }
}
