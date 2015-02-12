<?php

/**
 * Test: Carrooi\Labels\Model\Facades\LabelItemsFacade
 *
 * @testCase CarrooiTests\Labels\Model\Facades\LabelItemsFacadeTest
 * @author David Kudera
 */

namespace CarrooiTests\Labels\Model\Facades;

use Carrooi\Labels\Model\Entities\ILabelableEntity;
use Carrooi\Labels\Model\Entities\TLabelable;
use CarrooiTests\Labels\TestCase;
use CarrooiTests\LabelsApp\Model\Entities\Article;
use CarrooiTests\LabelsApp\Model\Entities\Mail;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class LabelItemsFacadeTest extends TestCase
{


	/** @var \Carrooi\Labels\Model\Facades\LabelNamespacesFacade */
	private $namespaces;

	/** @var \Carrooi\Labels\Model\Facades\LabelsFacade */
	private $labels;

	/** @var \Carrooi\Labels\Model\Facades\LabelItemsFacade */
	private $items;

	/** @var \CarrooiTests\LabelsApp\Model\Facades\Users */
	private $users;

	/** @var \CarrooiTests\LabelsApp\Model\Facades\Mails */
	private $mails;

	/** @var \CarrooiTests\LabelsApp\Model\Facades\Articles */
	private $articles;


	public function setUp()
	{
		$container = $this->createContainer();

		$this->namespaces = $container->getByType('Carrooi\Labels\Model\Facades\LabelNamespacesFacade');
		$this->labels = $container->getByType('Carrooi\Labels\Model\Facades\LabelsFacade');
		$this->items = $container->getByType('Carrooi\Labels\Model\Facades\LabelItemsFacade');
		$this->users = $container->getByType('CarrooiTests\LabelsApp\Model\Facades\Users');
		$this->mails = $container->getByType('CarrooiTests\LabelsApp\Model\Facades\Mails');
		$this->articles = $container->getByType('CarrooiTests\LabelsApp\Model\Facades\Articles');
	}


	public function tearDown()
	{
		$this->namespaces = $this->labels = $this->users = null;
	}


	public function testAddItemToLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		$item = $this->items->addItemToLabel($mail, $label);		/** @var \CarrooiTests\LabelsApp\Model\Entities\LabelItem $item */

		Assert::notSame(null, $item);
		Assert::type('Carrooi\Labels\Model\Entities\ILabelItem', $item);
		Assert::notSame(null, $item->getId());
		Assert::true($item->hasMail());
		Assert::same($mail->getId(), $item->getMail()->getId());
	}


	public function testAddItemToLabel_notRegisteredLabelableEntity()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$book = new Book;

		Assert::exception(function() use ($book, $label) {
			$this->items->addItemToLabel($book, $label);
		}, 'Carrooi\Labels\InvalidArgumentException', 'Please register entity CarrooiTests\Labels\Model\Facades\Book in your labels configuration.');
	}


	public function testAddItemToLabel_alreadyAdded()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		$this->items->addItemToLabel($mail, $label);

		Assert::exception(function() use ($mail, $label) {
			$this->items->addItemToLabel($mail, $label);
		}, 'Carrooi\Labels\InvalidArgumentException', 'Item CarrooiTests\LabelsApp\Model\Entities\Mail('. $mail->getId(). ') is already in label '. $label->getId(). '.');
	}


	public function testFindItem()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		Assert::null($this->items->findItem($mail, $label));

		$item = $this->items->addItemToLabel($mail, $label);
		$found = $this->items->findItem($mail, $label);

		Assert::notSame(null, $item);
		Assert::same($item->getId(), $found->getId());
	}


	public function testIsItemInLabel_not()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		Assert::false($this->items->isItemInLabel($mail, $label));
	}


	public function testIsItemInLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		$this->items->addItemToLabel($mail, $label);

		Assert::true($this->items->isItemInLabel($mail, $label));
	}


	public function testGetItems()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');

		$this->items->addItemToLabel($this->mails->create(), $label);
		$this->items->addItemToLabel($this->mails->create(), $label);
		$this->items->addItemToLabel($this->mails->create(), $label);

		$this->items->addItemToLabel($this->articles->create(), $label);
		$this->items->addItemToLabel($this->articles->create(), $label);

		$this->items->addItemToLabel($this->mails->create(), $this->labels->addLabel($namespace, $user, 'Test'));

		$items = $this->items->getItems($label);

		Assert::count(5, $items);
	}


	public function testGetItemsByType_notRegisteredLabelableEntity()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');

		Assert::exception(function() use ($label) {
			$this->items->getItemsByType($label, 'Nette\Object');
		}, 'Carrooi\Labels\InvalidArgumentException', 'Please register entity Nette\Object in your labels configuration.');
	}


	public function testGetItemsByType()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');

		$this->items->addItemToLabel($this->mails->create(), $label);
		$this->items->addItemToLabel($this->mails->create(), $label);
		$this->items->addItemToLabel($this->mails->create(), $label);

		$this->items->addItemToLabel($this->articles->create(), $label);
		$this->items->addItemToLabel($this->articles->create(), $label);

		$mails = $this->items->getItemsByType($label, Mail::getClassName())->toArray();

		Assert::count(3, $mails);
		Assert::type(Mail::getClassName(), $mails[0]);
		Assert::type(Mail::getClassName(), $mails[1]);
		Assert::type(Mail::getClassName(), $mails[2]);

		$articles = $this->items->getItemsByType($label, Article::getClassName())->toArray();

		Assert::count(2, $articles);
		Assert::type(Article::getClassName(), $articles[0]);
		Assert::type(Article::getClassName(), $articles[1]);
	}


	public function testRemoveItemFromLabel_notInLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		Assert::exception(function() use ($mail, $label) {
			$this->items->removeItemFromLabel($label, $mail);
		}, 'Carrooi\Labels\InvalidArgumentException', 'Item CarrooiTests\LabelsApp\Model\Entities\Mail('. $mail->getId(). ') is not in label '. $label->getId(). '.');
	}


	public function testRemoveItemFromLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();
		$label = $this->labels->addLabel($namespace, $user, 'Banking');
		$mail = $this->mails->create();

		$this->items->addItemToLabel($mail, $label);

		Assert::true($this->items->isItemInLabel($mail, $label));

		$this->items->removeItemFromLabel($label, $mail);

		Assert::false($this->items->isItemInLabel($mail, $label));
	}

}


/**
 *
 * @author David Kudera
 */
class Book implements ILabelableEntity
{

	use Identifier;

	use TLabelable;

}


run(new LabelItemsFacadeTest);
