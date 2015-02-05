<?php

/**
 * Test: Carrooi\Labels\Model\Facades\LabelNamespacesFacade
 *
 * @testCase CarrooiTests\Labels\Model\Facades\LabelNamespacesFacadeTest
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
class LabelNamespacesFacadeTest extends TestCase
{



	/** @var \Carrooi\Labels\Model\Facades\LabelNamespacesFacade */
	private $namespaces;


	public function setUp()
	{
		$container = $this->createContainer();

		$this->namespaces = $container->getByType('Carrooi\Labels\Model\Facades\LabelNamespacesFacade');
	}


	public function tearDown()
	{
		$this->namespaces = null;
	}


	public function testAddNamespace()
	{
		$namespace = $this->namespaces->addNamespace('test');

		Assert::type('Carrooi\Labels\Model\Entities\LabelNamespace', $namespace);
		Assert::notSame(null, $namespace->getId());
	}


	public function testAddNamespace_unique()
	{
		$this->namespaces->addNamespace('test');

		Assert::exception(function() {
			$this->namespaces->addNamespace('test');
		}, 'Carrooi\Labels\DuplicateLabelNamespace', 'Label namespace test already exists.');
	}


	public function testFindNamespace()
	{
		$namespace = $this->namespaces->addNamespace('test');

		$found = $this->namespaces->findNamespace('test');

		Assert::type('Carrooi\Labels\Model\Entities\LabelNamespace', $namespace);
		Assert::same($namespace->getId(), $found->getId());
	}


	public function testFindNamespace_notExists()
	{
		Assert::null($this->namespaces->findNamespace('test'));
	}


	public function testGetNamespaces()
	{
		$this->namespaces->addNamespace('test1');
		$this->namespaces->addNamespace('test2');
		$this->namespaces->addNamespace('test3');

		$namespaces = $this->namespaces->getNamespaces();

		Assert::count(3, $namespaces);
	}


	public function testRenameNamespace()
	{
		$namespace = $this->namespaces->addNamespace('test');

		$this->namespaces->renameNamespace($namespace, 'super_test');

		Assert::null($this->namespaces->findNamespace('test'));

		$namespace = $this->namespaces->findNamespace('super_test');

		Assert::notSame(null, $namespace);
		Assert::same('super_test', $namespace->getName());
	}


	public function testRenameNamespace_unique()
	{
		$this->namespaces->addNamespace('super_test');
		$namespace = $this->namespaces->addNamespace('test');

		Assert::exception(function() use ($namespace) {
			$this->namespaces->renameNamespace($namespace, 'super_test');
		}, 'Carrooi\Labels\DuplicateLabelNamespace', 'Label namespace super_test already exists.');
	}


	public function testRemoveNamespace()
	{
		$namespace = $this->namespaces->addNamespace('test');

		$this->namespaces->removeNamespace($namespace);

		Assert::null($this->namespaces->findNamespace('test'));
	}

}


run(new LabelNamespacesFacadeTest);
