<?php

namespace Carrooi\Doctrine\Entities;

use Carrooi\Doctrine\Utils\TValidatorsAccessor;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity as KdybyBaseEntity;

/**
 *
 * @ORM\MappedSuperclass
 *
 * @author David Kudera
 */
abstract class BaseEntity extends KdybyBaseEntity
{


	use Identifier;

	use TValidatorsAccessor;

}
