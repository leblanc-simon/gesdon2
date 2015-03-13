<?php

namespace Gesdon2Bundle\Controller;

use Gesdon2Bundle\Entity\Don;
use Gesdon2Bundle\Form\DonType;
use Gesdon2Bundle\Form\SearchDonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DonController extends Controller
{

    /**
     * Afficher la liste des dons.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $form = $this->createSearchForm('Don');

        // générer la page à retourner à partir du template twig "search"
        return $this->render('Gesdon2Bundle:Don:search.html.twig',
            array(
                'list_form' => $form->createView(), // créer la vue à partir du formulaire
            )
        );
    }

    /**
     * Créer un formulaire pour rechercher des dons.
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createSearchForm()
    {
        // créer l'objet type
        $typeObject = new SearchDonType();

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            //pas de données initiales
            null,
            array(
                'action' => $this->generateUrl('don_search'),
                'method' => 'POST',
            )
        );

        $form->add('don_search', 'submit', array(
            'label' => 'Rechercher',
            'disabled' => 'true',
        ));

        return $form;
    }

    /**
     * Créer le tableau HTML des dons
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableAction(){
        $em = $this->getDoctrine()->getManager();

        // retrouver la table
        $repository = $em->getRepository('Gesdon2Bundle:Don');
        // créer un constructeur de requêtes sur la table
        $qb = $repository->createQueryBuilder('Don');

        // retrouver les données du formulaire
        $filter = $_POST;
        if (!empty($filter)) {
            // créer une expression AND
            $andX = $qb->expr()->andX();
            // pour chaque champ du filtre et sa valeur
            foreach ($filter as $column => $value) {
                // La fonction IDENTITY permet de filtrer sur la colonne correspondant à la clef étrangère, sans avoir à faire la jointure
                // Sans cette fonction, Doctirne renvoit l'erreur "Invalid PathExpression. Must be a StateFieldPathExpression"
                // TODO gérer les Types! l'erreur Invalid PathExpression remet ça!
                if (is_array($value)) {
                    // un champ de formulaire de type 'choice' renvoit une sélection multiple sous forme de tableau
                    // transformer le tableau en chaînes séparées par des virgules
                    $elements = $value->toArray();
                    // si le tableau n'est pas vide...
                    if (!empty($elements)) {
                        /** @var string $ids Chaîne des Id */
                        $ids = '';
                        $i = 0;
                        $len = count($elements);
                        // pour chaque objet du tableau
                        foreach ($elements as $object) {
                            // ajouter l'Id à la chaîne
                            $ids = $ids . $object->getId();
                            if ($i != $len - 1) {
                                $ids = $ids . ',';
                            }
                            $i++;
                        }
                        // passer la chaîne des Id dans la clause
                        $andX->add("IDENTITY(Don.{$column}) IN ({$ids})");
                    }
                } else {
                    // si le champ n'est pas vide
                    if ($value != '') {
                        $andX->add($qb->expr()->like(
                            "Don.{$column}",
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
        // exécuter la requête et retrouver le résultat
        $instances = $qb->getQuery()->getResult();

        // générer la page à retourner à partir du template twig "table"
        // en passant la liste des donateurs
        return $this->render('Gesdon2Bundle:Don:table.html.twig',
            array(
                'instances'=> $instances
            )
        );
    }

    /**
     * Générer le formulaire de création de don.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", name="new")
     * @Method("GET")
     * @Template("Gesdon2Bundle:Don:new.html.twig")
     */
    public function newAction(Request $request)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getManager();

        // créer l'objet
        $instance = new Don();

    	// créer l'objet formulaire
        $typeObject = new DonType();
        $form = $this->createForm($typeObject, $instance);

        // ajouter le bouton d'envoi
        $form->add('submit', 'submit', array('label' => 'Créer'));

        // traiter la soumission du formulaire
        $form->handleRequest($request);

        // si le formulaire est validé
        if ($form->isValid()) {
            // récupérer les données du formulaire
            $instance = $form->getData();

            // persister l'objet
            //TODO traiter les erreurs SQL
            $em->persist($instance);
            $em->flush();

            return $this->redirect($this->generateUrl('don_edit', array(
                'id' => $instance->getId()
            )));
        }

        // retourner le formulaire d'ajout
        // le nom de la classe et le formulaire correspondant au type d'objet sont passés en paramètre
        return $this->render('Gesdon2Bundle:Don:new.html.twig',
            array
            (
                'form'  => $form->createView()
            )
        );
    }

    /**
     * Afficher un formulaire pour modifier un don.
     *
     * @param int $id           L'identifiant du don
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/edit", name="edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // retrouver l'instance de l'entité avec l'id
        $instance = $em->getRepository('Gesdon2Bundle:Don')->find($id);

        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Don instance.');
        }

        $editForm = $this->createEditForm($instance, $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Créer un formulaire pour modifier un don.
     *
     * @param object $instance  L'objet Don
     * @param int $id           L'identifiant du don
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm($instance, $id)
    {
        $typeObject = new DonType();

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            $instance,
            array(
                'action' => $this->generateUrl(
                    'don_update',
                    array(
                        'id' => $id
                    )
                ),
                'method' => 'PUT',)
        );

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Modifier un don.
     *
     * @param Request $request
     * @param int $id           L'identifiant du don.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/Don/{id}", name="update")
     * @Method("PUT")
     * @Template("Gesdon2Bundle:Don:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // retrouver l'instance par son ID
        $instance = $em->getRepository('Gesdon2Bundle:Don')->find($id);
        // si l'instance est introuvable, revoyer un message d'erreur
        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Don instance.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($instance, $id);
        $editForm->handleRequest($request);

        // si le formulaire est validé...
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('don_edit', array(
                'id' => $id)));
        }

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Supprimer un don.
     *
     * @param Request $request
     * @param int $id           l'identifiant du don
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{entity}/{id}", name="delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // retrouver l'instance grâce à son ID
            $instance = $em->getRepository('Gesdon2Bundle:Don')->find($id);

            // si l'instance est introuvable, renvoyer un message d'erreur
            if (!$instance) {
                throw $this->createNotFoundException('Unable to find Don instance.');
            }

            // supprimer l'instance
            $em->remove($instance);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('don_search'));
    }

    /**
     * Créer un formulaire pour supprimer un don.
     *
     * @param int $id           L'identifiant du don
     *
     * @return \Symfony\Component\Form\Form Le formulaire
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('don_delete', array(
                'id' => $id)
            ))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }

}
