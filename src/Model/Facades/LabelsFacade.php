<?php

namespace Carrooi\Labels\Model\Facades;

use Carrooi\Labels\InvalidArgumentException;
use Carrooi\Labels\Model\Entities\ILabelableEntity;
use Carrooi\Labels\Model\Entities\ILabelOwner;
use Carrooi\Labels\Model\Entities\Label;
use Carrooi\Labels\Model\Entities\LabelNamespace;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class LabelsFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $dao;

	/** @var \Carrooi\Labels\Model\Facades\LabelNamespacesFacade */
	private $namespacesFacade;

	/** @var \Carrooi\Labels\Model\Facades\LabelItemsFacade */
	private $itemsFacade;


	/**
	 * @param \Kdyby\Doctrine\EntityManager $em
	 * @param \Carrooi\Labels\Model\Facades\LabelNamespacesFacade $namespacesFacade
	 * @param \Carrooi\Labels\Model\Facades\LabelItemsFacade $itemsFacade
	 */
	public function __construct(EntityManager $em, LabelNamespacesFacade $namespacesFacade, LabelItemsFacade $itemsFacade)
	{
		$this->em = $em;
		$this->namespacesFacade = $namespacesFacade;
		$this->itemsFacade = $itemsFacade;

		$this->dao = $em->getRepository(Label::getClassName());
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @param \Carrooi\Labels\Model\Entities\ILabelOwner $owner
	 * @return \Carrooi\Labels\Model\Entities\Label[]
	 */
	public function getLabels(LabelNamespace $namespace, ILabelOwner $owner)
	{
		return $this->dao->findBy([
			'namespace' => $namespace,
			'owner' => $owner,
		]);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @param \Carrooi\Labels\Model\Entities\ILabelOwner $owner
	 * @param string $title
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\Label
	 */
	public function addLabel(LabelNamespace $namespace, ILabelOwner $owner, $title, $name = null)
	{
		$label = new Label;
		$label->setNamespace($namespace);
		$label->setOwner($owner);
		$label->setTitle($title);

		if ($name !== null) {
			$label->setName($name);
		}

		$this->em->persist($label)->flush();

		return $label;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @param \Carrooi\Labels\Model\Entities\ILabelOwner $owner
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\Label
	 */
	public function findLabel(LabelNamespace $namespace, ILabelOwner $owner, $name)
	{
		return $this->dao->findOneBy([
			'namespace' => $namespace,
			'owner' => $owner,
			'name' => $name,
		]);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @param string $title
	 * @param string $name
	 * @return $this
	 */
	public function renameLabel(Label $label, $title = null, $name = null)
	{
		if ($title === null && $name === null) {
			throw new InvalidArgumentException(__METHOD__. ': Please set either new name or title.');
		}

		if ($title !== null) {
			$label->setTitle($title);
		}

		if ($name !== null) {
			$label->setName($name);
		}

		$this->em->flush();

		return $this;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return $this
	 */
	public function removeLabel(Label $label)
	{
		$this->em->remove($label)->flush();

		return $this;
	}


	/*
	 * 		NAMESPACES SHORTCUTS
	 */


	/**
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace[]
	 */
	public function getNamespaces()
	{
		return $this->namespacesFacade->getNamespaces();
	}


	/**
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	public function findNamespace($name)
	{
		return $this->namespacesFacade->findNamespace($name);
	}


	/**
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	public function addNamespace($name)
	{
		return $this->namespacesFacade->addNamespace($name);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @param string $name
	 * @return $this
	 */
	public function renameNamespace(LabelNamespace $namespace, $name)
	{
		$this->namespacesFacade->renameNamespace($namespace, $name);
		return $this;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @return $this
	 */
	public function removeNamespace(LabelNamespace $namespace)
	{
		$this->namespacesFacade->removeNamespace($namespace);
		return $this;
	}


	/*
	 * 		LABEL ITEMS SHORTCUTS
	 */


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem
	 */
	public function addItemToLabel(ILabelableEntity $item, Label $label)
	{
		return $this->itemsFacade->addItemToLabel($item, $label);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem
	 */
	public function findItem(ILabelableEntity $item, Label $label)
	{
		return $this->itemsFacade->findItem($item, $label);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return bool
	 */
	public function isItemInLabel(ILabelableEntity $item, Label $label)
	{
		return $this->itemsFacade->isItemInLabel($item, $label);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem[]
	 */
	public function getItems(Label $label)
	{
		return $this->itemsFacade->getItems($label);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @param string $className
	 * @return \Carrooi\Labels\Model\Entities\ILabelableEntity[]|\Kdyby\Doctrine\ResultSet
	 */
	public function getItemsByType(Label $label, $className)
	{
		return $this->itemsFacade->getItemsByType($label, $className);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return $this
	 */
	public function removeItemFromLabel(ILabelableEntity $item, Label $label)
	{
		$this->itemsFacade->removeItemFromLabel($item, $label);
		return $this;
	}

}
