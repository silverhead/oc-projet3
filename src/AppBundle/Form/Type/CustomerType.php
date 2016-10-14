<?php

namespace AppBundle\Form\Type;

use AppBundle\Validator\Constraints\Birthday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new DateTime(),
                    new Birthday()
                ]
            ])
            ->add('country', CountryType::class, [
                'preferred_choices' => array('FR')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    public function getName()
    {
        return 'app_bundle_customer_type';
    }
}
