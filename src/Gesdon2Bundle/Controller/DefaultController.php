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
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
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
        /*
         * Variables utiles
         */
        /** @var bool $nouveau_donateur Passe à vrai si un donateur est créé. */
        $nouveau_donateur = false;
        /** @var bool $nouvelle_adresse Passe à vrai si aucune adresse trouvée. */
        $nouvelle_adresse = false;

        // récupérer les paramètres de la requête
        $params = $request->query->all();
        dump($params);

        // invoquer Doctrine
        $dt = $this->getDoctrine();
        // invoquer l'Entity Manager de Doctrine
        $em = $dt->getManager();


        // TODO vérifier l'existence des paramètres avant de créer la requête
        // TODO ou bien remplacer l'absence de paramètre par un paramètre vide
        /** @var Type $donateurType */
        $donateurType = $dt->getRepository('Gesdon2Bundle:Type')->findOneBy(array('nom'
            => $params['donateur_type']));
        /** @var string $donateurNom Nom du donateur */
        $donateurNom
            = (!empty($params['donateur_nom']) ? $params['donateur_nom'] : '');
        /** @var string $donateurPrenom Prénom du donateur */
        $donateurPrenom
            = (!empty($params['donateur_prenom']) ? $params['donateur_prenom'] : '');
        /** @var string $donateurCourriel Adresse courriel du donateur */
        $donateurCourriel
            = (!empty($params['donateur_courriel']) ? $params['donateur_courriel'] : '');
        /** @var string $adresseAdresse1 Numéro et voie */
        $adresseAdresse1
            = (!empty($params['adresse_adresse1']) ? $params['adresse_adresse1'] : '');
        /** @var string $adresseAdresse2 Complément d'adresse */
        $adresseAdresse2
            = (!empty($params['adresse_adresse2']) ? $params['adresse_adresse2'] : '');
        /** @var string $adresseCodePostal Code postal */
        $adresseCodePostal
            = (!empty($params['adresse_code_postal']) ? $params['adresse_code_postal'] : '');
        /** @var string $adresseVille Commune */
        $adresseVille
            = (!empty($params['adresse_ville']) ? $params['adresse_ville'] : '');
        /** @var string $adressePays Pays */
        $adressePays
            = (!empty($params['adresse_pays']) ? $params['adresse_pays'] : '');
        /** @var DateTime $donDate Date de création du don */
        if($params['don_date']=="") $donDate = new DateTime('now');
        else $donDate
            = new DateTime($params['don_date']);
        /** @var string $donMontant Montant du don, TODO chaîne de caractères? */
        $donMontant
            = (!empty($params['don_montant']) ? $params['don_montant'] : '');
        /** @var Moyen $moyen Moyen de paiement. */
        $donMoyen = $dt->getRepository('Gesdon2Bundle:Moyen')->findOneBy(array('nom'
            => $params['don_moyen']));
        /** @var string $donMoyenDesc Description du moyen de paiement */
        $donMoyenDesc
            = (!empty($params['don_moyen_desc']) ? $params['don_moyen_desc'] : '');
        /** @var bool $donRecurrence Indicateur de recurrence du don */
        $donRecurrence
            = $params['don_recurrence'];
        /** @var DateTime $donDateFinRecurrence Date de fin de récurrence du don */
        if($params['don_date_fin_recurrence']=="") $donDateFinRecurrence = null;
        else $donDateFinRecurrence = new DateTime($params['don_date_fin_recurrence']);
        /** @var string $donTransacNum Numéro de transaction (Paypal ou CM-CIC) */
        $donTransacNum
            = (!empty($params['don_transac_num']) ? $params['don_transac_num'] : '');

        /*
         * I
         * vérifier l'existence d'un donateur identique
         */

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
                1=>$donateurType,
                2=>$donateurNom,
                3=>$donateurPrenom,
                4=>$donateurCourriel,
            ));

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
            $donateur->setType      ($donateurType);
            $donateur->setNom       ($donateurNom);
            $donateur->setPrenom    ($donateurPrenom);
            $donateur->setCourriel  ($donateurCourriel);

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
                    1=>$donateur,
                    2=>$adresseAdresse1,
                    3=>$adresseAdresse2,
                    4=>$adresseCodePostal,
                    5=>$adresseVille,
                    6=>$adressePays,
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
            $adresse->setDonateur   ($donateur); // TODO vérifier le type d'entrée des setters
            $adresse->setAdresse1   ($adresseAdresse1);
            $adresse->setAdresse2   ($adresseAdresse2);
            $adresse->setCodePostal ($adresseCodePostal);
            $adresse->setVille      ($adresseVille);
            $adresse->setPays       ($adressePays);

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

        // affecter les paramètres
        $don->setAdresse            ($adresse); // TODO vérifier le type d'entrée des setters
        $don->setDate               ($donDate);
        $don->setMontant            ($donMontant);
        $don->setMoyen              ($donMoyen);
        $don->setMoyenDesc          ($donMoyenDesc);
        $don->setRecurrence         ($donRecurrence);
        $don->setdateFinRecurrence  ($donDateFinRecurrence);
        $don->setTransacNum         ($donTransacNum);
        $don->setCourriel           ($donateur->getCourriel());

        // persister l'objet
        //TODO traiter les erreurs SQL
        $em->persist($don);
        $em->flush();

        // TODO retourner quelque chose
        $response = new Response();
        $response->setStatusCode(200);
        return new $response;
    }
}
