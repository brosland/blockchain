<?php

namespace Brosland\Blockchain;

use Kdyby\Curl\CurlSender,
	Kdyby\Curl\Request,
	Nette\DI\Container,
	Nette\Utils\Json;

class Blockchain extends \Nette\Object
{
	const URL = 'https://blockchain.info';


	/**
	 * @var Container
	 */
	private $serviceLocator;
	/**
	 * @var array
	 */
	private $serviceMap = [];
	/**
	 * @var CurlSender
	 */
	private $sender;


	public function __construct()
	{
		$this->sender = new CurlSender();
		$this->sender->headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$this->sender->options['CAINFO'] = __DIR__ . '/certificates/cacert.pem';
	}

	/**
	 * @param string $name
	 * @return Wallet
	 */
	public function getWallet($name)
	{
		if (!isset($this->serviceMap[$name]))
		{
			throw new \Nette\InvalidArgumentException("Unknown wallet {$name}.");
		}

		return $this->serviceLocator->getService($this->serviceMap[$name]);
	}

	/**
	 * @internal
	 * @param array $wallets
	 */
	public function injectServiceMap(array $wallets)
	{
		$this->serviceMap = $wallets;
	}

	/**
	 * @internal
	 * @param Container $serviceLocator
	 */
	public function injectServiceLocator(Container $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @return Currency[]
	 */
	public function getTicker()
	{
		$request = new Request(self::URL . '/ticker');
		$response = Json::decode($this->sender->send($request)->getResponse(), Json::FORCE_ARRAY);
		$currencies = [];

		foreach ($response as $code => $currency)
		{
			$currency['code'] = $code;
			$currencies[$code] = new Currency($currency);
		}

		return $currencies;
	}
}