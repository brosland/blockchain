<?php

namespace Brosland\Blockchain;

class Currency extends \Nette\Object
{
	/**
	 * @var array
	 */
	public static $TYPES = array (
		'USD' => '$', 'JPY' => '¥', 'CNY' => '¥', 'SGD' => '$', 'HKD' => '$',
		'CAD' => '$', 'NZD' => '$', 'AUD' => '$', 'CLP' => '$', 'GBP' => '£',
		'DKK' => 'kr', 'SEK' => 'kr', 'ISK' => 'kr', 'CHF' => 'CHF',
		'BRL' => 'R$', 'EUR' => '€', 'RUB' => 'RUB', 'PLN' => 'zł',
		'THB' => '฿', 'KRW' => '₩', 'TWD' => 'NT$'
	);
	/**
	 * @var string
	 */
	private $code;
	/**
	 * @var double
	 */
	private $value;
	/**
	 * @var double
	 */
	private $value15m = NULL;
	/**
	 * @var double
	 */
	private $valueBuy = NULL;
	/**
	 * @var double
	 */
	private $valueSell = NULL;


	/**
	 * @param string $code
	 * @param double $value
	 */
	public function __construct($code, $value)
	{
		$this->code = $code;
		$this->value = $value;
	}

	/**
	 * 
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getSymbol()
	{
		return self::$TYPES[$this->code];
	}

	/**
	 * @return double
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return double
	 */
	public function getValue15m()
	{
		return $this->value15m;
	}

	/**
	 * @param double $value15m
	 * @return self
	 */
	public function setValue15m($value15m)
	{
		$this->value15m = $value15m;

		return $this;
	}

	/**
	 * @return double
	 */
	public function getValueBuy()
	{
		return $this->valueBuy;
	}

	/**
	 * @param double $valueBuy
	 * @return self
	 */
	public function setValueBuy($valueBuy)
	{
		$this->valueBuy = $valueBuy;

		return $this;
	}

	/**
	 * @return double
	 */
	public function getValueSell()
	{
		return $this->valueSell;
	}

	/**
	 * @param double $valueSell
	 * @return self
	 */
	public function setValueSell($valueSell)
	{
		$this->valueSell = $valueSell;

		return $this;
	}

	/**
	 * @param string $code
	 * @return bool
	 */
	public static function validate($code)
	{
		return isset(self::$TYPES[$code]);
	}
}