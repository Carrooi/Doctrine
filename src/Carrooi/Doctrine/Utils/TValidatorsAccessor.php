<?php

namespace Carrooi\Doctrine\Utils;

/**
 *
 * @author David Kudera
 */
trait TValidatorsAccessor
{


	/** @var \Carrooi\Doctrine\Utils\Validators */
	private $validators;


	/**
	 * @return \Carrooi\Doctrine\Utils\Validators
	 */
	protected function getValidators()
	{
		if (!$this->validators) {
			$this->validators = new Validators;
		}

		return $this->validators;
	}


	/**
	 * @param \Carrooi\Doctrine\Utils\Validators $validators
	 * @return $this
	 */
	protected function setValidators(Validators $validators)
	{
		$this->validators = $validators;
		return $this;
	}

}
