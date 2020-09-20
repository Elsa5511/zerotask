<?php

namespace BestPractice\Form;

use Sysco\Aurora\Form\Form;

class BestPracticeForm extends Form
{

    public function __construct()
    {
        parent::__construct('best-practice');
    }

    public function verifyFilesValidation($slideOnePost)
    {
        $slideOneisNotEdited = is_array($slideOnePost) && empty($slideOnePost["tmp_name"]);
        if ($slideOneisNotEdited) {
            $inputFilterFieldset = $this->getInputFilter()->get("best-practice");
            $slideOneFilter = $inputFilterFieldset->get("slide-one");
            $slideOneFilter->setRequired(false);            
        }
    }
    
    public function createNewRevision($bestPractice, $comment)
    {
        $clonedBestPractice = clone $bestPractice;
        $this->bind($clonedBestPractice);
        $clonedBestPractice->setComment($comment);
        return $clonedBestPractice;
    }

}

