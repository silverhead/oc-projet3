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

		foreach ($ticketAmounts as $ticketAmount){
			$ticketPromoCondition = new TicketPromoCondition();
			$ticketPromoCondition
				->setCount( 0 )
				->setTicketPromo( $ticketPromo )
				->setTicketAmount( $ticketAmount )
			;

			$manager->persist($ticketPromoCondition);
		}

		$manager->flush();

		$birthdayAdulte = \DateTime::createFromFormat("Y-m-d", "1981-05-07");
		$ticketAmountAdulte = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayAdulte);

		$ticketPromoCondition = $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount' => $ticketAmountAdulte,
			'ticketPromo' => $ticketPromo
		));

		var_dump($ticketPromoCondition);

		$ticketPromoCondition->setCount(2);


		$birthdayEnfant = \DateTime::createFromFormat("Y-m-d", "2009-30-10");
		$ticketAmountEnfant = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayEnfant);

		$ticketPromoCondition = $manager->getRepository("AppBundle:TicketPromoCondition")->findOneBy(array(
			'ticketAmount' => $ticketAmountEnfant,
			'ticketPromo' => $ticketPromo
		));
		$ticketPromoCondition->setCount(2);

		$manager->flush();
	}

    public function getOrder()
    {
        return 4;
    }
}