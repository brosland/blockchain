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
	 * @var array
	 */
	private $transactions = array ();


	/**
	 * Returns new instance of Address created from responce of https://blockchain.info/address/$address?format=json
	 * @param array $args
	 * @return Address
	 */
	public static function createFromArray($args)
	{
		$address = new Address($args['address'], $args['final_balance'], $args['total_received']);
		$address->hash160 = $args['hash160'];
		$address->totalSent = $args['total_sent'];

		foreach ($args['txs'] as $txArgs)
		{
			$address->transactions[$txArgs['hash']] = Transaction::createFromArray($txArgs);
		}

		return $address;
	}

	/**
	 * @param string $address
	 * @param string $balance In Satoshi.
	 * @param string $totalReceived In Satoshi.
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
	 * @return array
	 */
	public function getTransactions()
	{
		return $this->transactions;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->address;
	}
}