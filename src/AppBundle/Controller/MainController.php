<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Types\JsonArrayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            throw new ResourceNotFoundException("Ressource non trouvé");
        }

        $date = \DateTime::createFromFormat('Y-m-d', $getDate);

        $bookingManager = $this->get('app.manager.booking');

        $ticketTypes = $bookingManager->getTicketTypeAvailableFor($date);

        $serializer = $this->get('serializer');

	    return new JsonResponse($serializer->serialize($ticketTypes, 'json'));
        //return new Response($serializer->serialize($ticketTypes, 'json'));
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
