<?php
namespace Equipment\Form;

use Sysco\Aurora\Form\Form;

class PeriodicControlForm extends Form
{
    public function __construct()
    {
        parent::__construct('periodic-control');
    }

    public function bindControlPointResults($post, $sizeCollection)
    {
        $periodicControl = $this->getObject();
        $collection = $periodicControl->getControlPointResultCollection();
        for ($counter = 1; $counter <= $sizeCollection; $counter++) {
            $pointResultData = $post->get('control-point-result-' . $counter);
            $fieldset = $this->get('control-point-result-' . $counter);
            $fieldset->bindValues($pointResultData);
            $collection->add($fieldset->getObject());
        }
    }

    public function bindPeriodicControlAttachments($post)
    {
        $periodicControl = $this->getObject();
        $attachments = $periodicControl->getPeriodicControlAttachments();
        $attachmentFieldsets = $this->get('periodicControlAttachments');
        if($attachmentFieldsets && $attachmentFieldsets->count() > 0) {
            foreach ($attachmentFieldsets as $k => $attachmentFielset) {
                $dataArray = $post->get('periodicControlAttachments');
                $attachmentFielset->bindValues($dataArray[$k]);
                $attachments->add($attachmentFielset->getObject());
            }
        }
    }

    public function addingHiddenElements($post)
    {
        foreach ($post->idList as $i => $value) {
            $this->add(
                    array(
                        'name' => "idList[$i]",
                        'type' => 'hidden',
                        'attributes' => array(
                            'value' => $value
                        )
            ));
        }

        $this->add(
                array(
                    'name' => 'equipmentId',
                    'type' => 'hidden',
                    'attributes' => array(
                        'value' => $post->equipmentId
                    )
        ));

        $this->add(
                array(
                    'name' => 'equipmentIntervalDays',
                    'type' => 'hidden',
                    'attributes' => array(
                        'value' => $post->equipmentIntervalDays
                    )
        ));
    }

    public function addAttachmentButtons()
    {
        $this->add(
                array(
                    'name' => 'add',
                    'type' => 'submit',
                    'attributes' => array(
                        'id' => 'add-attachment-btn',
                        'value' => $this->translate('Add attachment')
                    )
        ));

        $this->add(
                array(
                    'name' => 'remove',
                    'type' => 'submit',
                    'attributes' => array(
                        'id' => 'remove-attachment-btn',
                        'value' => $this->translate('Remove attachment'),
                    )
        ));
    }

    public function addSubmitButton()
    {
        $this->add(
                array(
                    'name' => 'submit',
                    'type' => 'submit',
                    'attributes' => array(
                        'value' => $this->translate('Save changes')
                    )
        ));
    }
}

