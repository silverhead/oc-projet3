<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;

class PdfController extends Controller
{
    /**
     * @Route("/test.pdf", name="test-pdf", methods={"GET"})
     * @return Response
     */
    public function indexAction()
    {
        $orderBridge = $this->get("app.bridge.order");
        $order = $orderBridge->getCurrent();

        $orderLines = $order->getOrderDetails();


        // set style for barcode
        $style = array(
            'border' => true,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $pdf = $this->get("white_october.tcpdf")->create();

        $pdf->SetFont('helvetica', '', 11);



//        $txt = "You can also export 2D barcodes in other formats (PNG, SVG, HTML). Check the examples inside the barcode directory.\n";
//        $pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);

        $top = 0;

        $rootDir = $this->get('kernel')->getRootDir();
        $image = $rootDir . '/../web/images/logo-louvre.jpg';

        foreach ($orderLines as $line){

            $serialNumber = $line->getTicket()->getSerialNumber();
            $top +=60;
            $pdf->AddPage();

            $pdf->SetXY(10, 10);
            $pdf->Image($image);
            $pdf->SetXY(100, 40);
            $pdf->writeHTML("<h1>MUSEE DU LOUVRE</h1>");
//            $pdf->Cell(0, 0, 'MUSEE DU LOUVRE', 1, 1, 'C');


            $pdf->write2DBarcode($serialNumber, 'QRCODE,H', 140, $top, 50, 50, $style, 'N');
        }

//        $pdf->Output('example_050.pdf', 'I');
        return new Response(
            $pdf->Output('example_050.pdf', 'I')
        );
    }
}
