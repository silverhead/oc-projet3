<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketPromoCondition;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketPromoConditionData implements FixtureInterface, OrderedFixtureInterface
{

	public function load(ObjectManager $manager)
	{
        $ticketPromos = $manager->getRepository("AppBundle:TicketPromo")->findAll();
        $ticketPromo = $ticketPromos[0];

		$ticketAmounts = $manager->getRepository("AppBundle:TicketAmount")->findAll();

		foreach($ticketPromos as $ticketPromo){
			foreach ($ticketAmounts as $ticketAmount){
				$ticketPromoCondition = new TicketPromoCondition();
				$ticketPromoCondition
					->setCount( 0 )
					->setTicketPromo( $ticketPromo )
					->setTicketAmount( $ticketAmount )
				;

				$manager->persist($ticketPromoCondition);
			}
		}


		$manager->flush();

		$birthdayAdulte = \DateTime::createFromFormat("Y-m-d", "1981-05-07");
		$ticketAmountAdulte = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayAdulte);


		$birthdayEnfant = \DateTime::createFromFormat("Y-m-d", "2009-30-10");
		$ticketAmountEnfant = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayEnfant);


		$ticketPromoCondition1 = $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount' => $ticketAmountAdulte,
			'ticketPromo' => $ticketPromos[0]
		));
		$ticketPromoCondition1->setCount(2);

		$ticketPromoCondition1 = $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount'  => $ticketAmountEnfant,
			'ticketPromo'   => $ticketPromos[0]
		));
		$ticketPromoCondition1->setCount(2);

		$ticketPromoCondition2= $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount' => $ticketAmountAdulte,
			'ticketPromo' => $ticketPromos[1]
		));
		$ticketPromoCondition2->setCount(2);

		$ticketPromoCondition2 = $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount' => $ticketAmountEnfant,
			'ticketPromo' => $ticketPromos[1]
		));
		$ticketPromoCondition2->setCount(4);

		$manager->flush();
	}

    public function getOrder()
    {
        return 4;
    }
}