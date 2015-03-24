<?php

namespace Gesdon2Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Don
 *
 * @ORM\Table(name="don")
 * @ORM\Entity()
 */
class Don
{
    /**
     * @var integer Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Adresse liée.
     * Clé étrangère.
     *
     * @var adresse Adresse
     *
     * @ORM\ManyToOne(targetEntity="Adresse",cascade={"persist"})
     * @ORM\JoinColumn(name="adresse", referencedColumnName="id", nullable=true)
     **/
    private $adresse;

    /**
     * @var \DateTime Date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string Montant
     *
     * @ORM\Column(name="montant", type="decimal", nullable=true)
     */
    private $montant;

    /**
     * Moyen de paiement lié.
     * Requis.
     * Clé étrangère.
     *
     * @var moyen
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Moyen")
     * @ORM\JoinColumn(name="moyen", referencedColumnName="id", nullable=true)
     */
    private $moyen;

    /**
     * @var string Description du moyen de paiement, si "autre".
     *
     * @ORM\Column(name="moyenDesc", type="string", length=255, nullable=true)
     */
    private $moyenDesc;

    /**
     * @var boolean Recurrence
     *
     * @ORM\Column(name="recurrence", type="boolean", nullable=true)
     */
    private $recurrence;

    /**
     * @var string Numéro de transaction
     *
     * @ORM\Column(name="transacNum", type="string", length=255, nullable=true)
     */
    private $transacNum;

    /**
     * @var \DateTime Date de fin de récurrence
     *
     * @ORM\Column(name="recurrDateFin", type="datetime", nullable=true)
     */
    private $dateFinRecurrence;

    /**
     * Adresse courriel du donateur à la date du don.
     *
     * @var string
     *
     * @ORM\Column(name="courriel", type="string", length=255, nullable=true)
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
     * @param Adresse $adresse
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
     * Set dateFinRecurrence
     *
     * @param \DateTime $dateFinRecurrence
     * @return Don
     */
    public function setdateFinRecurrence($dateFinRecurrence)
    {
        $this->dateFinRecurrence = $dateFinRecurrence;

        return $this;
    }

    /**
     * Get dateFinRecurrence
     *
     * @return \DateTime
     */
    public function getdateFinRecurrence()
    {
        return $this->dateFinRecurrence;
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
