<?php

namespace Gesdon2Bundle\Controller;

use DateTime;
use Gesdon2Bundle\Entity\Don;
use Gesdon2Bundle\Entity\Donateur;
use Gesdon2Bundle\Entity\Type;
use Gesdon2Bundle\Entity\Moyen;
use Gesdon2Bundle\Entity\Adresse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\NoResultException;

class DefaultController extends Controller
{

    /**
     * Menu des entités modifiables.
     *
     * @Route("/", name="index")
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
     * Traiter une requête en provenance du site Soutenir
     *
     * @Route("/soutenir", name="soutenir")
     *
     * @param Request $request
     */
    public function soutenirAction(Request $request)
    {

        /* REQUETE DE TEST
http://localhost/gesdon2/web/app_dev.php/soutenir?
donateur_type=Particulier&
donateur_nom=Dupont&
donateur_prenom=Martin&
donateur_courriel=martin.dupont%40france.fr&
adresse_adresse1=1%20rue%20des%20Arts&
adresse_adresse2=&
adresse_code_postal=01000&
adresse_ville=Paris&
adresse_pays=France&
don_date=2015-03-20&
don_montant=999993&
don_moyen=Carte%20bancaire&
don_moyen_desc=&
don_recurrence=0&
don_date_fin_recurrence=&
don_transac_num=&
        */

        // récupérer les paramètres de la requête
        $request = $request->query->all();
        dump($request);
        /*
         * I
         * vérifier l'existence d'un donateur identique
         */

        // invoquer Doctrine
        $dt = $this->getDoctrine();
        // invoquer l'Entity Manager de Doctrine
        $em = $dt->getManager();


        // retrouver l'objet Type correspondant au paramètre
        // TODO vérifier l'existence des paramètres avant de créer la requête
        // TODO ou bien remplacer l'absence de paramètre par un paramètre vide
        /** @var Type $type */
        $type = $dt
            ->getRepository('Gesdon2Bundle:Type')
            ->findOneBy(array('nom'=>$request['donateur_type']));
        dump($type);


        // créer un constructeur de requêtes DQL
        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        // sélectionner dans la table Donateur
        $qb ->select('d')
            ->from('Gesdon2Bundle:Donateur', 'd')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('d.type'    , '?1'),
                $qb->expr()->eq('d.nom'     , '?2'),
                $qb->expr()->eq('d.prenom'  , '?3'),
                $qb->expr()->eq('d.courriel', '?4')
            ))
            // TODO vérifier l'existence des paramètres avant de créer la requête
            // TODO ou bien remplacer l'absence de paramètre par un paramètre vide
            ->setParameters(array(
                1=>$type,
                2=>$request['donateur_nom'],
                3=>$request['donateur_prenom'],
                4=>$request['donateur_courriel'],
            ));

        /** @var bool $nouveau_donateur Passe à vrai si un donateur est créé. */
        $nouveau_donateur = false;
        /** @var bool $nouvelle_adresse Passe à vrai si aucune adresse trouvée. */
        $nouvelle_adresse = false;

        try
        {
            /** @var Donateur $donateur */
            $donateur = $qb->getQuery()->getSingleResult();
            dump($donateur);
        }
        /*
         * II
         * Si le donateur n'existe pas, le créer
         */
        catch(NoResultException $e)
        {
            // notifier la création d'un nouveau donateur
            $nouveau_donateur = true;
            // créer l'objet
            $donateur = new Donateur();

            // affecter les paramètres
            $donateur->setType      ($type);
            $donateur->setNom       ($request['donateur_nom']);
            $donateur->setPrenom    ($request['donateur_prenom']);
            $donateur->setCourriel  ($request['donateur_courriel']);

            // persister l'objet
            //TODO traiter les erreurs SQL
            $em->persist($donateur);
            $em->flush();
        }
        // nettoyer le QueryBuilder
        $qb->resetDQLParts();
        /*
         * III
         * Si le donateur n'est pas nouveau,
         * vérifier l'existence d'une adresse identique associée à ce donateur
         */
        if (!$nouveau_donateur)
        {
            $qb ->select('a')
                ->from('Gesdon2Bundle:Adresse', 'a')
                ->where($qb->expr()->andX(
                    $qb->expr()->eq('a.donateur'    , '?1'),
                    $qb->expr()->eq('a.adresse1'    , '?2'),
                    $qb->expr()->eq('a.adresse2'    , '?3'),
                    $qb->expr()->eq('a.codePostal'  , '?4'),
                    $qb->expr()->eq('a.ville'       , '?5'),
                    $qb->expr()->eq('a.pays'        , '?6')
                ))
                // TODO vérifier l'existence des paramètres avant de créer la requête
                // TODO ou bien remplacer l'absence de paramètre par un paramètre vide
                ->setParameters(array(
                    // TODO Donateur est une entité
                    1=>$donateur,
                    2=>$request['adresse_adresse1'],
                    3=>$request['adresse_adresse2'],
                    4=>$request['adresse_code_postal'],
                    5=>$request['adresse_ville'],
                    6=>$request['adresse_pays'],
                ));
            try
            {
                /** @var Adresse $adresse */
                $adresse = $qb->getQuery()->getSingleResult();
            }
            catch(NoResultException $e)
            {
                $nouvelle_adresse = true;
            }
        }

        /*
         * IV
         * S'il s'agit d'un nouveau donateur, ou qu'aucune adresse n'est trouvée,
         * créer une adresse
         */
        if($nouveau_donateur||$nouvelle_adresse)
        {
            // créer l'objet
            $adresse = new Adresse();

            // affecter les paramètres
            $adresse->setDonateur   ($donateur);
            $adresse->setAdresse1   ($request['adresse_adresse1']);
            $adresse->setAdresse2   ($request['adresse_adresse2']);
            $adresse->setCodePostal ($request['adresse_code_postal']);
            $adresse->setVille      ($request['adresse_ville']);
            $adresse->setPays       ($request['adresse_pays']);

            // persister l'objet
            //TODO traiter les erreurs SQL
            $em->persist($adresse);
            $em->flush();
        }

        /*
         * V
         * Créer un don
         */
        // créer l'objet
        /** @var Don $don Le nouveau don. */
        $don = new Don();

        // retrouver l'objet Moyen correspondant au paramètre
        /** @var Moyen $moyen Moyen de paiement. */
        $moyen = $dt
            ->getRepository('Gesdon2Bundle:Moyen')
            ->findOneBy(array('nom'=>$request['don_moyen']));

        // formater les dates
        if($request['don_date']=="")
            $date = new DateTime('now');
        else
            $date = new DateTime($request['don_date']);
        if($request['don_date_fin_recurrence']=="")
            $dateFinRecurrence = null;
        else
            $dateFinRecurrence = new DateTime($request['don_date_fin_recurrence']);


        // affecter les paramètres
        $don->setAdresse            ($adresse);
        $don->setDate               ($date);
        $don->setMontant            ($request['don_montant']);
        $don->setMoyen              ($moyen);
        $don->setMoyenDesc          ($request['don_moyen_desc']);
        $don->setRecurrence         ($request['don_recurrence']);
        $don->setdateFinRecurrence  ($dateFinRecurrence);
        $don->setTransacNum         ($request['don_transac_num']);
        $don->setCourriel           ($donateur->getCourriel());

        // persister l'objet
        //TODO traiter les erreurs SQL
        $em->persist($don);
        $em->flush();

        // TODO retourner quelque chose
    }
}
