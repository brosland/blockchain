<?php

namespace Brosland\Blockchain;

class Address extends \Nette\Object
{

	/**
	 * @var array
	 */
	private static $REQUIRED = ['address', 'balance', 'total_received'];
	/**
	 * @var array
	 */
	private $data;


	private function __construct()
	{
		
	}

	/**
	 * @param array $data
	 * @return Address
	 */
	public static function createFromArray(array $data)
	{
		Utils::checkRequiredFields(self::$REQUIRED, $data);

		$address = new Address();
		$address->data = $data;

		return $address;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->data['address'];
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return isset($this->data['label']) ? $this->data['label'] : NULL;
	}

	/**
	 * @return string
	 */
	public function getBalance()
	{
		return $this->data['balance'];
	}

	/**
	 * @return string
	 */
	public function getTotalReceived()
	{
		return $this->data['total_received'];
	}
}