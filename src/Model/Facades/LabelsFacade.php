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

}
