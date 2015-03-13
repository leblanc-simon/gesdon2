<?php

namespace Gesdon2Bundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Gesdon2Bundle\Entity\Adresse;

class AdresseVersId implements DataTransformerInterface
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
     * Transforme un objet Adresse en entier Id.
     * Renvoie -1 si l'adresse n'est pas trouvée.
     *
     * @param  Adresse|null $adresse
     * @return int
     */
    public function transform($adresse)
    {
        if (null === $adresse) {
            return -1;
        }

        return $adresse->getId();
    }

    /**
     * Transforme un entier Id en objet Adresse.
     *
     * @param  int $id
     *
     * @return Adresse|null
     *
     * @throws TransformationFailedException si l'objet Adresse n'est pas trouvé.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $adresse = $this->om
            ->getRepository('Gesdon2Bundle:Adresse')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $adresse) {
            throw new TransformationFailedException(sprintf(
                'L\'adresse avec l\'ID "%s" n\'existe pas!',
                $id
            ));
        }

        return $adresse;
    }
}