<?php

namespace Brosland\Blockchain;

class Address extends \Nette\Object
{
	/**
	 * @var string
	 */
	private $address;
	/**
	 * @var string
	 */
	private $label = NULL;
	/**
	 * @var int
	 */
	private $balance;
	/**
	 * @var int
	 */
	private $totalReceived;
	/**
	 * @var bool
	 */
	private $archived = FALSE;


	/**
	 * @param string $address
	 * @param int $balance In Satoshi.
	 * @param int $totalReceived In Satoshi.
	 */
	public function __construct($address = NULL, $balance = 0, $totalReceived = 0)
	{
		$this->address = $address;
		$this->balance = $balance;
		$this->totalReceived = $totalReceived;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return self
	 */
	public function setAddress($address)
	{
		$this->address = $address;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 * @return self
	 */
	public function setLabel($label)
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * @return int In Satoshi.
	 */
	public function getBalance()
	{
		return $this->balance;
	}

	/**
	 * @return int In Satoshi.
	 */
	public function getTotalReceived()
	{
		return $this->totalReceived;
	}

	/**
	 * @return bool
	 */
	public function isArchived()
	{
		return $this->archived;
	}

	/**
	 * @param bool $archived
	 * @return self
	 */
	public function setArchived($archived)
	{
		$this->archived = $archived;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->address;
	}
}