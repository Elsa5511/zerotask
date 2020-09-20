<?php

namespace Application\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="language")
 */
class Language extends Entity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="language_id", type="integer")
     * */
    protected $languageId;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="isocode", type="string", length=2, nullable=false)
     */
    protected $isocode;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=false)
     */
    protected $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=false)
     */
    protected $dateUpdate;

    
    public function getId() {
        return $this->languageId;
    }

    public function setId($languageId) {
        $this->languageId = $languageId;
    }
    
    public function getLanguageId() {
        return $this->languageId;
    }
    
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getIsocode() {
        return $this->isocode;
    }
    
    public function setIsocode($isocode) {
        $this->isocode = $isocode;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getDateAdd() {
        return $this->dateAdd;
    }
    
    public function setDateAdd($date) {
        $this->dateAdd = $date;
    }
    
    public function getDateUpdate() {
        return $this->dateUpdate;
    }
    
    public function setDateUpdate($date) {
        $this->dateUpdate = $date;
    }

    /*public function getInputFilter() {
        if (!isset($this->_inputFilter)) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'language_id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        )
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'isocode',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 2,
                                ),
                            ))
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'status',
                        'required' => true,
            )));

            $this->_inputFilter = $inputFilter;
        }

        return $this->_inputFilter;
    }*/

}