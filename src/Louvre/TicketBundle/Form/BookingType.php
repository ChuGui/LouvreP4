<?php

namespace Louvre\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'label' => 'Saisissez une date',
                'required' => true,
                'attr' => [
                    'class' => 'js-datepicker',
                    'html5' => false,
                ]
            ))
            ->add('halfday', CheckboxType::class, array(
                'required' => false,
                'label' => 'Cochez la case si vous voulez des billets pour la demi-journée  (après 14h00)'
            ))
            ->add('tickets', CollectionType::class, array(
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('next', SubmitType::class, array(
                'label' => 'Passer au paiement'
            ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketBundle\Entity\Booking',

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
