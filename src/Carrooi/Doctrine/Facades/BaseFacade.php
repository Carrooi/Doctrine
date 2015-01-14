<?php

namespace Carrooi\Doctrine\Facades;

use Carrooi\Doctrine\DuplicateEntryException;
use Carrooi\Doctrine\Entities\BaseEntity;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

/**
 *
 * @author David Kudera
 */
abstract class BaseFacade extends Object
{


	/** @var \Kdyby\Doctrine\EntityDao  */
	private $dao;


	/**
	 * @param \Kdyby\Doctrine\EntityDao $dao
	 */
	public function __construct(EntityDao $dao)
	{
		$this->dao = $dao;
	}


	/**
	 * @return \Kdyby\Doctrine\EntityDao|null
	 */
	protected function getDao()
	{
		return $this->dao;
	}


	/**
	 * @param \Carrooi\Doctrine\Entities\BaseEntity $entity
	 * @return \Carrooi\Doctrine\Entities\BaseEntity
	 */
	public function save(BaseEntity $entity)
	{
		try {
			$this->dao->add($entity);
			$this->dao->getEntityManager()->flush();
		} catch (UniqueConstraintViolationException $e) {
			$e = DuplicateEntryException::createFromDuplicateEntryException($e, $this->getDao(), $entity);
			$this->processDuplicateEntryException($e);
		}

		return $entity;
	}


	/**
	 * @param \Carrooi\Doctrine\DuplicateEntryException $e
	 */
	protected function processDuplicateEntryException(DuplicateEntryException $e)
	{
		throw $e;
	}


	/**
	 * @param callable $callback
	 * @return bool|mixed
	 */
	protected function transactional($callback)
	{
		return $this->getDao()->transactional($callback);
	}

}
