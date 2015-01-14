<?php

namespace Carrooi\Doctrine;

use Carrooi\Doctrine\Entities\BaseEntity;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityDao;
use Nette\Utils\Strings;
use Exception;

class RuntimeException extends \RuntimeException {}

class LogicException extends \LogicException {}

class NotImplementedException extends LogicException {}

class InvalidArgumentException extends LogicException {}

class InvalidStateException extends RuntimeException {}

class DuplicateEntryException extends RuntimeException
{


	/** @var \Carrooi\Doctrine\Entities\BaseEntity  */
	private $entity;

	/** @var string */
	private $column;

	/** @var mixed */
	private $value;


	/**
	 * @param \Exception $e
	 * @param \Carrooi\Doctrine\Entities\BaseEntity $entity
	 * @param string $column
	 * @param mixed $value
	 */
	public function __construct(Exception $e, BaseEntity $entity, $column, $value)
	{
		$this->entity = $entity;
		$this->column = $column;
		$this->value = $value;

		parent::__construct($e->getMessage(), $e->getCode(), $e);
	}


	/**
	 * @param \Doctrine\DBAL\Exception\UniqueConstraintViolationException $e
	 * @param \Kdyby\Doctrine\EntityDao $dao
	 * @param \Carrooi\Doctrine\Entities\BaseEntity $entity
	 * @return static
	 */
	public static function createFromDuplicateEntryException(UniqueConstraintViolationException $e, EntityDao $dao, BaseEntity $entity)
	{
		$match = Strings::match($e->getMessage(), '/DETAIL:\s+Key\s\(([a-z_]+)\)/');

		$column = $dao->getClassMetadata()->getColumnName($match[1]);
		$value = $dao->getClassMetadata()->getFieldValue($entity, $match[1]);

		return new static($e, $entity, $column, $value);
	}


	/**
	 * @return \Carrooi\Doctrine\Entities\BaseEntity
	 */
	public function getEntity()
	{
		return $this->entity;
	}


	/**
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
	}


	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}
