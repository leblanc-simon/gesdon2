<?php

namespace Gesdon2Bundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Gesdon2Bundle\Entity\Donateur;
use Gesdon2Bundle\Form\DonateurType;
use Gesdon2Bundle\Form\DonateurSearchType;

class DonateurController extends Controller
{

    /**
     * Afficher la page de recherche des donateurs.
     *
     * @Route("/Donateur", name="donateur_search")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $form = $this->createSearchForm('Donateur');
        // générer la page à retourner à partir du template twig "list"
        return $this->render('Gesdon2Bundle:Donateur:search.html.twig',
            array(
                'donateur_form' => $form->createView(), // créer la vue à partir du formulaire
            )
        );
    }

    /**
     * Créer un formulaire pour rechercher des donateurs.
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createSearchForm()
    {
        // créer l'objet type
        $typeObject = new DonateurSearchType();

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            //pas de données initiales
            null,
            array(
                'action' => $this->generateUrl('donateur_table'),
                'method' => 'POST'
            )
        );

        $form->add('donateur_search', 'submit', array(
            'label' => 'Rechercher',
            'disabled' => 'true',
        ));

        return $form;
    }

    /**
     * Créer le tableau HTML des donateurs
     *
     * @Route("/Donateur/table", name="donateur_table")
     * @Method({"POST"})
     * @Template( "Gesdon2Bundle:Donateur:table.html.twig" )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableAction(Request $request)
    {
        // invoquer le manager Doctrine
        $em = $this->getDoctrine()->getManager();
        // créer un constructeur de requêtes DQL
        $qb = $em->createQueryBuilder();
        // sélectionner dans la table Donateur
        $qb ->select('d')
            ->from('Gesdon2Bundle:Donateur', 'd');

        // retrouver les données du formulaire
        $form = $this->createForm(new DonateurSearchType());
        $form->submit($request);


        // si le formulaire est validé...
        if ($form->isValid()) {
            // retrouver les données
            $filter = $form->getData();
            // pour debug
            // dump($filter);
            if (!empty($filter)) {
                // créer une expression AND
                $andX = $qb->expr()->andX();
                // pour chaque champ du filtre et sa valeur
                foreach ($filter as $column => $value) {
                    // si le champ est une ArrayCollection
                    // (dans le cas d'un champ 'entity')
                    if ($value instanceof ArrayCollection) {
                        // si la collection n'est pas vide...
                        if ($value->count() != 0) {
                                // ajouter une clause IN
                                // où la valeur de la colonne est dans le tableau d'IDs
                                $andX->add("d.{$column} IN(:ids)");
                                // affecter la liste d'IDs du tableau au paramètre
                                $qb->setParameter('ids', $value->getValues());
                            }
                    }
                    // sinon
                    else {
                        // si le champ n'est pas vide
                        if ($value != '') {
                            // ajouter une clause LIKE, traiter comme du texte
                            $andX->add($qb->expr()->like(
                                "d.{$column}",
                                "'{$value}'"));
                        }
                    }
                }
                // si des champs du filtre ont été renseignés, définir la clause where
                $andParts = $andX->getParts();
                if (!empty($andParts)) {
                    $qb->where($andX);
                }
            }
        }
        // exécuter la requête et retrouver le résultat
        $instances = $qb->getQuery()->getResult();

        // générer la page à retourner à partir du template twig "table"
        // en passant la liste des donateurs
        return $this->render('Gesdon2Bundle:Donateur:table.html.twig',
            array(
                'instances' => $instances
            )
        );
    }

    /**
     * Afficher le formulaire de création des donateurs.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/Donateur/new", name="donateur_new")
     * @Template("Gesdon2Bundle:Donateur:new.html.twig")
     */
    public function newAction(Request $request)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getManager();

        // créer l'objet
        $entityObject = new Donateur();

    	// créer l'objet formulaire à partir du type
        $typeObject = new DonateurType();
        $form = $this->createForm($typeObject, $entityObject);

        // ajouter le bouton d'envoi
        $form->add('submit', 'submit', array('label' => 'Créer'));

        // traiter la soumission du formulaire
        $form->handleRequest($request);

        // si le formulaire est validé
        if ($form->isValid()) {
            // récupérer les données du formulaire
            $entityObject = $form->getData();

            // persister l'objet
            //TODO traiter les erreurs SQL
            $em->persist($entityObject);
            $em->flush();

            return $this->redirect($this->generateUrl('donateur_edit', array(
                'id' => $entityObject->getId()
            )));
        }

        // retourner le formulaire d'ajout
        // le nom de la classe et le formulaire correspondant au type d'objet sont passés en paramètre
        return $this->render('Gesdon2Bundle:Donateur:new.html.twig',
            array
            (
                'form'  => $form->createView()
            )
        );
    }

    /**
     * Afficher un formulaire de modification d'un donateur.
     *
     * @param int $id           L'identifiant du donateur
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("Donateur/edit/{id}", name="donateur_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // retrouver l'instance de l'entité avec l'id
        $instance = $em->getRepository('Gesdon2Bundle:Donateur')->find($id);

        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Donateur instance.');
        }

        $editForm = $this->createEditForm($instance, $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Créer un formulaire pour modifier un donateur.
     *
     * @param object $instance  L'objet Donateur
     * @param int $id           L'identifiant du donateur
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm($instance, $id)
    {
        // créer l'objet type
        $typeObject = new DonateurType();

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            $instance,
            array(
                'action' => $this->generateUrl(
                    'donateur_update',
                    array(
                        'id' => $id
                    )
                ),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Modifier un donateur
     *
     * @param Request $request
     * @param int $id           L'identifiant ddu donateur
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/Donateur/update/{id}", name="donateur_update")
     * @Method("PUT")
     * @Template("Gesdon2Bundle:Donateur:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // retrouver l'instance par son ID
        $instance = $em->getRepository('Gesdon2Bundle:Donateur')->find($id);
        // si l'instance est introuvable, revoyer un message d'erreur
        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Donateur instance.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($instance, $id);
        $editForm->handleRequest($request);

        // si le formulaire est validé...
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('donateur_edit', array(
                'id' => $id)));
        }

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Supprimer un donateur.
     *
     * @param Request $request
     * @param int $id           l'identifiant du donateur
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/Donateur/delete/{id}", name="donateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // retrouver l'instance grâce à son ID
            $instance = $em->getRepository('Gesdon2Bundle:Donateur')->find($id);

            // si l'instance est introuvable, renvoyer un message d'erreur
            if (!$instance) {
                throw $this->createNotFoundException('Unable to find Donateur instance.');
            }

            // supprimer l'instance
            $em->remove($instance);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('donateur_search'));
    }

    /**
     * Créer un formulaire pour supprimer un donateur.
     *
     * @param int $id           L'identifiant du donateur
     *
     * @return \Symfony\Component\Form\Form Le formulaire
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('donateur_delete', array(
                'id' => $id)
            ))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }

}
