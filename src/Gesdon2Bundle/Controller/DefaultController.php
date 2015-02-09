<?php

namespace Gesdon2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @param String $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($entity)
    {
        $em = $this->getDoctrine()->getManager();

        // retrouver la liste des instances de l'entité
        $bundle = "Gesdon2Bundle:";
        $instances = $em->getRepository($bundle.$entity)->findAll();

        // retrouver les attributs de l'entité
        $fields = $em ->getClassMetadata($bundle.$entity)->getFieldNames();

        // générer la page à retourner à partir du template twig "list"
        // en passant la liste des instances de l'entité
        return $this->render('Gesdon2Bundle:Default:list.html.twig',
            array(
                'instances'=> $instances,
                'entity'  => $entity,
                'fields'  => $fields
            )
        );
    }

    /**
     * Générer le formulaire de création d'instance de l'entité passée en paramètre.
     *
     * @param Request $request
     * @param String $entity
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", name="new")
     * @Method("GET")
     * @Template("Gesdon2Bundle:Default:ajouter.html.twig")
     */
    public function newAction(Request $request, $entity)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getManager();

        // concaténer le namespace et le nom de la classe
        $namespaceClasse = 'Gesdon2Bundle\\Entity\\' . $entity;
        // créer l'objet
        $entityObject = new $namespaceClasse;

    	// créer l'objet formulaire à partir du type
        $type = 'Gesdon2Bundle\\Form\\' . $entity . "Type";
        $typeObject = new $type;
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

            return $this->redirect($this->generateUrl('show', array(
                'entity' => $entity,
                'id' => $entityObject->getId()
            )));
        }

        // retourner le formulaire d'ajout
        // le nom de la classe et le formulaire correspondant au type d'objet sont passés en paramètre
        return $this->render('Gesdon2Bundle:Default:new.html.twig',
            array
            (
                'entity' => $entity,
                'form'  => $form->createView()
            )
        );
    }

    /**
     * Trouver et afficher une instance.
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     * @Template()
     *
     * @param $entity
     * @param $id
     * @return array
     */
    public function showAction($entity, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $instance = $em->getRepository('Gesdon2Bundle:'.$entity)->find($id);

        if (!$instance) {
            throw $this->createNotFoundException('Unable to find ' . $entity . ' instance.');
        }

        $deleteForm = $this->createDeleteForm($entity, $id);

        return array(
            'entity'      => $entity,
            'instance'    => $instance,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Afficher un formulaire pour modifier un instance.
     *
     * @param String $entity
     * @param Integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/edit", name="edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($entity,$id)
    {
        $em = $this->getDoctrine()->getManager();

        // retrouver l'instance de l'entité avec l'id
        $instance = $em->getRepository('Gesdon2Bundle:'.$entity)->find($id);

        if (!$instance) {
            throw $this->createNotFoundException('Unable to find '.$entity.' instance.');
        }

        $editForm = $this->createEditForm($entity, $instance);
        $deleteForm = $this->createDeleteForm($entity, $id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Créer un formulaire pour modifier une instance.
     *
     * @param String $entity
     * @param $instance
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm($entity, $instance)
    {
        // créer l'objet type à partir du nom
        $type = 'Gesdon2Bundle\\Form\\' . $entity . "Type";
        $typeObject = new $type;

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            $instance,
            array(
                'action' => $this->generateUrl(
                    'update',
                    array(
                        'entity' => $entity,
                        'id' => $instance->getId()
                    )
                ),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Modifier une instance.
     *
     * @param Request $request
     * @param String $entity
     * @param Integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{entity}/{id}", name="update")
     * @Method("PUT")
     * @Template("Gesdon2Bundle:Default:edit.html.twig")
     */
    public function updateAction(Request $request, $entity, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // retrouver l'instance par son ID
        $instance = $em->getRepository('Gesdon2Bundle:'.$entity)->find($id);
        // si l'instance est introuvable, revoyer un message d'erreur
        if (!$instance) {
            throw $this->createNotFoundException('Unable to find '.$entity.' instance.');
        }

        $deleteForm = $this->createDeleteForm($entity, $instance);
        $editForm = $this->createEditForm($entity, $instance);
        $editForm->handleRequest($request);

        // si le formulaire est validé...
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('edit', array(
                'entity'=>$entity,
                'id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Supprimer une instance.
     *
     * @param Request $request
     * @param String $entity
     * @param Integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{entity}/{id}", name="delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $entity, $id)
    {
        $form = $this->createDeleteForm($entity, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // retrouver l'instance grâce à son ID
            $instance = $em->getRepository('Gesdon2Bundle:'.$entity)->find($id);

            // si l'instance est introuvable, renvoyer un message d'erreur
            if (!$instance) {
                throw $this->createNotFoundException('Unable to find '.$entity.' instance.');
            }

            // supprimer l'instance
            $em->remove($instance);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('list', array('entity'=> $entity)));
    }

    /**
     * Créer un formulaire pour supprimer une instance.
     *
     * @param String $entity
     * @param Integer $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete', array(
                'entity' => $entity,
                'id' => $id)
            ))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }

}
