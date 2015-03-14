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
     * @param  array|null $donateurs
     * @return array
     */
    public function transform($donateurs)
    {
        if (null === $donateurs) {
            return array();
        }

        $ids = array();
        for ($i = 0; $i < count($donateurs); $i++)
        {
            $ids[$i] = $donateurs[$i]->getId();
        }
        return $ids;
    }

    /**
     * Transforme un entier Id en objet Donateur.
     *
     * @param  string $strIds
     *
     * @return array
     *
     * @throws TransformationFailedException si l'objet Donateur n'est pas trouvé.
     */
    public function reverseTransform($strIds)
    {
        if (null === $strIds) {
            return array();
        }

        $ids = explode(',',$strIds);

        $donateurs = $this->om
            ->getRepository('Gesdon2Bundle:Donateur')
            ->findBy(array('id' => $ids))
        ;


        if (null === $donateurs) {
            throw new TransformationFailedException(sprintf(
                'Aucun donateur n\'a été trouvé.',
                $ids
            ));
        }

        return $donateurs;
    }
}