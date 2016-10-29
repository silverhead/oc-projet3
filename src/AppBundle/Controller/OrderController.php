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

        $formHandler = $this->get("app.form.handler.check_order");

        if($formHandler->process($request)){
            return $this->redirectToRoute("payment-choice");
        }

        return $this->render('order/check-order.html.twig', [
            'booking' => $checkOrderManager->getCurrentBooking(),
            'form' => $formHandler->getForm()->createView()
        ]);
    }

    /**
     * @Route("/choix-paiement", name="payment-choice", methods={"GET", "POST"})
     */
    public function paymentChoiceAction(Request $request){
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

//	    $config = [
//		    'paypal_express_checkout' => [
//			    'return_url' => 'https://example.com/return-url',
//			    'cancel_url' => 'https://example.com/cancel-url',
////			    'useraction' => 'commit',
//		    ],
//	    ];
//
//	    $formPayPal = $this->createForm(ChoosePaymentMethodType::class, null, [
//		    'amount'          => number_format($order->getAmount(), 2),
//		    'currency'        => 'EUR',
//            'default_method' => 'payment_paypal',
//		    'predefined_data' => $config,
//	    ]);
//
//	    $formPayPal->handleRequest($request);
//
//	    if ($formPayPal->isSubmitted() && $formPayPal->isValid()) {
//		    $ppc = $this->get('payment.plugin_controller');
//		    $ppc->createPaymentInstruction($instruction = $formPayPal->getData());
//
//		    $checkOrderManager = $this->get("app.manager.check_order");
//		    $order = $checkOrderManager->getCurrentOrder();
//
//		    $order->setPaymentInstruction($instruction);
//
//		    $em = $this->getDoctrine()->getManager();
////		    $em->persist($order);
//		    $em->flush($order);
//
//		    return $this->redirect($this->generateUrl('payment-create', [
////			    'id' => $order->getId(),
//		    ]));
//	    }


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
