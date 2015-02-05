<?php

/**
 * Test: Carrooi\Labels\Model\Facades\LabelsFacade
 *
 * @testCase CarrooiTests\Labels\Model\Facades\LabelsFacadeTest
 * @author David Kudera
 */

namespace CarrooiTests\Labels\Model\Facades;

use CarrooiTests\Labels\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class LabelsFacadeTest extends TestCase
{



	/** @var \Carrooi\Labels\Model\Facades\LabelNamespacesFacade */
	private $namespaces;

	/** @var \Carrooi\Labels\Model\Facades\LabelsFacade */
	private $labels;

	/** @var \CarrooiTests\LabelsApp\Model\Facades\Users */
	private $users;


	public function setUp()
	{
		$container = $this->createContainer();

		$this->namespaces = $container->getByType('Carrooi\Labels\Model\Facades\LabelNamespacesFacade');
		$this->labels = $container->getByType('Carrooi\Labels\Model\Facades\LabelsFacade');
		$this->users = $container->getByType('CarrooiTests\LabelsApp\Model\Facades\Users');
	}


	public function tearDown()
	{
		$this->namespaces = $this->labels = $this->users = null;
	}


	public function testAddLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();

		$label = $this->labels->addLabel($namespace, $user, 'Bug', 'bug');

		Assert::type('Carrooi\Labels\Model\Entities\Label', $label);
		Assert::notSame(null, $label->getId());
		Assert::same('Bug', $label->getTitle());
		Assert::same('bug', $label->getName());
	}


	public function testFindLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();

		$label = $this->labels->addLabel($namespace, $user, 'Bug', 'bug');

		$found = $this->labels->findLabel($namespace, $user, 'bug');

		Assert::notSame(null, $found);
		Assert::same($label->getId(), $found->getId());
	}


	public function testRenameLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();

		$label = $this->labels->addLabel($namespace, $user, 'Bug');

		Assert::null($label->getName());

		$this->labels->renameLabel($label, 'Super bug', 'super_bug');

		$found = $this->labels->findLabel($namespace, $user, 'super_bug');

		Assert::notSame(null, $found);
		Assert::same($label->getId(), $found->getId());
		Assert::same('Super bug', $found->getTitle());
		Assert::same('super_bug', $found->getName());
	}


	public function testGetLabels()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();

		$this->labels->addLabel($namespace, $user, 'Test');
		$this->labels->addLabel($namespace, $user, 'Test');
		$this->labels->addLabel($namespace, $user, 'Test');

		$this->labels->addLabel($namespace, $this->users->create(), 'Test');

		$this->labels->addLabel($this->namespaces->addNamespace('test2'), $this->users->create(), 'Test');

		$labels = $this->labels->getLabels($namespace, $user);

		Assert::count(3, $labels);
	}


	public function testRemoveLabel()
	{
		$namespace = $this->namespaces->addNamespace('test');
		$user = $this->users->create();

		$label = $this->labels->addLabel($namespace, $user, 'Test', 'test');

		$this->labels->removeLabel($label);

		Assert::null($this->labels->findLabel($namespace, $user, 'test'));
	}

}


run(new LabelsFacadeTest);
