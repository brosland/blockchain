<?php

namespace Brosland\Blockchain;

use DateTime;

class Block extends \Nette\Object
{
	/**
	 * @var array
	 */
	private $args;
	/**
	 * @var Transaction[]
	 */
	private $transactions;


	/**
	 * Returns new instance of Block created from responce of https://blockchain.info/rawblock/$hash?format=json
	 * @param array $args
	 * @return Block
	 */
	public static function createFromArray($args)
	{
		$args['time'] = DateTime::createFromFormat('U', $args['time']);

		$block = new Block($args);

		foreach ($block->args['tx'] as $txArgs)
		{
			$block->transactions[$txArgs['hash']] = Transaction::createFromArray($txArgs);
		}

		unset($block->args['tx']);

		return $block;
	}

	/**
	 * @param array $args
	 */
	private function __construct(array $args)
	{
		$this->args = $args;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->args['hash'];
	}

	/**
	 * @return string
	 */
	public function getIndex()
	{
		return $this->args['block_index'];
	}

	/**
	 * The hash value of the previous block this particular block references 
	 * 
	 * @return string
	 */
	public function getPreviousBlockHash()
	{
		return $this->args['prev_block'];
	}

	/**
	 * The reference to a Merkle tree collection which is a hash of all transactions related to this block 
	 * 
	 * @return string
	 */
	public function getMerkleRoot()
	{
		return $this->args['mrkl_root'];
	}

	/**
	 * A datetime recording when this block was created
	 * 
	 * @return DateTime
	 */
	public function getTime()
	{
		return $this->args['time'];
	}

	/**
	 * The calculated difficulty target being used for this block
	 * 
	 * @return int
	 */
	public function getBits()
	{
		return $this->args['bits'];
	}

	/**
	 * The nonce used to generate this block… to allow variations of the header and compute different hashes
	 * 
	 * @return int
	 */
	public function getNonce()
	{
		return $this->args['nonce'];
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->args['size'];
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->args['height'];
	}

	/**
	 * @return bool
	 */
	public function isMainChain()
	{
		return $this->args['main_chain'];
	}

	/**
	 * @return string
	 */
	public function getRelayedBy()
	{
		return $this->args['relayed_by'];
	}

	/**
	 * Block version information, based upon the software version creating this block 
	 * 
	 * @return int
	 */
	public function getVersion()
	{
		return $this->args['ver'];
	}

	/**
	 * @return Transaction[]
	 */
	public function getTransactions()
	{
		return $this->transactions;
	}
}