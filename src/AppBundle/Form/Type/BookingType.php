<?php

namespace AppBundle\Form\Type;

use AppBundle\Validator\Constraints\ForbiddenDates;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $bookingManager = $options['booking_manager'];

        $builder
            ->add("bookingDate", DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new ForbiddenDates(),
                ],
                'attr' => [
                    'data-forbidden-dates'      =>  implode(", ", $bookingManager->getForbiddenDates()),
                    'data-forbidden-weekdays'   =>  implode(", ",$bookingManager->getForbiddenWeekDays()),
                ]
            ])
            ->add("ticketType", EntityType::class, [
                'class' => 'AppBundle\Entity\TicketType',
                'choice_label' => "Label",
	            'placeholder' => 'Choisissez le type de ticket'
            ])
            ->add("ticketQuantity", ChoiceType::class, [
                'choices' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('booking_manager');
    }

    public function getName()
    {
        return 'app_bundle_booking_type';
    }
}
