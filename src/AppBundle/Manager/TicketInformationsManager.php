<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 13/10/16
 * Time: 10:55
 */

namespace AppBundle\Manager;


use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Service\BookingSaveAndGetErrorsInterface;
use AppBundle\Service\FindBookingsInterface;

class TicketInformationsManager implements TicketInformationsManagerInterface
{
    /**
     * @var BookingSaveAndGetErrorsInterface
     */
    private $saveService;

    /**
     * @var FindBookingsInterface
     */
    private $findBooking;

    public function __construct(
        BookingSaveAndGetErrorsInterface $saveService,
        FindBookingsInterface $findBooking
    )
    {
        $this->saveService = $saveService;
        $this->findBooking = $findBooking;
    }

    /**
     * Return the Booking model
     * @return mixed
     */
    public function getCurrentBooking()
    {
        $booking =  $this->findBooking->getCurrentBooking();
        $this->setNewTicketsForBooking($booking);

        return $booking;
    }

    public function setNewTicketsForBooking(BookingEntityInterface $booking)
    {
        $defaultTicketAmount = $this->findBooking->getTicketAmountByTicketType($booking->getTicketType());

        for($i = 0; $i < $booking->getTicketQuantity(); $i++){
            $ticket = $this->findBooking->getTicket();
            $ticket->setAmount($defaultTicketAmount);
            $booking->addTicket( $ticket );
        }

        return $this;
    }

    /**
     * Return the cost of ticket in function of the birthday
     * @param \DateTime $birthday
     * @return mixed
     */
    public function getTicketPriceByBirthday(\DateTime $birthday)
    {
        // TODO: Implement getTicketPriceByBirthday() method.
    }

    /**
     * Return the special rate of ticket if a special rate is ckecked
     *
     * @param $id
     * @return mixed
     */
    public function getSpecialRateById($id)
    {
        // TODO: Implement getSpecialRateById() method.
    }

    /**
     * Save all data for the tickets
     *
     * @return mixed
     */
    public function saveTickets()
    {
        // TODO: Implement saveTickets() method.
    }
}