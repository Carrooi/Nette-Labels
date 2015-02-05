<?php

namespace CarrooiTests\LabelsApp\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelableEntity;
use Carrooi\Labels\Model\Entities\TLabelable;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class Mail extends BaseEntity implements ILabelableEntity
{


	use Identifier;

	use TLabelable;

}
