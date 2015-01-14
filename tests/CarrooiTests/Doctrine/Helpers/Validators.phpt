<?php
/**
 * Test: Carrooi\Doctrine\Helpers\Validators
 *
 * @testCase CarrooiTests\Doctrine\Helpers\Validators
 * @author David Kudera
 */

namespace CarrooiTests\Doctrine\Helpers;

use Tester\Assert;
use Tester\TestCase;
use Carrooi\Doctrine\Helpers\Validators as DoctrineValidators;

require_once __DIR__. '/../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class Validators extends TestCase
{


	/** @var \Carrooi\Doctrine\Helpers\Validators */
	private $validators;


	public function __construct()
	{
		$this->validators = new DoctrineValidators;
	}


	/**
	 * @return array
	 */
	public function getValidateStringArgs()
	{
		return [
			['hello', 'hello'],
			['', null],
			[5, '5'],
			[0, '0'],
			[null, null],
		];
	}


	/**
	 * @param mixed $value
	 * @param string $result
	 *
	 * @dataProvider getValidateStringArgs
	 */
	public function testValidateString($value, $result)
	{
		Assert::same($result, $this->validators->validateString($value));
	}


	/**
	 * @return array
	 */
	public function getValidateIntArgs()
	{
		return [
			[5, 5],
			[0, 0],
			['10', 10],
			['', null],
			[null, null],
		];
	}


	/**
	 * @param mixed $value
	 * @param int $result
	 *
	 * @dataProvider getValidateIntArgs
	 */
	public function testValidateInt($value, $result)
	{
		Assert::same($result, $this->validators->validateInt($value));
	}


	/**
	 * @return array
	 */
	public function getValidateFloatArgs()
	{
		return [
			[5.5, 5.5],
			[0, 0.0],
			['10.2', 10.2],
			['', null],
			[null, null],
		];
	}


	/**
	 * @param mixed $value
	 * @param float $result
	 *
	 * @dataProvider getValidateFloatArgs
	 */
	public function testValidateFloat($value, $result)
	{
		Assert::same($result, $this->validators->validateFloat($value));
	}


	/**
	 * @return array
	 */
	public function getValidateBoolArgs()
	{
		return [
			[true, true],
			[1, true],
			[5, true],
			[0, false],
			['1', true],
			['', null],
			[null, null],
		];
	}


	/**
	 * @param mixed $value
	 * @param bool $result
	 *
	 * @dataProvider getValidateBoolArgs
	 */
	public function testValidateBool($value, $result)
	{
		Assert::same($result, $this->validators->validateBool($value));
	}

}

run(new Validators);
