<?php
namespace Equipment\Form;

use Sysco\Aurora\Form\Form;

class CheckoutForm extends Form
{
    public function __construct()
    {
        parent::__construct('checkout');
    }

    public function addingHiddenElements($post)
    {
        if(is_array($post->idList)) {
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
        }

        $this->add(
                array(
                    'name' => 'equipmentId',
                    'type' => 'hidden',
                    'attributes' => array(
                        'value' => $post->equipmentId
                    )
        ));
    }
}

