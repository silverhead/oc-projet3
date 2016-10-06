<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketAmount;
use AppBundle\Entity\TicketType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketAmountData implements FixtureInterface{

	public function load(ObjectManager $manager)
	{
		$data = [
			(object) [
				'label'     => 'Tarif normal',
				'amount'   => 16,
                'ageConditionStart' => 12,
                'ageConditionEnd' => 60,
				'default' => 1
			],
			(object) [
				'label'     => 'Tarif enfant',
				'amount'   => 8,
				'ageConditionStart' => 4,
				'ageConditionEnd' => 12,
				'default' => 0
			],
			(object) [
				'label'     => 'Tarif enfant de moins de 4 ans',
				'amount'   => 0,
				'ageConditionStart' => 0,
				'ageConditionEnd' => 4,
				'default' => 0
			],
			(object) [
				'label'     => 'Tarif senior',
				'amount'   => 12,
				'ageConditionStart' => 60,
				'ageConditionEnd' => 99,
				'default' => 0
			],

		];

		foreach ($data as $ticketAmountFixture){
			$ticketAmount = new TicketAmount();

			$ticketAmount
				->setLabel($ticketAmountFixture->label)
				->setAmount($ticketAmountFixture->amount)
				->setAgeConditionStart($ticketAmountFixture->ageConditionStart)
				->setAgeConditionEnd($ticketAmountFixture->ageConditionEnd)
				->setDefault($ticketAmountFixture->default)
			;

			$manager->persist($ticketAmount);
		}

		$manager->flush();
	}
}