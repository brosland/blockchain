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
	private static $REQUIRED = ['code', 'last', '15m', 'buy', 'sell'];
	/**
	 * @var array
	 */
	private $data;


	private function __construct()
	{
		
	}

	/**
	 * @param array $data
	 * @return Currency
	 */
	public static function createFormArray(array $data)
	{
		Utils::checkRequiredFields(self::$REQUIRED, $data);

		$currency = new Currency();
		$currency->data = $data;

		return $currency;
	}

	/**
	 * 
	 * @return string
	 */
	public function getCode()
	{
		return $this->data['code'];
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
		return $this->data['last'];
	}

	/**
	 * @return double
	 */
	public function getValue15m()
	{
		return $this->data['15m'];
	}

	/**
	 * @return double
	 */
	public function getValueBuy()
	{
		return $this->data['buy'];
	}

	/**
	 * @return double
	 */
	public function getValueSell()
	{
		return $this->data['sell'];
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