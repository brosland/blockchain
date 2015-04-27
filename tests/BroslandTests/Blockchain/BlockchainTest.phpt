<?php

namespace BroslandTest\Blockchain;

use Brosland\Blockchain\Address,
	Brosland\Blockchain\Block,
	Brosland\Blockchain\Blockchain,
	Brosland\Blockchain\BlockchainException,
	Brosland\Blockchain\Transaction,
	Brosland\Blockchain\Wallet,
	Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class BlockchainTest extends \Tester\TestCase
{
	/**
	 * @var Blockchain
	 */
	private $blockchain;
	/**
	 * @var Wallet
	 */
	private $wallet;


	public function setUp()
	{
		parent::setUp();

		$this->blockchain = new Blockchain();
		$this->wallet = new Wallet('1234567890', 'password');
	}

	public function testAddWallet()
	{
		$result = $this->blockchain->addWallet('default', $this->wallet);
		Assert::equal($this->blockchain, $result);
	}

	public function testGetWallet()
	{
		$this->blockchain->addWallet('default', $this->wallet);
		$wallet = $this->blockchain->getWallet('default');
		Assert::equal($this->wallet, $wallet);
	}

	public function testGetBlock()
	{
		// valid block
		$hash = '00000000000005d86b3b6fc40fd68c8c62f5414882f2c045cd3204b3f908b4c4';
		$block = $this->blockchain->getBlock($hash);
		Assert::type(Block::class, $block);

		// invalid block
		Assert::exception($this->blockchain->getBlock('a'), BlockchainException::class, 'Invalid Block Hash');
	}

	public function testGetTransaction()
	{
		// valid transaction
		$hash = '1953886f889be284e260cb6f54605b6c61c947c42ce217202a7c985cb88fb9c1';
		$transaction = $this->blockchain->getTransaction($hash);
		Assert::type(Transaction::class, $transaction);

		// invalid block
		Assert::exception($this->blockchain->getTransaction('a'), BlockchainException::class, 'Invalid Transaction Hash');
	}

	public function testGetAddress()
	{
		// valid address
		$hash = '1KdXFjZ558AdZUHxbRXbaDqx3FHW8mdXB1';
		$addressA = $this->blockchain->getAddress($hash);
		Assert::type(Address::class, $addressA);

		// valid address with transaction's limit
		$addressB = $this->blockchain->getAddress($hash, 1);
		Assert::type(Address::class, $addressB);

		// valid address with transaction's limit and offset
		$addressC = $this->blockchain->getAddress($hash, 1, 1);
		Assert::type(Address::class, $addressC);

		// invalid address
		Assert::exception($this->blockchain->getAddress('a'), BlockchainException::class, 'Invalid Address Hash');
	}

	public function testGetTicker()
	{
		$currencies = $this->blockchain->getTicker();
		Assert::type('array', $currencies);
	}

	public function testConvertToBitcoins()
	{
		// convert 1 USD
		$value = $this->blockchain->convertToBitcoins('USD');

		// convert 2 USD
		$value2 = $this->blockchain->convertToBitcoins('USD', 2);

		Assert::type('double', $value);
		Assert::type('double', $value2);
		Assert::equal($value * 2.0, $value2);

		// invalid currency
		Assert::exception($this->blockchain->convertToBitcoins('usa'), BlockchainException::class, 'Unknown Currency Code');
	}

	public function testGetBlockCount()
	{
		$count = $this->blockchain->getBlockCount();
		Assert::type('int', $count);
	}
}

$test = new BlockchainTest();
$test->run();