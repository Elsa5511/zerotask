<?php

namespace Application\Form;

use Sysco\Aurora\Form\Form;
use Application\Form\ForgotPasswordFieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ForgotPasswordForm extends Form
{

    public function __construct(ObjectManager $objectManager)
    {

        parent::__construct('user');

        $hydrator = new DoctrineHydrator($objectManager, 'Application\Entity\User', false);
        $this->setHydrator($hydrator);

        $forgotPasswordFieldset = new ForgotPasswordFieldset($objectManager);
        $forgotPasswordFieldset->setUseAsBaseFieldset(true);
        $this->add($forgotPasswordFieldset);
    }

}