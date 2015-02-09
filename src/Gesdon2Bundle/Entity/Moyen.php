<?php

namespace Gesdon2Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Moyen de paiement.
 *
 * @ORM\Table(name="moyen")
 * @ORM\Entity(repositoryClass="Gesdon2Bundle\Entity\MoyenRepository")
 */
class Moyen
{
    /**
     * Identifiant.
     * Clé primaire auto-générée.
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Nom du moyen de paiement.
     * Requis.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=20)
     */
    private $nom;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Moyen
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    // TODO bien définir cette méthode, dans la mesure où elle sert à sélectionner le donateur pour créer une adresse
    /**
     * Renvoie une chaîne décrivant l'objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom;
    }
}
