<?php

namespace Carrooi\Labels\Model\Facades;

use Carrooi\Labels\InvalidArgumentException;
use Carrooi\Labels\Model\Entities\ILabelableEntity;
use Carrooi\Labels\Model\Entities\Label;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\ResultSet;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class LabelItemsFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $dao;

	/** @var \Carrooi\Labels\Model\Facades\AssociationsManager */
	private $associations;

	/** @var string */
	private $class;


	/**
	 * @param string $class
	 * @param \Kdyby\Doctrine\EntityManager $em
	 * @param \Carrooi\Labels\Model\Facades\AssociationsManager $associations
	 */
	public function __construct($class, EntityManager $em, AssociationsManager $associations)
	{
		$this->em = $em;
		$this->associations = $associations;
		$this->class = $class;

		$this->dao = $em->getRepository('Carrooi\Labels\Model\Entities\ILabelItem');
	}


	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}


	/**
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem
	 */
	public function createEntity()
	{
		$class = $this->getClass();
		return new $class;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem
	 */
	public function addItemToLabel(ILabelableEntity $item, Label $label)
	{
		$class = get_class($item);

		if (!$this->associations->hasAssociation($class)) {
			throw new InvalidArgumentException('Please register entity '. $class. ' in your labels configuration.');
		}

		if ($this->isItemInLabel($item, $label)) {
			throw new InvalidArgumentException('Item '. $class. '('. $item->getId(). ') is already in label '. $label->getId(). '.');
		}

		$labelItem = $this->createEntity();
		$labelItem->setLabel($label);

		$field = $this->associations->getAssociation($class);
		$this->em->getClassMetadata($this->getClass())->setFieldValue($labelItem, $field, $item);

		$item->addLabelItem($labelItem);

		$this->em->persist([
			$labelItem, $item,
		])->flush();

		return $labelItem;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem
	 */
	public function findItem(ILabelableEntity $item, Label $label)
	{
		return $this->dao->findOneBy([
			$this->associations->getAssociation(get_class($item)) => $item,
			'label' => $label,
		]);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return bool
	 */
	public function isItemInLabel(ILabelableEntity $item, Label $label)
	{
		return $this->findItem($item, $label) !== null;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return \Kdyby\Doctrine\ResultSet|\Carrooi\Labels\Model\Entities\ILabelItem[]
	 */
	public function getItems(Label $label)
	{
		$query = $this->dao->createQueryBuilder('i')
			->andWhere('i.label = :label')->setParameter('label', $label)
			->getQuery();

		return new ResultSet($query);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @param string $className
	 * @return \Kdyby\Doctrine\ResultSet|\Carrooi\Labels\Model\Entities\ILabelableEntity[]
	 */
	public function getItemsByType(Label $label, $className)
	{
		if (!$this->associations->hasAssociation($className)) {
			throw new InvalidArgumentException('Please register entity '. $className. ' in your labels configuration.');
		}

		$query = $this->em->getRepository($className)->createQueryBuilder('i')
			->join('i.labelItems', 'li')
			->andWhere('li.label = :label')->setParameter('label', $label)
			->getQuery();

		return new ResultSet($query);
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelableEntity $item
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return $this
	 */
	public function removeItemFromLabel(ILabelableEntity $item, Label $label)
	{
		$class = get_class($item);

		if (!$this->isItemInLabel($item, $label)) {
			throw new InvalidArgumentException('Item '. $class. '('. $item->getId(). ') is not in label '. $label->getId(). '.');
		}

		$labelItem = $this->findItem($item, $label);
		$item->removeLabelItem($labelItem);

		$this->em->remove($labelItem);
		$this->em->persist($item);

		$this->em->flush();

		return $this;
	}

}
