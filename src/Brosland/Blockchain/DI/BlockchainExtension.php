<?php

namespace Brosland\Blockchain\DI;

class BlockchainExtension extends \Nette\DI\CompilerExtension
{
	/**
	 * @var array
	 */
	private static $DEFAULTS = [
		'wallet' => [
			'id' => NULL,
			'password' => NULL,
			'password2' => NULL
		],
		'minConfirmations' => 50,
		'httpCallbackRoute' => 'blockchain-callback'
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		$blockchain = $builder->addDefinition($this->prefix('blockchain'))
			->setClass(\Brosland\Blockchain\Blockchain::class);

		foreach ($this->loadWallets($config['wallet']) as $name => $wallet)
		{
			$blockchain->addSetup('addWallet', array ($name, $wallet));
		}

		$router = $builder->addDefinition($this->prefix('router'))
			->setClass(\Brosland\Blockchain\Routers\HttpCallbackRouter::class)
			->setArguments(array ($config['httpCallbackRoute']))
			->setAutowired(FALSE);

		if ($builder->hasDefinition('router'))
		{
			$builder->getDefinition('router')
				->addSetup('offsetSet', array (NULL, $router));
		}
	}

	/**
	 * @param array $wallets
	 * @return \Nette\DI\ServiceDefinition[]
	 */
	private function loadWallets($wallets)
	{
		if (isset($wallets['id']))
		{
			$wallets = array ('default' => $wallets);
		}

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		$services = array ();

		foreach ($wallets as $name => $wallet)
		{
			$serviceName = $this->prefix('wallet.' . $name);

			$service = $builder->addDefinition($serviceName)
				->setClass(\Brosland\Blockchain\Wallet::class)
				->setArguments(array (
					$wallet['id'],
					$wallet['password'],
					$wallet['password2']
				))
				->addSetup('setMinConfirmations', array ($config['minConfirmations']));

			if (!empty($services))
			{
				$service->setAutowired(FALSE);
			}

			$services[$name] = $service;
		}

		return $services;
	}
}