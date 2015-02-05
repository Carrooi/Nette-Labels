<?php

namespace Carrooi\Labels\Model\Facades;

use Carrooi\Labels\DuplicateLabelNamespace;
use Carrooi\Labels\Model\Entities\LabelNamespace;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class LabelNamespacesFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $dao;


	/**
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$this->dao = $em->getRepository(LabelNamespace::getClassName());
	}


	/**
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace[]
	 */
	public function getNamespaces()
	{
		return $this->dao->findAll();
	}


	/**
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	public function findNamespace($name)
	{
		return $this->dao->findOneBy([
			'name' => $name,
		]);
	}


	/**
	 * @param string $name
	 * @return \Carrooi\Labels\Model\Entities\LabelNamespace
	 */
	public function addNamespace($name)
	{
		$namespace = new LabelNamespace;
		$namespace->setName($name);

		$namespace = $this->em->safePersist($namespace);
		if (!$namespace) {
			throw new DuplicateLabelNamespace('Label namespace '. $name. ' already exists.');
		}

		$this->em->flush();

		return $namespace;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @param string $name
	 * @return $this
	 */
	public function renameNamespace(LabelNamespace $namespace, $name)
	{
		$namespace->setName($name);

		try {
			$this->em->persist($namespace)->flush();
		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateLabelNamespace('Label namespace '. $name. ' already exists.');
		}

		return $this;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\LabelNamespace $namespace
	 * @return $this
	 */
	public function removeNamespace(LabelNamespace $namespace)
	{
		$this->em->remove($namespace)->flush();

		return $this;
	}

}
