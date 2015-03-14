<?php

namespace Gesdon2Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DonSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TODO trouver un moyen de filtrer par période
            ->add('date', 'date', array('required' => false))
            ->add('montant', 'number', array('required' => false))
            ->add('moyen','entity',
                array
                (
                    'class' => 'Gesdon2Bundle:Moyen',
                    'property' => 'nom',
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('moyenDesc',          'text',             array('required' => false))
            ->add('recurrence',         'checkbox',         array('required' => false))
            ->add('transacNum',         'text',             array('required' => false))
            // TODO trouver un moyen de filtrer par période
            ->add('dateFinRecurrence',  'date',             array('required' => false))
            ->add('courriel',           'text',             array('required' => false))
            ->add('adresse',            'adresse_selector', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Gesdon2Bundle\Entity\Don'
            'attr' => array('id' => 'don_form'),
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
