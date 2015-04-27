<?php

namespace Brosland\Blockchain;

use DateTime;

class Transaction extends \Nette\Object
{
	/**
	 * @var string
	 */
	private $hash;
	/**
	 * @var string
	 */
	private $index;
	/**
	 * @var DateTime
	 */
	private $lockTime;
	/**
	 * @var int
	 */
	private $size;
	/**
	 * @var string
	 */
	private $relayedBy;
	/**
	 * @var int
	 */
	private $blockHeight;
	/**
	 * @var int
	 */
	private $ver;
	/**
	 * @var array
	 */
	private $inputs;
	/**
	 * @var array
	 */
	private $outputs;


	/**
	 * Returns new instance of Address created from responce of https://blockchain.info/address/$address?format=json
	 * @param array $args
	 * @return Transaction
	 */
	public static function createFromArray($args)
	{
		$transaction = new Transaction();
		$transaction->hash = $args['hash'];
		$transaction->index = $args['tx_index'];
		$transaction->lockTime = is_int($args['lock_time']) ?
			DateTime::createFromFormat('U', $args['lock_time']) : NULL;
		$transaction->size = (int) $args['size'];
		$transaction->relayedBy = $args['relayed_by'];
		$transaction->blockHeight = (int) $args['block_height'];
		$transaction->ver = (int) $args['ver'];
		$transaction->inputs = $args['inputs'];
		$transaction->outputs = $args['out'];

		return $transaction;
	}

	private function __construct()
	{
		
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @return string
	 */
	public function getIndex()
	{
		return $this->index;
	}

	/**
	 * @return DateTime
	 */
	public function getLockTime()
	{
		return $this->lockTime;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return (int) $this->size;
	}

	/**
	 * @return string
	 */
	public function getRelayedBy()
	{
		return $this->relayedBy;
	}

	/**
	 * @return int
	 */
	public function getBlockHeight()
	{
		return (int) $this->blockHeight;
	}

	/**
	 * @return int
	 */
	public function getVer()
	{
		return (int) $this->ver;
	}
}