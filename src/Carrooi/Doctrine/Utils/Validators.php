<?php

namespace Carrooi\Doctrine\Utils;

/**
 *
 * @author David Kudera
 */
class Validators
{


	/**
	 * @param mixed $s
	 * @return mixed
	 */
	public function validateString($s)
	{
		$s = (string) $s;

		if (($s = trim($s)) === '') {
			return null;
		}

		return $s;
	}


	/**
	 * @param mixed $n
	 * @return int
	 */
	public function validateInt($n)
	{
		if ($n === '' || $n === null) {
			return null;
		}

		return (int) $n;
	}


	/**
	 * @param mixed $f
	 * @return float
	 */
	public function validateFloat($f)
	{
		if ($f === '' || $f === null) {
			return null;
		}

		return floatval($f);
	}


	/**
	 * @param mixed $b
	 * @return bool
	 */
	public function validateBool($b)
	{
		if ($b === '' || $b === null) {
			return null;
		}

		return (bool) $b;
	}

}
