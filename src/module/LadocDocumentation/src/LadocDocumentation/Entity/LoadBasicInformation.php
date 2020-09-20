<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("load_basic_information")
 */
class LoadBasicInformation extends BasicInformation {

    /**
     * @ORM\ManyToMany(targetEntity="FormOfTransportation")
     * @ORM\JoinTable(name="load_basic_information_to_form_of_transportation",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="load_basic_information__id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="form_of_transportation_id", referencedColumnName="id")
     *      })
     */
    protected $approvedFormsOfTransportation;

    /**
     * @ORM\ManyToMany(targetEntity="Stanag")
     * @ORM\JoinTable(name="load_basic_information_to_stanag",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="load_basic_information__id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="stanag_id", referencedColumnName="id")
     *      })
     */
    protected $stanags;

    /**
     * @var string
     *
     * @ORM\Column(name="equivalent_models", type="string", length=255, nullable=true)
     */
    protected $equivalentModels;


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
//        $this->responsibleOffices = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * @return string
     */
    public function getEquivalentModels() {
        return $this->equivalentModels;
    }

    /**
     * @param string $equivalentModels
     */
    public function setEquivalentModels($equivalentModels) {
        $this->equivalentModels = $equivalentModels;
    }
}