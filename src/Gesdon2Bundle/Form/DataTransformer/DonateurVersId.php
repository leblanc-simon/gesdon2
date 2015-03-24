<?php

namespace Gesdon2Bundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Gesdon2Bundle\Entity\Donateur;

class DonateurVersId implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforme un objet Donateur en entier Id.
     *
     * @param  Donateur|null $donateur
     * @return int|null
     */
    public function transform($donateur)
    {
        if (null === $donateur) {
            return null;
        }

        return $donateur->getId();
    }

    /**
     * Transforme un entier Id en objet Donateur.
     *
     * @param  int $id
     *
     * @return array
     *
     * @throws TransformationFailedException si l'objet Donateur n'est pas trouvé.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $adresse = $this->om
            ->getRepository('Gesdon2Bundle:Donateur')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $adresse) {
            throw new TransformationFailedException(sprintf(
                'Le donateur avec l\'ID "%s" n\'existe pas!',
                $id
            ));
        }

        return $adresse;
    }
}