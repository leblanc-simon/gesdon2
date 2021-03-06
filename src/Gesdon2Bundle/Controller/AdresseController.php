<?php

namespace Gesdon2Bundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AdresseController extends Controller
{

    /**
     * Afficher la liste des instances de l'entité passée en paramètre.
     *
     * @param Request $request  Tableau des champs utilisés pour le filtre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // retrouver la table
        $repository = $em->getRepository('Gesdon2Bundle:Adresse');
        // créer un constructeur de requêtes sur la table
        $qb = $repository->createQueryBuilder('Adresse');

        // exécuter la requête
        $instances = $qb->getQuery()->getResult();

        // retrouver les attributs de l'entité
        // TODO ne renvoie pas les clés étrangères. Trouver autre méthode.
        $fields = $em ->getClassMetadata('Gesdon2Bundle:Adresse')->getColumnNames();

        $form = $this->createListForm('Adresse');

        // si le formulaire de filtrage est soumis
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            // retrouver les données depuis le formulaire
            $filter = $form->getData();

            // créer une expression AND
            $andX = $qb->expr()->andX();
            // pour chaque champ du filtre et sa valeur
            foreach ($filter as $column => $value)
            {
                // La fonction IDENTITY permet de filtrer sur la colonne correspondant à la clef étrangère, sans avoir à faire la jointure
                // Sans cette fonction, Doctirne renvoit l'erreur "Invalid PathExpression. Must be a StateFieldPathExpression"
                if ($value instanceof ArrayCollection)
                {
                    // un champ de formulaire de type 'choice' renvoit une sélection multiple sous forme de tableau
                    // transformer le tableau en chaînes séparées par des virgules
                    $elements = $value->toArray();
                    // si le tableau n'est pas vide...
                    if (!empty($elements))
                    {
                        /** @var string $ids Chaîne des Id*/
                        $ids = '';
                        $i = 0;
                        $len = count($elements);
                        // pour chaque objet du tableau
                        foreach ($elements as $object) {
                            // ajouter l'Id à la chaîne
                            $ids = $ids . $object->getId();
                            if ($i != $len - 1){
                                $ids = $ids . ',';
                            }
                            $i++;
                        }
                        // passer la chaîne des Id dans la clause
                        $andX->add("IDENTITY(Adresse.{$column}) IN ({$ids})");
                    }
                } else {
                    // si le champ n'est pas vide
                    if ($value != '')
                    {
                        $andX->add($qb->expr()->like("{'Adresse'}.{$column}", "'{$value}'"));
                    }
                }
            }
            // si des champs du filtre ont été renseignés, définir la clause where
            if (!empty($andX->getParts())){
                $qb->where($andX);
            }

            // exécuter la requête et retrouver le résultat
            $instances = $qb->getQuery()->getResult();

        }


        // générer la page à retourner à partir du template twig "list"
        // en passant la liste des instances de l'entité
        return $this->render('Gesdon2Bundle:Adresse:list.html.twig',
            array(
                'list_form' => $form->createView(), // créer la vue à partir du formulaire
                'instances'=> $instances,
                'entity'  => 'Adresse'
            )
        );
    }

    /**
     * Créer un formulaire pour filtrer la liste des instances d'une entité.
     *
     * @param string $entity    Le nom de l'entité
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createListForm($entity)
    {
        // créer l'objet type à partir du nom
        $type = 'Gesdon2Bundle\\Form\\Search' . $entity . "Type";
        $typeObject = new $type;

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            //pas de données initiales
            null,
            array(
                'action' => $this->generateUrl(
                    'adresse_list'
                ),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Filtrer'));

        return $form;
    }

    /**
     * Générer le formulaire de création d'instance de l'entité passée en paramètre.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", name="new")
     * @Method("GET")
     * @Template("Gesdon2Bundle:Adresse:new.html.twig")
     */
    public function newAction(Request $request)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getManager();

        // concaténer le namespace et le nom de la classe
        $namespaceClasse = 'Gesdon2Bundle\\Entity\\Adresse';
        // créer l'objet
        $entityObject = new $namespaceClasse;

    	// créer l'objet formulaire à partir du type
        $type = 'Gesdon2Bundle\\Form\\AdresseType';
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

            return $this->redirect($this->generateUrl('adresse_edit', array(
                'id' => $entityObject->getId()
            )));
        }

        // retourner le formulaire d'ajout
        // le nom de la classe et le formulaire correspondant au type d'objet sont passés en paramètre
        return $this->render('Gesdon2Bundle:Adresse:new.html.twig',
            array
            (
                'form'  => $form->createView()
            )
        );
    }

    /**
     * Afficher un formulaire pour modifier un instance.
     *
     * @param int $id           L'identifiant de l'instance
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
        $instance = $em->getRepository('Gesdon2Bundle:Adresse')->find($id);

        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Adresse instance.');
        }

        $editForm = $this->createEditForm($instance, $id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Créer un formulaire pour modifier une instance.
     *
     * @param object $instance  L'objet d'instance
     * @param int $id           L'identifiant de l'isntance
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm($instance, $id)
    {
        // créer l'objet type à partir du nom
        $type = 'Gesdon2Bundle\\Form\\AdresseType';
        $typeObject = new $type;

        // créer le formulaire
        $form = $this->createForm(
            $typeObject,
            $instance,
            array(
                'action' => $this->generateUrl(
                    'adresse_update',
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
     * Modifier une instance.
     *
     * @param Request $request
     * @param int $id           L'identifiant de l'instance
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{entity}/{id}", name="update")
     * @Method("PUT")
     * @Template("Gesdon2Bundle:Adresse:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // retrouver l'instance par son ID
        $instance = $em->getRepository('Gesdon2Bundle:Adresse')->find($id);
        // si l'instance est introuvable, revoyer un message d'erreur
        if (!$instance) {
            throw $this->createNotFoundException('Unable to find Adresse instance.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($instance, $id);
        $editForm->handleRequest($request);

        // si le formulaire est validé...
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adresse_edit', array(
                'id' => $id)));
        }

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Supprimer une instance.
     *
     * @param Request $request
     * @param int $id           l'identifiant de l'instance
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
            $instance = $em->getRepository('Gesdon2Bundle:Adresse')->find($id);

            // si l'instance est introuvable, renvoyer un message d'erreur
            if (!$instance) {
                throw $this->createNotFoundException('Unable to find Adresse instance.');
            }

            // supprimer l'instance
            $em->remove($instance);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adresse_list'));
    }

    /**
     * Créer un formulaire pour supprimer une instance.
     *
     * @param int $id           L'identifiant de l'instance
     *
     * @return \Symfony\Component\Form\Form Le formulaire
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adresse_delete', array(
                'id' => $id)
            ))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }

}
