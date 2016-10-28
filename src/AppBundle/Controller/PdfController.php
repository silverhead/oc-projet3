<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;

class PdfController extends Controller
{

    /**
     * @Route("/download/booking", name="download-booking", methods={"GET"})
     * @return Response
     */
    public function downloadAction()
    {
	    $pdfFact = $this->get("app.factory.ticket_pdf");
        $orderBridge = $this->get("app.bridge.order");

        $order = $orderBridge->getCurrent();

	    foreach($order->getOrderDetails() as $line){
		    $amountLabel = $line->getTicket()->getBooking()->getTicketType()->getLabel()
					        . ' - ' .
					        $line->getTicket()->getTicketAmount()->getLabel()
					        . ' : ' .
					        number_format($line->getTicket()->getAmount(), 2, ', ', ' ') . ' €'
		    ;

		    $customerName = strtoupper($line->getTicket()->getCustomer()->getLastName())
					        . ' ' .
					        ucfirst($line->getTicket()->getCustomer()->getFirstName())
		    ;

		    $pdfFact->createTicket(
			    $line->getTicket()->getBookingDate(),
			    $amountLabel,
			    $customerName,
			    $line->getTicket()->getCustomer()->getBirthday(),
			    $line->getTicket()->getSerialNumber()
		    );
	    }

	    $fileName = 'billets_musee_du_louvre_'.str_pad($order->getId(), 5, '0', STR_PAD_LEFT).'.pdf';

        return new Response(
	        $pdfFact->output($fileName)
        );
    }
}
