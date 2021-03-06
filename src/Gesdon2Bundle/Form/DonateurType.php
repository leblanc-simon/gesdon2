<?php

namespace Gesdon2Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/* Pour gestion des événements et champs conditionnels */
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DonateurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // référencer l'entité "Type"
            ->add('type')
            ->add('nom')
            ->add('prenom')
            ->add('courriel','email')
        ;
        /* TODO champ prénom conditionnel*/
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesdon2Bundle\Entity\Donateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gesdon2_gesdon2bundle_donateur';
    }
}
