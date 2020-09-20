<?php
namespace BestPractice\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Attachment;

/**
 * Best practice attachment
 *
 * @ORM\Table(name="best_practice_attachment")
 * @ORM\Entity
 */
class BestPracticeAttachment extends Attachment
{
   
    /**
     * @var \BestPractice\Entity\BestPractice
     *
     * @ORM\ManyToOne(targetEntity="BestPractice\Entity\BestPractice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="best_practice_id", referencedColumnName="best_practice_id")
     * })
     */
    private $bestPractice;


    /**
     *
     * @param \BestPractice\Entity\BestPractice $bestPractice
     */
    public function setBestPractice(\BestPractice\Entity\BestPractice $bestPractice = null)
    {
        $this->bestPractice = $bestPractice;
    
        return $this;
    }

    /**
     *
     * @return \BestPractice\Entity\BestPractice
     */
    public function getBestPractice()
    {
        return $this->bestPractice;
    }
    
    public function __clone() {
        if ($this->attachmentId !== null) {
            $this->attachmentId = null;
            $this->bestPractice = null;
        }
    }
}
