<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class CheckAuthorOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('email', EmailType::class, [
				'constraints' => [new Email([
					'strict' => true
				])]
			])
			->add('callbackpage', ChoiceType::class, [
				'choices' => [
					'Finaliser la commande' => 1,
					'Annuler la commande et recommencer une nouvelle réservation' => 2
				]
			])
		;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_check_author_order_type';
    }
}
