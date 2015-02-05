<?php

namespace Carrooi\Labels\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @author David Kudera
 */
trait TLabelable
{


	/** @var \Doctrine\Common\Collections\ArrayCollection */
	private $labelItems;


	private function initLabelItems()
	{
		if (!$this->labelItems) {
			$this->labelItems = new ArrayCollection;
		}
	}


	/**
	 * @return \Carrooi\Labels\Model\Entities\ILabelItem[]
	 */
	public function getLabelItems()
	{
		$this->initLabelItems();
		return $this->labelItems->toArray();
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelItem $labelItem
	 * @return $this
	 */
	public function addLabelItem(ILabelItem $labelItem)
	{
		$this->initLabelItems();
		$this->labelItems->add($labelItem);
		return $this;
	}


	/**
	 * @param \Carrooi\Labels\Model\Entities\ILabelItem $labelItem
	 * @return $this
	 */
	public function removeLabelItem(ILabelItem $labelItem)
	{
		$this->initLabelItems();
		$this->labelItems->removeElement($labelItem);
		return $this;
	}

}
