<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("feature")
 */
class Feature {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $key;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue() {
        $map = array(
            'ladoc-documentation' => 'Ladoc Documentation',
            'training' => 'Training',
            'exercise' => 'Exercises',
            'exam' => 'Exams',
            'documentation' => 'Documentation',
            'certification' => 'Certification',
            'instances' => 'Instances',
            'attachments' => 'Attachments',
            'best_practice' => 'Best practices'
        );
        return $map[$this->key];
    }


    function __toString() {
        return $this->getValue();
    }


}
