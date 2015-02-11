<?php

namespace Carrooi\Labels\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @author David Kudera
 */
trait TLabelItem
{


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