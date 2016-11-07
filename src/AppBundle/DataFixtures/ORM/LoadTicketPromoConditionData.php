<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketPromoCondition;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadTicketPromoConditionData implements FixtureInterface, OrderedFixtureInterface
{

	public function load(ObjectManager $manager)
	{
        $ticketPromos = $manager->getRepository("TicketPromo")->findAll();
        $ticketPromo = $ticketPromos[0];

        $birthdayAdulte = \DateTime::createFromFormat("Y-m-d", "1981-05-07");
        $ticketAmountAdulte = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayAdulte);

        $birthdayEnfant = \DateTime::createFromFormat("Y-m-d", "2009-30-10");
        $ticketAmountEnfant = $manager->getRepository("AppBundle:TicketAmount")->findOneByAge($birthdayEnfant);

		$data = [
			(object) [
                'count'       => 2,
                'ticketPromo' => $ticketPromo->getId(),
                'ticketType' => $ticketAmountAdulte->getId(),
			],
			(object) [
                'count'       => 2,
                'ticketPromo' => $ticketPromo->getId(),
                'ticketType' => $ticketAmountEnfant->getId(),
            ]
		];

		foreach ($data as $ticketPromoConditionFixture){
			$ticketPromoCondition = new TicketPromoCondition();

            $ticketPromoCondition
				->setLabel($ticketPromoConditionFixture->label)
				->setAmount($ticketPromoConditionFixture->amount)
			;

			$manager->persist($ticketPromoCondition);
		}

		$manager->flush();
	}

    public function getOrder()
    {
        return 4;
    }
}