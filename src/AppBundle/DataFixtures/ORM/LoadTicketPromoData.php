<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketPromo;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketPromoData implements FixtureInterface, OrderedFixtureInterface
{

	public function load(ObjectManager $manager)
	{
		$data = [
			(object) [
				'label'     => 'Tarif spécial Grande famille',
				'amount'   => 35
			]
		];

		foreach ($data as $ticketAmountFixture){
			$ticketPromo = new TicketPromo();

			$ticketPromo
				->setLabel($ticketAmountFixture->label)
				->setAmount($ticketAmountFixture->amount)
			;

			$manager->persist($ticketPromo);
		}

		$manager->flush();
	}

    public function getOrder()
    {
        return 3;
    }
}