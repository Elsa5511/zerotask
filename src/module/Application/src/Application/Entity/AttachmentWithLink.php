<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class AttachmentWithLink extends Attachment {
    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=1024, nullable=true)
     */
    protected $link;

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

    public function getExtension() {
        $extension = parent::getExtension();
        if(empty($extension))
            $extension = 'url';
        return $extension;
    }
}
