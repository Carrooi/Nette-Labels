<?php

namespace Carrooi\Labels\Model\Entities;

/**
 *
 * @author David Kudera
 */
interface ILabelableEntity
{


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem[]
	 */
	public function getLabelItems();


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelItem $labelItem
	 * @return $this
	 */
	public function addLabelItem(ILabelItem $labelItem);


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelItem $labelItem
	 * @return $this
	 */
	public function removeLabelItem(ILabelItem $labelItem);

}
