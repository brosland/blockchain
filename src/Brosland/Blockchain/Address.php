<?php

namespace Brosland\Blockchain;

class Address extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $address;


	/**
	 * @param array $address
	 */
	public function __construct(array $address)
	{
		$this->address = $address;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address['address'];
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return isset($this->address['label']) ? $this->address['label'] : NULL;
	}

	/**
	 * @return string
	 */
	public function getBalance()
	{
		return $this->address['balance'];
	}

	/**
	 * @return string
	 */
	public function getTotalReceived()
	{
		return $this->address['total_received'];
	}
}