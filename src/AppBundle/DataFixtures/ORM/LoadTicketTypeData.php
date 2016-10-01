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
                'limitHour' => 0
			],
			(object) [
				'label'     => 'Tarif demi-journée',
				'percent'   => 50,
                'limitHour' => 14
			]
		];

		foreach ($data as $ticketTypeData){
			$ticketType = new TicketType();

			$ticketType
				->setLabel($ticketTypeData->label)
				->setPercent($ticketTypeData->percent)
				->setLimitHour($ticketTypeData->limitHour)
			;

			$manager->persist($ticketType);
		}

		$manager->flush();
	}
}
