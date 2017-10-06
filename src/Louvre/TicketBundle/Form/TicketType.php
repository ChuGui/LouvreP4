<?php

namespace Louvre\TicketBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;


class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom'
            ))
            ->add('birthday', BirthdayType::class, array(
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy',
                'label' => 'Date de naissance',
                'constraints' => array(
                    new Assert\Date(),
                )
            ))
            ->add('country', CountryType::class, array(
                'label' => 'Pays'
            ))
            ->add('discount', CheckboxType::class, array(
                'label' => "Cochez cette case si vous êtes étudiant, employé du musée, d’un service du Ministère de la Culture, militaire (A justifier à l'entrée du Louvre)",
                'required' => false
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketbundle_ticket';
    }


}
