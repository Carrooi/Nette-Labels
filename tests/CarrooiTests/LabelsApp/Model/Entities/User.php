<?php

namespace CarrooiTests\LabelsApp\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelOwner;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class User extends BaseEntity implements ILabelOwner
{


	use Identifier;

}
