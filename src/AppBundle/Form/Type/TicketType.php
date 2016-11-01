<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\TicketAmount;
use AppBundle\Form\DataTransformer\EntityToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CustomerType::class, [
                'data_class' => 'AppBundle\Entity\Customer'
            ])
            ->add('amount', HiddenType::class)
            ->add('TicketAmount', HiddenType::class,[
                'property_path' => 'ticketAmount'
            ])
            ->add('specialAmount', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
        ;

        $builder->get("TicketAmount")
            ->addModelTransformer(new EntityToIdTransformer($this->manager,TicketAmount::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    public function getName()
    {
        return 'app_bundle_ticket';
    }
}
