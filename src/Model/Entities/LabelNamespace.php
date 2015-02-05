<?php

namespace Carrooi\Labels\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class LabelNamespace extends BaseEntity
{


	use Identifier;


	/**
	 * @ORM\Column(type="string", length=20, unique=true, nullable=false)
	 * @var string
	 */
	private $name;


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

}
