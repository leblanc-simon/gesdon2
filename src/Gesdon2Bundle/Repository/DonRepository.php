<?php

namespace Gesdon2Bundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Form;

class DonRepository extends EntityRepository
{
    public function findBySearch(Form $form)
    {
        // Availables fields from form
        $fields = $form->getIterator();

        // Datas submit by user
        $datas = $form->getData();

        // Init the QueryBuilder
        $qb = $this->createQueryBuilder('d');
        $parameters = [];

        // Foreach availables fields, check if user has submit a value
        foreach ($fields as $field) {
            $name = $field->getName();
            if (empty($datas[$name]) !== true) {
                // If data is an ArrayCollection, transform it into an array (and get a IN clause)
                if ($datas[$name] instanceof ArrayCollection) {
                    $qb->andWhere('d.'.$name.' IN (:'.$name.')');
                    $parameters[$name] = $datas[$name]->toArray();
                } else {
                    $qb->andWhere('d.'.$name.' = :'.$name);
                    $parameters[$name] = $datas[$name];
                }
            }
        }

        $qb->setParameters($parameters);

        return $qb->getQuery()->getResult();
    }
}