<?php

namespace Brosland\Blockchain;

class Currency extends \Nette\Object
{
	/**
	 * @var array
	 */
	public static $TYPES = [
		'USD' => '$', 'JPY' => '¥', 'CNY' => '¥', 'SGD' => '$', 'HKD' => '$',
		'CAD' => '$', 'NZD' => '$', 'AUD' => '$', 'CLP' => '$', 'GBP' => '£',
		'DKK' => 'kr', 'SEK' => 'kr', 'ISK' => 'kr', 'CHF' => 'CHF',
		'BRL' => 'R$', 'EUR' => '€', 'RUB' => 'RUB', 'PLN' => 'zł',
		'THB' => '฿', 'KRW' => '₩', 'TWD' => 'NT$'
	];
	/**
	 * @var array
	 */
	private $currency;


	/**
	 * @param array $currency
	 */
	public function __construct($currency)
	{
		$this->currency = $currency;
	}

	/**
	 * 
	 * @return string
	 */
	public function getCode()
	{
		return $this->currency['code'];
	}

	/**
	 * @return string
	 */
	public function getSymbol()
	{
		return self::$TYPES[$this->getCode()];
	}

	/**
	 * @return double
	 */
	public function getValue()
	{
		return $this->currency['last'];
	}

	/**
	 * @return double
	 */
	public function getValue15m()
	{
		return $this->currency['15m'];
	}

	/**
	 * @return double
	 */
	public function getValueBuy()
	{
		return $this->currency['buy'];
	}

	/**
	 * @return double
	 */
	public function getValueSell()
	{
		return $this->currency['sell'];
	}

	/**
	 * @param string $code
	 * @return bool
	 */
	public static function validate($code)
	{
		return isset(self::$TYPES[\Nette\Utils\Strings::upper($code)]);
	}
}