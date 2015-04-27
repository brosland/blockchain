<?php

namespace Brosland\Blockchain;

use Kdyby\Curl\Request,
	Nette\Utils\Json;

class Blockchain extends \Nette\Object
{
	const BASE_URL = 'https://blockchain.info';


	/**
	 * @var Wallet[]
	 */
	private $wallets = array ();


	/**
	 * @param string $name
	 * @param Wallet $wallet
	 * @return self
	 */
	public function addWallet($name, Wallet $wallet)
	{
		$this->wallets[$name] = $wallet;

		return $this;
	}

	/**
	 * @param string $name
	 * @return Wallet
	 * @throws \Nette\ArgumentOutOfRangeException
	 */
	public function getWallet($name)
	{
		if (!isset($this->wallets[$name]))
		{
			throw new \Nette\ArgumentOutOfRangeException('Wallet not found.');
		}

		return $this->wallets[$name];
	}

	/**
	 * @param string $hash
	 * @return Block
	 */
	public function getBlock($hash)
	{
		return Block::createFromArray($this->sendRequest('rawblock/' . $hash));
	}

	/**
	 * @param string $hash
	 * @return Transaction
	 */
	public function getTransaction($hash)
	{
		return Transaction::createFromArray($this->sendRequest('rawtx/' . $hash));
	}

	/**
	 * @param string $hash
	 * @param int $transactionsLimit
	 * @param int $transactionsOffset
	 * @return Address
	 */
	public function getAddress($hash, $transactionsLimit = NULL,
		$transactionsOffset = NULL)
	{
		$response = $this->sendRequest('address/' . $hash, array (
			'limit' => $transactionsLimit,
			'offset' => $transactionsOffset
		));

		return Address::createFromArray($response);
	}

	/**
	 * @return Currency[]
	 */
	public function getTicker()
	{
		$response = $this->sendRequest('ticker');
		$currencies = array ();

		foreach ($response as $code => $values)
		{
			$currency = new Currency($code, $values->last);
			$currency->setValue15m($values->value15m)
				->setValueBuy($values->valueBuy)
				->setValueSell($values->valueSell);

			$currencies[$code] = $currency;
		}

		return $currencies;
	}

	/**
	 * @param string $code
	 * @param double $value
	 * @throws \Nette\InvalidArgumentException
	 */
	public function convertToBitcoins($code, $value = 1.0)
	{
		if (!Currency::validate($code))
		{
			throw new \Nette\InvalidArgumentException('Currency not found.');
		}

		return $this->sendRequest('tobtc', array ('currency' => $code, 'value' => $value), FALSE);
	}

	/**
	 * @return int
	 */
	public function getBlockCount()
	{
		return (int) $this->sendRequest('q/getblockcount', NULL, FALSE);
	}

	/**
	 * @param string $endpoint
	 * @param array $parameters
	 * @param bool $jsonResponse
	 * @return mixed
	 */
	private function sendRequest($endpoint = '', $parameters = array (),
		$jsonResponse = TRUE)
	{
		if ($jsonResponse)
		{
			$parameters['format'] = 'json';
		}

		$request = new Request(self::BASE_URL . '/' . $endpoint);
		$request->headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$request->options['CONNECTTIMEOUT'] = 30;
		$request->options['TIMEOUT'] = 60;
		$request->options['CAINFO'] = __DIR__ . '/certificates/cacert.pem';

		$response = $request->get($parameters);

		if ($jsonResponse)
		{
			try
			{
				return Json::decode($response->getResponse(), Json::FORCE_ARRAY);
			}
			catch (\Nette\Utils\JsonException $ex)
			{
				throw new BlockchainException($response->getResponse());
			}
		}

		return $response->getResponse();
	}
}