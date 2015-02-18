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
            // TODO référencer l'entité "Type".
/*            ->add('type','choice',
                array
                (
                    'choices' => array
                    (
                        '1'=>'Entreprise',
                        '2'=>'Association',
                        '3'=>'Particulier'
                    ),
                    'required' => false,
                    'multiple' => true,
                    // passer 'expanded à 'true' pour avoir de checkbox
                    // cependant : impossible de lui faire prendre les valeurs par défaut
                    //'expanded' => true,
                    'empty_data' => array(1,2,3),
                )
            )*/
            /* utilise l'entité Type. Renvoit une ArrayCollection
            TODO trouver comment récupérer l'id et pas le nom
            apparemment, la conversion de l'ArrayCollection vers un array utilise la métohde
            toString de l'entité. Or elle est implémentée de façon à renvoyer le nom du Type,
            pas l'id.*/
            ->add('type','entity',array(
                'class' => 'Gesdon2Bundle:Type',
                'property' => 'nom',
                'required' => false,
                'multiple' => true,
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
