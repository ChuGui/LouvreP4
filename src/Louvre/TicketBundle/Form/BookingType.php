<?php

namespace Louvre\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitingDay', DateType::class, array(
                'label' => 'Choisissez votre jour de visite',
                'widget' => 'single_text',
                'attr' => [
                    'format' => 'dd-MM-yyyy'

                ]
            ))


            ->add('fullday', CheckboxType::class, array(
                'required' => false,
                'label' => 'Cochez la case si vous voulez des billets pour la journée entière (avant 14h00)'
            ))
            ->add('tickets', CollectionType::class,array(
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('next', SubmitType::class);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketBundle\Entity\Booking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketbundle_booking';
    }


}
