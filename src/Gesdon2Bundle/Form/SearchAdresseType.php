<?php

namespace Gesdon2Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchAdresseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TODO formulaire imbriqué ou popup

            ->add(
            'donateur','entity',array(
                'class' => 'Gesdon2Bundle:Donateur',
                'property' => 'nom',
                'required' => false,
                'multiple' => true,
                )
            )

            ->add('adresse1',   'text', array('required' => false))
            ->add('adresse2',   'text', array('required' => false))
            ->add('codePostal', 'text', array('required' => false))
            ->add('ville',      'text', array('required' => false))
            ->add('pays',       'text', array('required' => false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Gesdon2Bundle\Entity\Adresse'
            'attr' => array('id' => 'adresse_form'),
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
