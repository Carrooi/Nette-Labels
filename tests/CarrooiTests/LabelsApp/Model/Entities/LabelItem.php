<?php

namespace CarrooiTests\LabelsApp\Model\Entities;

use Carrooi\Labels\Model\Entities\ILabelItem;
use Carrooi\Labels\Model\Entities\TLabelItem;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 *
 * @ORM\Entity
 *
 * @author David Kudera
 */
class LabelItem extends BaseEntity implements ILabelItem
{


	use Identifier;

	use TLabelItem;


	/**
	 * @ORM\ManyToOne(targetEntity="\CarrooiTests\LabelsApp\Model\Entities\Mail")
	 * @var \CarrooiTests\LabelsApp\Model\Entities\Mail
	 */
	private $mail;


	/**
	 * @ORM\ManyToOne(targetEntity="\CarrooiTests\LabelsApp\Model\Entities\Article")
	 * @var \CarrooiTests\LabelsApp\Model\Entities\Article
	 */
	private $article;


	/**
	 * @return bool
	 */
	public function hasMail()
	{
		return $this->mail !== null;
	}


	/**
	 * @return \CarrooiTests\LabelsApp\Model\Entities\Mail
	 */
	public function getMail()
	{
		return $this->mail;
	}


	/**
	 * @param \CarrooiTests\LabelsApp\Model\Entities\Mail $mail
	 * @return $this
	 */
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function hasArticle()
	{
		return $this->article !== null;
	}


	/**
	 * @return \CarrooiTests\LabelsApp\Model\Entities\Article
	 */
	public function getArticle()
	{
		return $this->article;
	}


	/**
	 * @param \CarrooiTests\LabelsApp\Model\Entities\Article $article
	 * @return $this
	 */
	public function setArticle(Article $article)
	{
		$this->article = $article;
		return $this;
	}

}
