<?php

namespace Carrooi\Labels\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\MappedSuperclass
 *
 * @author David Kudera
 */
abstract class BaseLabelItem extends BaseEntity implements ILabelItem
{


	use Identifier;


	/**
	 * @ORM\ManyToOne(targetEntity="\Carrooi\Labels\Model\Entities\Label")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \Carrooi\Labels\Model\Entities\Label
	 */
	private $label;


	/**
	 * @return \Carrooi\Labels\Model\Entities\Label
	 */
	public function getLabel()
	{
		return $this->label;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return $this
	 */
	public function setLabel(Label $label)
	{
		$this->label = $label;
		return $this;
	}

}
