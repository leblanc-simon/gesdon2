<?php

namespace Gesdon2Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchDonateurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type','entity',array(
                'class' => 'Gesdon2Bundle:Type',
                'property' => 'nom',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('nom',        'text', array('required' => false))
            ->add('prenom',     'text', array('required' => false))
            ->add('courriel',   'text', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Gesdon2Bundle\Entity\Donateur'
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
