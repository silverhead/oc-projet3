<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketTypeData implements FixtureInterface{

	public function load(ObjectManager $manager)
	{
		$data = [
			(object) [
				'label'     => 'Tarif journée',
				'percent'   => 100,
			],
			(object) [
				'label'     => 'Tarif demi-journée',
				'percent'   => 50,
			]
		];

		foreach ($data as $ticketTypeData){
			$ticketType = new TicketType();

			$ticketType
				->setLabel($ticketTypeData->label)
				->setPercent($ticketTypeData->percent)
			;

			$manager->persist($ticketType);
		}

		$manager->flush();
	}
}
