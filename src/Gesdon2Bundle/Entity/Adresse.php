<?php

namespace Gesdon2Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Adresse
 *
 * @ORM\Table(name="adresse")
 * @ORM\Entity()
 */
class Adresse
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
     * Donateur lié.
     * Clé étrangère.
     *
     * @var donateur
     *
     * @ORM\ManyToOne(targetEntity="Donateur", cascade={"persist"})
     * @ORM\JoinColumn(name="donateur", referencedColumnName="id", nullable=true)
     **/
    private $donateur;

    /**
     * Adresse, première ligne.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="adresse1", type="string", length=255, nullable=true)
     */
    private $adresse1;

    /**
     * Adresse, deuxième ligne.
     *
     * @var string
     *
     * @ORM\Column(name="adresse2", type="string", length=255, nullable=true)
     */
    private $adresse2;

    /**
     * Code Postal.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="codePostal", type="string", length=5, nullable=true)
     */
    private $codePostal;

    /**
     * Ville.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * Pays.
     *
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;


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
     * Set donateur
     *
     * @param integer $donateur
     * @return Adresse
     */
    public function setDonateur($donateur)
    {
        $this->donateur = $donateur;
        return $this;
    }

    /**
     * Get donateur
     *
     * @return donateur
     */
    public function getDonateur()
    {
        return $this->donateur;
    }

    /**
     * Set adresse1
     *
     * @param string $adresse1
     * @return Adresse
     */
    public function setAdresse1($adresse1)
    {
        $this->adresse1 = $adresse1;
        return $this;
    }

    /**
     * Get adresse1
     *
     * @return string 
     */
    public function getAdresse1()
    {
        return $this->adresse1;
    }

    /**
     * Set adresse2
     *
     * @param string $adresse2
     * @return Adresse
     */
    public function setAdresse2($adresse2)
    {
        $this->adresse2 = $adresse2;
        return $this;
    }

    /**
     * Get adresse2
     *
     * @return string 
     */
    public function getAdresse2()
    {
        return $this->adresse2;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return Adresse
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Adresse
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return Adresse
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }


    // TODO bien définir cette méthode, dans la mesure où elle sert à sélectionner l'adresse pour créer un don
    /**
     * Renvoie une chaîne décrivant l'objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->adresse1;
    }
}
