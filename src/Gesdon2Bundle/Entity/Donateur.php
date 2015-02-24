<?php

namespace Gesdon2Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Donateur
 *
 * @ORM\Table(
 * name="donateur",
 * uniqueConstraints={@UniqueConstraint(name="courriel", columns={"courriel"})}
 * )
 * @ORM\Entity()
 * @UniqueEntity("courriel")
 */
class Donateur
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
     * Type lié.
     * Requis.
     * Clé étrangère.
     *
     * @var type
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="Type", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * Nom ou raison sociale.
     * Requis.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;

    /**
     * Prénom.
     * Non requis pour les sociétés.
     *
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * Courriel.
     * Requis.
     *
     * @var string
     *
     * @ORM\Column(name="Courriel", type="string", length=255)
	 * @Assert\NotBlank()
     * @Assert\Email(
     * message="{{ value }} n'est pas un email valide.",
     * checkMX=false
     * )
     */
    private $courriel;


    /*---------------------------------------------------------

                            ACCESSEURS

    -----------------------------------------------------------*/


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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Donateur
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

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Donateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set courriel
     *
     * @param string $courriel
     * @return Donateur
     */
    public function setCourriel($courriel)
    {
        $this->courriel = $courriel;

        return $this;
    }

    /**
     * Get courriel
     *
     * @return string 
     */
    public function getCourriel()
    {
        return $this->courriel;
    }

    // TODO bien définir cette méthode, dans la mesure où elle sert à sélectionner le donateur pour créer une adresse
    /**
     * Renvoie une chaîne décrivant l'objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom . " " . $this->prenom;
    }
}
