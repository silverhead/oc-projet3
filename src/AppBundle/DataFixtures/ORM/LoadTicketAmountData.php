<?php

namespace AppBundle\DataFixture\ORM;

use AppBundle\Entity\TicketAmount;
use AppBundle\Entity\TicketType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketAmountData implements FixtureInterface, OrderedFixtureInterface
{

	public function load(ObjectManager $manager)
	{
		$data = [
			(object) [
				'label'     => 'Adulte',
				'amount'   => 16,
                'ageConditionStart' => 12,
                'ageConditionEnd' => 60,
				'default' => 1,
				'specialAmount' => 0,
			],
			(object) [
				'label'     => 'Enfant',
				'amount'   => 8,
				'ageConditionStart' => 4,
				'ageConditionEnd' => 12,
				'default' => 0,
				'specialAmount' => 0,
			],
			(object) [
				'label'     => 'Enfant de moins de 4 ans',
				'amount'   => 0,
				'ageConditionStart' => 0,
				'ageConditionEnd' => 4,
				'default' => 0,
				'specialAmount' => 0,
			],
			(object) [
				'label'     => 'Senior',
				'amount'   => 12,
				'ageConditionStart' => 60,
				'ageConditionEnd' => 99,
				'default' => 0,
				'specialAmount' => 0,
			],
			(object) [
				'label'     => 'Spécial',
				'amount'   => 10,
				'ageConditionStart' => 18,
				'ageConditionEnd' => 99,
				'default' => 0,
				'specialAmount' => 1,
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
				->setSpecialAmount($ticketAmountFixture->specialAmount)
			;

			$manager->persist($ticketAmount);
		}

		$manager->flush();
	}

	public function getOrder()
    {
        return 2;
    }
}