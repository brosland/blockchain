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
	private $hash160 = NULL;
	/**
	 * @var string
	 */
	private $label = NULL;
	/**
	 * @var string
	 */
	private $balance;
	/**
	 * @var string
	 */
	private $totalReceived;
	/**
	 * @var string
	 */
	private $totalSent = NULL;
	/**
	 * @var int
	 */
	private $unredeemed = NULL;
	/**
	 * @var array
	 */
	private $transactions = NULL;


	/**
	 * Returns new instance of Address created from responce of https://blockchain.info/address/$address?format=json
	 * @param array|\Nette\Utils\ArrayHash $definition
	 * @return Address
	 */
	public static function createFromArray($definition)
	{
		$address = new Address($definition['address'], $definition['final_balance'], $definition['total_received']);
		$address->setHash160($definition['hash160'])
			->setUnredeemed($definition['n_unredeemed'])
			->setTotalSent($definition['total_sent'])
			->setTransactions($definition['txs']);

		return $address;
	}

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
	 * @return string
	 */
	public function getHash160()
	{
		return $this->hash160;
	}

	/**
	 * @param string $hash160
	 * @return self
	 */
	public function setHash160($hash160)
	{
		$this->hash160 = $hash160;

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
	 * @return string
	 */
	public function getBalance()
	{
		return $this->balance;
	}

	/**
	 * @return string
	 */
	public function getTotalReceived()
	{
		return $this->totalReceived;
	}

	/**
	 * @return string
	 */
	public function getTotalSent()
	{
		return $this->totalSent;
	}

	/**
	 * @param string $totalSent
	 * @return self
	 */
	public function setTotalSent($totalSent)
	{
		$this->totalSent = $totalSent;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getUnredeemed()
	{
		return $this->unredeemed;
	}

	/**
	 * @param int $unredeemed
	 * @return self
	 */
	public function setUnredeemed($unredeemed)
	{
		$this->unredeemed = $unredeemed;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getTransactions()
	{
		return $this->transactions;
	}

	/**
	 * @param array|\Nette\Utils\ArrayHash $transactions
	 * @return self
	 */
	public function setTransactions($transactions)
	{
		$this->transactions = $transactions;

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