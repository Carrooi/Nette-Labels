<?php

namespace CarrooiTests\LabelsApp\Model\Facades;

use CarrooiTests\LabelsApp\Model\Entities\LabelOwner;
use CarrooiTests\LabelsApp\Model\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class Users extends Object
{


	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;


	/**
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->dao = $em->getRepository(User::getClassName());
	}


	/**
	 * @return \CarrooiTests\LabelsApp\Model\Entities\User
	 */
	public function create()
	{
		$user = new User;

		$this->dao->getEntityManager()->persist($user)->flush();

		return $user;
	}

}
