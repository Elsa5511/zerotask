<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LadocRestraintNonCertified
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_restraint_non_certified")
 */
class LadocRestraintNonCertified {
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation\Entity\LadocDocumentation", inversedBy="loadRestraintNonCertifieds")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="load_documentation_id", referencedColumnName="id")
     * })
     */
    protected $loadDocumentation;

    /**
     * @var \LadocDocumentation\Entity\LadocDocumentation
     *
     * @ORM\ManyToOne(targetEntity="LadocDocumentation\Entity\LadocDocumentation", inversedBy="carrierRestraintCertifieds")
     * @ORM\JoinColumn(name="carrier_documentation_id", referencedColumnName="id")
     */
    protected $carrierDocumentation;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLoadDocumentation() {
        return $this->loadDocumentation;
    }

    public function setLoadDocumentation($loadDocumentation) {
        $this->loadDocumentation = $loadDocumentation;
    }

    public function getCarrierDocumentation () {
        return $this->carrierDocumentation;
    }

    public function setCarrierDocumentation ($carrierDocumentation) {
        $this->carrierDocumentation = $carrierDocumentation;
    }

    public function setLadocDocumentationWithTypeChecked (LadocDocumentation $ladocDocumentation) {
        if($ladocDocumentation->getType() == 'load')
            $this->setLoadDocumentation($ladocDocumentation);
        elseif($ladocDocumentation->getType() == 'carrier')
            $this->setCarrierDocumentation($ladocDocumentation);
    }

    public function getTitle($middleText) {
        $type = $this->getLoadDocumentation()->getType();
        $firstText = $type == 'load' ? $this->getLoadDocumentation() : $this->getCarrierDocumentation();
        $secondText = $type == 'load' ? $this->getCarrierDocumentation() : $this->getLoadDocumentation();
        return $firstText . ' ' . $middleText . ' ' . $secondText;
    }
}