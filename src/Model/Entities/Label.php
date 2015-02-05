<?php

namespace Carrooi\Labels\Model\Entities;

use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class Label extends BaseEntity
{


	use Identifier;


	/**
	 * @ORM\ManyToOne(targetEntity="\Carrooi\Labels\Model\Entities\LabelNamespace")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	private $namespace;


	/**
	 * @ORM\ManyToOne(targetEntity="\Carrooi\Labels\Model\Entities\ILabelOwner")
	 * @ORM\JoinColumn(nullable=false)
	 * @var \Carrooi\Labels\Model\Entities\ILabelOwner
	 */
	private $owner;


	/**
	 * @ORM\Column(type="string", length=30, nullable=false)
	 * @var string
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 * @var string
	 */
	private $name;


	/**
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @return $this
	 */
	public function setNamespace(LabelNamespace $namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}


	/**
	 * @return \Carrooi\Labels\Model\Entities\ILabelOwner
	 */
	public function getOwner()
	{
		return $this->owner;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelOwner $owner
	 * @return $this
	 */
	public function setOwner(ILabelOwner $owner)
	{
		$this->owner = $owner;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}


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
