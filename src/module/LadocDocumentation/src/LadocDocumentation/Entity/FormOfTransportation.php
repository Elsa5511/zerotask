<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\IdAndNameUnit;

/**
 * @ORM\Entity
 * @ORM\Table(name="form_of_transportation")
 */
class FormOfTransportation extends IdAndNameUnit {
}