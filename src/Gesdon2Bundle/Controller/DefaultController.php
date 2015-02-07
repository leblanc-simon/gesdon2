<?php

namespace Gesdon2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $routes = array("donateur", "adresse", "don");
        return $this->render('Gesdon2Bundle:Default:index.html.twig',
            array(
                'routes' => $routes
            )
        );
    }

    /**
     * Afficher la liste des instances de l'entité passée en paramètre.
     *
     * @param $nomEntite
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeAction($nomEntite)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getEntityManager();

        // retrouver la liste des instances de l'entité
        $nomBundle = "Gesdon2Bundle:";
        $entite = $em->getRepository($nomBundle.$nomEntite);
        $instances = $entite->findAll();

        // retrouver les attributs de l'entité
        $colonnes = $em ->getClassMetadata($nomBundle.$nomEntite)
                        ->getColumnNames();

        // générer la page à retourner à partir du template twig "index"
        // en passant la liste des instances de l'entité
        return $this->render('Gesdon2Bundle:Default:liste.html.twig',
            array(
                'instances' => $instances,
                'classe'    => $nomEntite,
                'colonnes'  => $colonnes
            )
        );
    }

    /**
     * Générer le formulaire de création d'instance de l'entité passée en paramètre.
     *
     * @param Request $request
     * @param $classe
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajouterAction(Request $request, $classe)
    {
        // invoquer un Entity Manager pour persister les données
        $em = $this->getDoctrine()->getEntityManager();

        // concaténer le namespace et le nom de la classe
        // TODO vérifier s'il n'y a pas un meilleur procédé
        $namespace = 'Gesdon2Bundle\\Entity\\';
        $namespaceClasse = $namespace . $classe;
        // créer l'objet
        $objet = new $namespaceClasse;

    	// créer l'objet formulaire à partir du type
        // TODO idem, vérifier s'il n'y a pas un meilleur procédé
        $nomType = 'Gesdon2Bundle\\Form\\' . $classe . "Type";
        $type = new $nomType;
        $form = $this->createForm($type, $objet);

        // traiter la soumission du formulaire
        $form->handleRequest($request);

        // si le formulaire est validé
        if ($form->isValid()) {
            // récupérer les données du formulaire
            $objet = $form->getData();

            // persister l'objet
            //TODO traiter les erreurs SQL
            $em->persist($objet);
            $em->flush();

            //return $this->redirect($this->generateUrl('gesdon2_cree',array('nomEntite'=>$classe)));
            return $this->render('Gesdon2Bundle:Default:cree.html.twig',
                array(
                    'nomEntite' => $classe
                )
            );
        }

        // retourner le formulaire d'ajout
        // le nom de la classe et le formulaire correspondant au type d'objet sont passés en paramètre
        return $this->render('Gesdon2Bundle:Default:ajouter.html.twig',
            array
            (
                'objet' => $classe,
                'form'  => $form->createView()
            )
        );
    }


    /**
     * Afficher la page de notification de création de l'instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function creeAction(){
    	return $this->render('Gesdon2Bundle:Default:cree.html.twig');
    }
}
