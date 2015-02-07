<?php

namespace Gesdon2Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('montant')
            ->add('moyen')
            ->add('moyenDesc')
            ->add('recurrence', 'checkbox', array('required' => false))
            ->add('transacNum')
            ->add('recurrDateFin')
            ->add('courriel')
            ->add('adresse')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gesdon2Bundle\Entity\Don'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gesdon2_gesdon2bundle_don';
    }
}
