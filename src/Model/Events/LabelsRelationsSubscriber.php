<?php

namespace Carrooi\Labels\Model\Events;

use Carrooi\Labels\Model\Facades\AssociationsManager;
use Carrooi\Labels\RelationsException;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Nette\Object;
use Kdyby\Events\Subscriber;

/**
 *
 * @author David Kudera
 */
class LabelsRelationsSubscriber extends Object implements Subscriber
{


	const ASSOCIATION_FIELD_NAME = 'labelItems';


	/** @var \Carrooi\Labels\Model\Facades\AssociationsManager */
	private $associations;

	/** @var string */
	private $labelItemClass;


	/**
	 * @param string $labelItemClass
	 * @param \Carrooi\Labels\Model\Facades\AssociationsManager $associations
	 */
	public function __construct($labelItemClass, AssociationsManager $associations)
	{
		$this->associations = $associations;
		$this->labelItemClass = $labelItemClass;
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::loadClassMetadata => 'loadClassMetadata',
		];
	}


	/**
	 * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
	{
		$metadata = $eventArgs->getClassMetadata();				/** @var \Kdyby\Doctrine\Mapping\ClassMetadata $metadata */
		$class = $metadata->getName();

		if ($class === $this->labelItemClass) {
			foreach ($this->associations->getAssociations() as $assocClass => $field) {
				if (!$metadata->hasAssociation($field)) {
					throw new RelationsException('Missing manyToOne association at '. $class. '::$'. $field. '.');
				}

				$metadata->setAssociationOverride($field, [
					'type' => ClassMetadataInfo::MANY_TO_ONE,
					'targetEntity' => $assocClass,
					'fieldName' => $field,
					'inversedBy' => self::ASSOCIATION_FIELD_NAME,
					'joinColumn' => [
						'nullable' => false,
					],
				]);
			}

		} elseif (in_array('Carrooi\Labels\Model\Entities\ILabelableEntity', class_implements($class)) && $this->associations->hasAssociation($class)) {
			$metadata->mapOneToMany([
				'targetEntity' => $this->labelItemClass,
				'fieldName' => self::ASSOCIATION_FIELD_NAME,
				'mappedBy' => $this->associations->getAssociation($class),
			]);

		}
	}

}
