<?php

namespace Gesdon2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * Menu des entités modifiables.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // tableau de entités éditables
        $entities = array("Donateur", "Adresse", "Don");

        return $this->render('Gesdon2Bundle:Default:index.html.twig',
            array(
                'entities' => $entities
            )
        );
    }

    /**
     * Afficher la liste des instances de l'entité passée en paramètre.
     *
     * @param string $entity    Le nom de l'entité
     * @param Request $request  Tableau des champs utilisés pour le filtre
     * @return \Symfony\Component\HttpFoundation\Response
     */
}
