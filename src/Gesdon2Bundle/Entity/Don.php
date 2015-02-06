<?php

namespace Gesdon2Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Don
 *
 * @ORM\Table(name="don")
 * @ORM\Entity(repositoryClass="Gesdon2Bundle\Entity\DonRepository")
 */
class Don
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    // clé étrangère : identifiant de l'adresse où est domicilié le don
    /**
     * @var adresse
     *
     * @ORM\ManyToOne(targetEntity="Adresse")
     * @ORM\JoinColumn(name="adresse", referencedColumnName="id")
     **/
    private $adresse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal")
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="moyen", type="string", length=63)
     */
    private $moyen;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenDesc", type="string", length=255)
     */
    private $moyenDesc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recurrence", type="boolean")
     */
    private $recurrence;

    /**
     * @var string
     *
     * @ORM\Column(name="transacNum", type="string", length=255)
     */
    private $transacNum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="recurrDateFin", type="datetime")
     */
    private $recurrDateFin;

    // courriel avec lequel a été effectué le don.
    // enregistré pour historique
    /**
     * @var string
     *
     * @ORM\Column(name="courriel", type="string", length=255)
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
     * Set adresse
     *
     * @param integer $adresse
     * @return Adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * Get adresse
     *
     * @return adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Don
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Don
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set moyen
     *
     * @param string $moyen
     * @return Don
     */
    public function setMoyen($moyen)
    {
        $this->moyen = $moyen;

        return $this;
    }

    /**
     * Get moyen
     *
     * @return string 
     */
    public function getMoyen()
    {
        return $this->moyen;
    }

    /**
     * Set moyenDesc
     *
     * @param string $moyenDesc
     * @return Don
     */
    public function setMoyenDesc($moyenDesc)
    {
        $this->moyenDesc = $moyenDesc;

        return $this;
    }

    /**
     * Get moyenDesc
     *
     * @return string 
     */
    public function getMoyenDesc()
    {
        return $this->moyenDesc;
    }

    /**
     * Set recurrence
     *
     * @param boolean $recurrence
     * @return Don
     */
    public function setRecurrence($recurrence)
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * Get recurrence
     *
     * @return boolean 
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * Set transacNum
     *
     * @param string $transacNum
     * @return Don
     */
    public function setTransacNum($transacNum)
    {
        $this->transacNum = $transacNum;

        return $this;
    }

    /**
     * Get transacNum
     *
     * @return string 
     */
    public function getTransacNum()
    {
        return $this->transacNum;
    }

    /**
     * Set recurrDateFin
     *
     * @param \DateTime $recurrDateFin
     * @return Don
     */
    public function setRecurrDateFin($recurrDateFin)
    {
        $this->recurrDateFin = $recurrDateFin;

        return $this;
    }

    /**
     * Get recurrDateFin
     *
     * @return \DateTime 
     */
    public function getRecurrDateFin()
    {
        return $this->recurrDateFin;
    }

    /**
     * Set courriel
     *
     * @param string $courriel
     * @return Don
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
}
