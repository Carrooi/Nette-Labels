<?php

namespace CarrooiTests\LabelsApp\Model\Facades;

use CarrooiTests\LabelsApp\Model\Entities\Mail;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
class Mails extends Object
{


	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;


	/**
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->dao = $em->getRepository(Mail::getClassName());
	}


	/**
	 * @return \CarrooiTests\LabelsApp\Model\Entities\Mail
	 */
	public function create()
	{
		$mail = new Mail;

		$this->dao->getEntityManager()->persist($mail)->flush();

		return $mail;
	}

}
