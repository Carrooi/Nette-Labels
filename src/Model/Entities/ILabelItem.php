<?php

namespace Carrooi\Labels\Model\Entities;

/**
 *
 * @author David Kudera
 */
interface ILabelItem
{


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @return \Carrooi\Labels\Model\Entities\Label
	 */
	public function getLabel();


	/**
	 * @param \Carrooi\Labels\Model\Entities\Label $label
	 * @return $this
	 */
	public function setLabel(Label $label);

}
