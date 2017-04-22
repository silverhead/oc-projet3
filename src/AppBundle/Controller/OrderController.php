<?php

namespace AppBundle\Controller;

use PayPal\Api\Payment;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("/paiement/{choice}", name="order-payment", methods={"GET", "POST"})
     */
    public function orderPaymentAction(Request $request, $choice)
    {
//        dump($choice);

        $response = $this->redirect('payment-choice');//return to the payment choice if nothing corresponding

        switch ($choice){
            case 'paypal':
                $response = $this->paypalPayment($request);
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

    private function paypalPayment(Request $request)
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->getParameter('paypal_client_id'),     // ClientID
                $this->getParameter('paypal_client_secret')      // ClientSecret
            )
        );

        // Get payment object by passing paymentId
        $paymentId = $request->get('paymentID');
        $payerId = $request->get('payerID');

        $payment = Payment::get($paymentId, $apiContext);

        // Execute payment with payer id
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = json_decode($payment->execute($execution, $apiContext));

            $state = $result->state;

            $orderBridge = $this->get('app.bridge.order');

            if($state != 'approved'){

                //todo voir les autres possibilités
                // par exemple gérer -> PAYMENT_ALREADY_DONE

                $orderBridge->cancelPayment('paypal', $paymentId);

                return $this->redirectToRoute('order-canceled');
            }

            $orderBridge->validPayment('paypal', $paymentId);

            return $this->redirectToRoute('order-confirmed');

        } catch (PayPalConnectionException $ex) {
            $data = json_decode($ex->getData());
            $this->addFlash("error", "<strong>".$ex->getCode()." :".$data->name."</strong><p>".$data->message."</p>");

            return $this->redirectToRoute('payment-choice');

        } catch (\Exception $ex) {
            $this->addFlash("error", "<strong>".$ex->getCode()."</strong><p>".$ex->getMessage()."</p>");

            return $this->redirectToRoute('payment-choice');
        }
    }

    private function stripePayment(Request $request)
    {
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

        $token = $request->get('stripeToken');
        $stripeSK = $this->container->getParameter('stripe.secret_key');
        $stripePK = $this->container->getParameter('stripe.publishable_key');
        $amount = $order->getAmount() * 100;

        $stripe = array(
            "secret_key"      => $stripeSK,
            "publishable_key" => $stripePK
        );

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);

            $customer = \Stripe\Customer::create(array(
                'email' => $order->getEmail(),
                'card'  => $token
            ));

            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $amount,
                'currency' => 'eur'
            ));

            $data = (object) $charge->jsonSerialize();
            $paymentId = $data->id;
            $state = $data->status;

            $orderBridge = $this->get('app.bridge.order');

            if($state != 'succeeded'){
                //todo voir les autres possibilités
                $orderBridge->cancelPayment('stripe', $paymentId);

                return $this->redirectToRoute('order-canceled');
            }

            $orderBridge->validPayment('stripe', $paymentId);

            return $this->redirectToRoute('order-confirmed');

        } catch (\Exception $ex) {
            $this->addFlash("error", "<strong>".$ex->getCode()."</strong><p>".$ex->getMessage()."</p>");

            return $this->redirectToRoute('payment-choice');
        }
    }
}
