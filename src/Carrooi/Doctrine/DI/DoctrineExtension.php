<?php

namespace Carrooi\Doctrine\DI;

use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\DI\CompilerExtension;

/**
 *
 * @author David Kudera
 */
class DoctrineExtension extends CompilerExtension implements IEntityProvider
{


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('checker'))
			->setClass('Carrooi\Doctrine\DI\RegistrationChecker');
	}


	/**
	 * @return array
	 */
	function getEntityMappings()
	{
		return [
			'Carrooi\\Doctrine\\Entities' => __DIR__. '/../Entities',
		];
	}

}
