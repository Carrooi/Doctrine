<?php

namespace Carrooi\Doctrine\Queries;

use Carrooi\Doctrine\InvalidArgumentException;
use Doctrine\ORM\AbstractQuery;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;

/**
 *
 * @author David Kudera
 */
abstract class BaseQuery extends QueryObject
{


	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;

	/** @var callable[] */
	private $filters = [];

	/** @var callable[] */
	private $selectFilters = [];

	/** @var string[] */
	private $joins = [];

	/** @var array */
	private $selects = [];


	/**
	 * @param \Kdyby\Doctrine\EntityDao $dao
	 */
	public function __construct(EntityDao $dao)
	{
		$this->dao = $dao;
	}


	/**
	 * @param callable $modifier
	 * @return $this
	 */
	protected function addFilter(callable $modifier)
	{
		$this->filters[] = $modifier;
		return $this;
	}


	/**
	 * @param callable $modifier
	 * @return $this
	 */
	public function addSelectFilter(callable $modifier)
	{
		$this->selectFilters[] = $modifier;
		return $this;
	}


	/**
	 * @param \Kdyby\Doctrine\QueryBuilder $qb
	 * @return $this
	 */
	protected function applyModifiers(QueryBuilder $qb)
	{
		$this
			->applyFilters($qb)
			->applySelectFilters($qb);

		return $this;
	}


	/**
	 * @param \Kdyby\Doctrine\QueryBuilder $qb
	 * @return $this
	 */
	protected function applyFilters(QueryBuilder $qb)
	{
		if (count($this->joins) > 0) {
			foreach ($this->joins as $join) {
				$qb->{$join['type']}($join['join'], $join['alias'], $join['conditionType'], $join['condition']);
			}
		}

		foreach ($this->filters as $modifier) {
			$modifier($qb);
		}

		return $this;
	}


	/**
	 * @param \Kdyby\Doctrine\QueryBuilder $qb
	 * @return $this
	 */
	protected function applySelectFilters(QueryBuilder $qb)
	{
		if (count($this->selects) > 0) {
			$selects = $this->selects;
			$_selects = [];
			foreach ($selects as $alias => &$columns) {
				$columns = array_unique($columns);
				$columns = implode(',', $columns);
				$_selects[] = 'partial '. $alias. '.{'. $columns. '}';
			}

			$qb->select(implode(', ', $_selects));
		}

		foreach ($this->selectFilters as $modifier) {
			$modifier($qb);
		}

		return $this;
	}


	/**
	 * @param string $type
	 * @param string $join
	 * @param string $alias
	 * @param string $conditionType
	 * @param string $condition
	 * @throws \Carrooi\Doctrine\InvalidArgumentException
	 * @return $this
	 */
	private function _tryJoin($type, $join, $alias, $conditionType = null, $condition = null)
	{
		switch ($type) {
			case 'inner': $type = 'innerJoin'; break;
			case 'left': $type = 'leftJoin'; break;
			default:
				throw new InvalidArgumentException('Unknown join type '. $type);
				break;
		}

		$name = $join. '/'. $alias;

		if (array_key_exists($name, $this->joins)) {
			return $this;
		}

		$this->joins[$name] = [
			'type' => $type,
			'join' => $join,
			'alias' => $alias,
			'conditionType' => $conditionType,
			'condition' => $condition,
		];

		return $this;
	}


	/**
	 * @param string $join
	 * @param string $alias
	 * @param string $conditionType
	 * @param string $condition
	 * @return $this
	 */
	protected function tryJoin($join, $alias, $conditionType = null, $condition = null)
	{
		return $this->_tryJoin('inner', $join, $alias, $conditionType, $condition);
	}


	/**
	 * @param string $join
	 * @param string $alias
	 * @param string $conditionType
	 * @param string $condition
	 * @return $this
	 */
	protected function tryLeftJoin($join, $alias, $conditionType = null, $condition = null)
	{
		return $this->_tryJoin('left', $join, $alias, $conditionType, $condition);
	}


	/**
	 * @param string $alias
	 * @param array $columns
	 * @return $this
	 */
	public function trySelect($alias, array $columns)
	{
		if (!isset($this->selects[$alias])) {
			$this->selects[$alias] = ['id'];
		}

		$this->selects[$alias] = array_merge($this->selects[$alias], $columns);

		return $this;
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getQueryBuilder()
	{
		return $this->doCreateQuery($this->dao);
	}


	/**
	 * @return \Kdyby\Doctrine\ResultSet
	 */
	public function getResultSet()
	{
		return $this
			->dao
			->fetch($this);
	}


	/**
	 * @param int $hydrationMode
	 * @return \Carrooi\Doctrine\Entities\BaseEntity[]
	 */
	public function getResult($hydrationMode = AbstractQuery::HYDRATE_OBJECT)
	{
		return $this
			->doCreateQuery($this->dao)
			->getQuery()
			->getResult($hydrationMode);
	}


	/**
	 * @param int $hydrationMode
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 * @return \Carrooi\Doctrine\Entities\BaseEntity
	 */
	public function getOneOrNullResult($hydrationMode = null)
	{
		return $this
			->doCreateQuery($this->dao)
			->getQuery()
			->getOneOrNullResult($hydrationMode);
	}


	/**
	 * @return mixed
	 */
	public function getSingleScalarResult()
	{
		return $this
			->doCreateQuery($this->dao)
			->getQuery()
			->getSingleScalarResult();
	}

}
