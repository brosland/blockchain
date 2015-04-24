<?php

namespace Brosland\Blockchain;

use DateTime;

class Block extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $definition;


	/**
	 * @param array|\Nette\Utils\ArrayHash $definition
	 */
	public function __construct($definition)
	{
		$this->definition = $definition;

		$time = new DateTime();
		$time->setTimestamp($definition['time']);
		$this->definition['time'] = $time;

		$receivedTime = new DateTime();
		$receivedTime->setTimestamp($definition['received_time']);
		$this->definition['received_time'] = $receivedTime;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->definition['hash'];
	}

	/**
	 * @return string
	 */
	public function getIndex()
	{
		return $this->definition['block_index'];
	}

	/**
	 * @return string
	 */
	public function getPreviousBlockHash()
	{
		return $this->definition['prev_block'];
	}

	/**
	 * @return string
	 */
	public function getMerkleRoot()
	{
		return $this->definition['mrkl_root'];
	}

	/**
	 * @return DateTime
	 */
	public function getTime()
	{
		return $this->definition['time'];
	}

	/**
	 * @return DateTime
	 */
	public function getReceivedTime()
	{
		return $this->definition['received_time'];
	}

	/**
	 * @return string
	 */
	public function getBits()
	{
		return $this->definition['bits'];
	}

	/**
	 * @return string
	 */
	public function getNonce()
	{
		return $this->definition['nonce'];
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return (int) $this->definition['size'];
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return (int) $this->definition['height'];
	}

	/**
	 * @return bool
	 */
	public function isMainChain()
	{
		return (bool) $this->definition['main_chain'];
	}

	/**
	 * @return string
	 */
	public function getRelayedBy()
	{
		return $this->definition['relayed_by'];
	}

	/**
	 * @return int
	 */
	public function getVer()
	{
		return (int) $this->definition['ver'];
	}

	/**
	 * @return array
	 */
	public function getTransactions()
	{
		return $this->definition['tx'];
	}
}