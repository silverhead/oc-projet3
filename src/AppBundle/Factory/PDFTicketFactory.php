<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 27/10/16
 * Time: 14:03
 */

namespace AppBundle\Factory;


class PDFTicketFactory
{

	private $pdf;

	private $logoPathFile;

	private $title;

	public function __construct($pdfFactory, $logoPathFile, $title)
	{
		$this->pdf = $pdfFactory->create();// $this->get("white_october.tcpdf")-

		$this->logoPathFile = $logoPathFile;

		$this->title = $title;
	}

	public function createTicket
	(
		$bookingDate,
		$amountLabel,
		$customerName,
		$customerBirthday,
		$serialNumber
	)
	{
		$this->pdf->AddPage();
		$this->writeLogo($this->logoPathFile);
		$this->writeTitleTicket($this->title);
		$this->writeBookingDate($bookingDate);
		$this->writeAmountLine($amountLabel);
		$this->writeCustomerName($customerName);
		$this->writeCustomerBirthday($customerBirthday);
		$this->writeSerialNumberLine($serialNumber);
		$this->writeQRCODE($serialNumber);
	}

	/**
	 * Write on the pdf, the logo
	 *
	 * @param string $amountLine
	 */
	private function writeLogo($logo)
	{
		$this->pdf->SetXY(10, 10);
		$this->pdf->Image($logo);
	}


	/**
	 * Write on the pdf, the title of ticket
	 *
	 * @param string $amountLine
	 */
	private function writeTitleTicket($title)
	{
		$this->pdf->SetXY(80, 10);
		$this->pdf->SetFont('helvetica', '', 25);
		$this->pdf->MultiCell(100, 10, $title, 0, 'L');
	}

	/**
	 *write the booking date on the pdf
	 *
	 * @param \DateTime $bookingDate
	 * @param string $format Datetime format
	 */
	private function writeBookingDate($bookingDate, $format = 'd/m/Y'){
		$this->pdf->SetXY(80, 23);
		$this->pdf->SetFont('helvetica', '', 20);
		$this->pdf->MultiCell(100, 10, 'Date : ' . $bookingDate->format($format), 0, 'L');
	}

	/**
	 * Write on the pdf, the amount information line
	 *
	 * @param string $amountLine
	 */
	private function writeAmountLine($amountLine)
	{
		$this->pdf->SetXY(10, 35);
		$this->pdf->SetFont('helvetica', '', 15);
		$this->pdf->MultiCell(100, 10, $amountLine,0, 'L');
	}

	/**
	 * Write on the pdf, the name of customer
	 *
	 * @param string $customerName
	 */
	private function writeCustomerName($customerName)
	{
		//set the customer name
		$this->pdf->SetXY(10, 50);
		$this->pdf->SetFont('helvetica', '', 15);
		$this->pdf->MultiCell(100, 10,
			'Au nom de  : ' . $customerName,
			0,
			'L');
	}

	/**
	 *write the birthday of customer on the pdf
	 *
	 * @param \DateTime $customerBirthday
	 * @param string $format Datetime format
	 */
	private function writeCustomerBirthday(\Datetime $customerBirthday, $format = 'd/m/Y')
	{
		$this->pdf->SetXY(10, 65);
		$this->pdf->SetFont('helvetica', '', 15);
		$this->pdf->MultiCell(100, 10,
			'Née le  : ' .
			$customerBirthday->format($format),
			0,
			'L');
	}

	/**
	 * write on pdf the serial number
	 *
	 * @param $serialNumber
	 */
	private function writeSerialNumberLine($serialNumber)
	{
		//set the serial number
		$this->pdf->SetXY(10, 80);
		$this->pdf->SetFont('helvetica', '', 15);
		$this->pdf->MultiCell(200, 10,
			'n° : ' .
			$serialNumber,
			0,
			'L');
	}

	private function writeQRCODE($schema)
	{
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

		$this->pdf->write2DBarcode($schema, 'QRCODE,H', 140, 35, 50, 50, $style, 'N');
	}

	public function output($filenName)
	{
		return $this->pdf->Output($filenName, 'I');
	}
}