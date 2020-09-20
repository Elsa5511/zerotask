<?php

namespace Certification\Entity;

use Doctrine\ORM\Mapping as ORM;
    
/**
 * Equipment Certification
 *
 * @ORM\Table(name="equipment_certification")
 * @ORM\Entity(repositoryClass="Certification\Repository\CertificationRepository")
 */
class Certification extends \Acl\Entity\AbstractEntity
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="certification_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $certificationId;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $user;
    
    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id",nullable=false)
     * })
     */
    protected $equipment;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="theory_passed", type="boolean", nullable=false)
     */
    protected $theoryPassed = false;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="practical_passed", type="boolean", nullable=false)
     */
    protected $practicalPassed = false;
        
    /**
     * @var boolean
     * 
     * @ORM\Column(name="valid", type="boolean", nullable=false)
     */
    protected $valid = false;
        
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", nullable=true)
     */
    protected $expirationDate;

    
    public function getCertificationId()
    {
        return $this->certificationId;
    }

    public function setCertificationId($certificationId)
    {
        $this->certificationId = $certificationId;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(\Application\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getEquipment()
    {
        return $this->equipment;
    }

    public function setEquipment(\Equipment\Entity\Equipment $equipment)
    {
        $this->equipment = $equipment;
    }

    public function getTheoryPassed()
    {
        return $this->theoryPassed;
    }

    public function setTheoryPassed($theoryPassed)
    {
        $this->theoryPassed = $theoryPassed;
    }

    public function getPracticalPassed()
    {
        return $this->practicalPassed;
    }

    public function setPracticalPassed($practicalPassed)
    {
        $this->practicalPassed = $practicalPassed;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public function getUserOrganization() {
        if($this->getUser()) {
            return $this->getUser()->getOrganization();
        }
    }

}