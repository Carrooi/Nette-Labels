<?php

namespace Carrooi\Labels\DI;

use Carrooi\Labels\ConfigurationException;
use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Doctrine\DI\ITargetEntityProvider;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;

/**
 *
 * @author David Kudera
 */
class LabelsExtension extends CompilerExtension implements IEntityProvider, ITargetEntityProvider
{


	/** @var array */
	private $defaults = [
		'ownerClass' => null,
		'labelItemClass' => null,
		'entities' => [],
	];


	/** @var string */
	private $ownerClass;

	/** @var string */
	private $labelItemClass;


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		if (!$config['ownerClass']) {
			throw new ConfigurationException('Please, define your owner entity class at labels configuration.');
		}

		if (!$config['labelItemClass']) {
			throw new ConfigurationException('Please, define your label entity class at labels configuration.');
		}

		if (!class_exists($config['ownerClass'])) {
			throw new ConfigurationException('Class '. $config['ownerClass']. ' does not exists or is not instantiable.');
		}

		if (!class_exists($config['labelItemClass'])) {
			throw new ConfigurationException('Class '. $config['labelItemClass']. ' does not exists or is not instantiable.');
		}

		$this->ownerClass = $config['ownerClass'];
		$this->labelItemClass = $config['labelItemClass'];

		$builder->addDefinition($this->prefix('facade.namespaces'))
			->setClass('Carrooi\Labels\Model\Facades\LabelNamespacesFacade');

		$builder->addDefinition($this->prefix('facade.items'))
			->setClass('Carrooi\Labels\Model\Facades\LabelItemsFacade')
			->setArguments([$this->labelItemClass]);

		$builder->addDefinition($this->prefix('facade.labels'))
			->setClass('Carrooi\Labels\Model\Facades\LabelsFacade');

		$associations = $builder->addDefinition($this->prefix('facade.associations'))
			->setClass('Carrooi\Labels\Model\Facades\AssociationsManager');

		$builder->addDefinition($this->prefix('event.relations'))
			->setClass('Carrooi\Labels\Model\Events\LabelsRelationsSubscriber')
			->setArguments([$this->labelItemClass])
			->addTag(EventsExtension::TAG_SUBSCRIBER);

		foreach ($config['entities'] as $class => $field) {
			$associations->addSetup('addAssociation', [$class, $field]);
		}
	}


	/**
	 * @return array
	 */
	function getEntityMappings()
	{
		return [
			'Carrooi\Labels\Model\Entities' => __DIR__. '/../Model/Entities',
		];
	}


	/**
	 * @return array
	 */
	function getTargetEntityMappings()
	{
		return [
			'Carrooi\Labels\Model\Entities\ILabelOwner' => $this->ownerClass,
			'Carrooi\Labels\Model\Entities\ILabelItem' => $this->labelItemClass,
		];
	}

}
