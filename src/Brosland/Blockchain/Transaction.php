<?php

namespace Brosland\Blockchain;

use DateTime;

class Transaction extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $definition;


	/**
	 * @param array $definition
	 */
	public function __construct(array $definition)
	{
		$this->definition = $definition;

		if (is_int($definition['lock_time']))
		{
			$lockTime = new DateTime();
			$lockTime->setTimestamp($definition['lock_time']);
			$this->definition['lock_time'] = $lockTime;
		}
		else
		{
			$this->definition['lock_time'] = NULL;
		}
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
		return $this->definition['tx_index'];
	}

	/**
	 * @return DateTime
	 */
	public function getLockTime()
	{
		return $this->definition['lock_time'];
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return (int) $this->definition['size'];
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
	public function getBlockHeight()
	{
		return (int) $this->definition['block_height'];
	}

	/**
	 * @return int
	 */
	public function getVer()
	{
		return (int) $this->definition['ver'];
	}
}