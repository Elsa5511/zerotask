<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_basic_information")
 */
class CarrierBasicInformation extends BasicInformation {

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $technicalPayload;

    /**
     * @ORM\ManyToMany(targetEntity="FormOfTransportation")
     * @ORM\JoinTable(name="carrier_basic_information_to_form_of_transportation",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="carrier_basic_information__id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="form_of_transportation_id", referencedColumnName="id")
     *      })
     */
    protected $approvedFormsOfTransportation;

    /**
     * @ORM\ManyToMany(targetEntity="Stanag")
     * @ORM\JoinTable(name="carrier_basic_information_to_stanag",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="carrier_basic_information__id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="stanag_id", referencedColumnName="id")
     *      })
     */
    protected $stanags;

    /**
     * @var ResponsibleOffice
     *
     * @ORM\ManyToOne(targetEntity="ResponsibleOffice")
     * @ORM\JoinColumn(name="responsible_office_id", referencedColumnName="id")
     */
    protected $responsibleOffices;

    public function __construct()
    {
        $this->approvedFormsOfTransportation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stanags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getTechnicalPayload()
    {
        return $this->technicalPayload;
    }

    public function setTechnicalPayload($technicalPayload)
    {
        $this->technicalPayload = $technicalPayload;
    }

    public function getApprovedFormsOfTransportation()
    {
        return $this->approvedFormsOfTransportation;
    }

    public function setApprovedFormsOfTransportation($approvedFormsOfTransportation)
    {
        $this->approvedFormsOfTransportation = $approvedFormsOfTransportation;
    }

    public function removeApprovedFormsOfTransportation($approvedFormsOfTransportation) {
        foreach($approvedFormsOfTransportation as $aft) {
            $this->approvedFormsOfTransportation->removeElement($aft);
        }
    }

    public function addApprovedFormsOfTransportation($approvedFormsOfTransportation) {
        foreach($approvedFormsOfTransportation as $aft) {
            $this->approvedFormsOfTransportation->add($aft);
        }
    }

    public function getStanags()
    {
        return $this->stanags;
    }

    public function setStanags($stanags)
    {
        $this->stanags = $stanags;
    }

    public function removeStanags($stanags) {
        foreach($stanags as $stanag) {
            $this->stanags->removeElement($stanag);
        }
    }

    public function addStanags($stanags) {
        foreach($stanags as $stanag) {
            $this->stanags->add($stanag);
        }
    }

    public function getResponsibleOffices()
    {
        return $this->responsibleOffices;
    }

    public function setResponsibleOffices($responsibleOffices)
    {
        $this->responsibleOffices = $responsibleOffices;
    }
}