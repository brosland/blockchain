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
		'minConfirmations' => 3,
		'host' => 'localhost',
		'port' => 3000
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		$baseUrl = $config['host'] . ':' . $config['port'];
		$wallets = $this->loadWallets($config['wallet'], $baseUrl);

		$builder->addDefinition($this->prefix('blockchain'))
			->setClass(\Brosland\Blockchain\Blockchain::class)
			->setArguments([$baseUrl])
			->addSetup('injectServiceLocator')
			->addSetup('injectServiceMap', [$wallets]);
	}

	/**
	 * @param array $definitions
	 * @param string $baseUrl
	 * @return array
	 */
	private function loadWallets($definitions, $baseUrl)
	{
		if (isset($definitions['id']))
		{
			$definitions = ['default' => $definitions];
		}

		$builder = $this->getContainerBuilder();
		$wallets = [];

		foreach ($definitions as $name => $wallet)
		{
			$serviceName = $this->prefix('wallet.' . $name);

			$service = $builder->addDefinition($serviceName);
			$service->setClass(\Brosland\Blockchain\Wallet::class)
				->setArguments([
					$baseUrl,
					$wallet['id'],
					$wallet['password'],
					$wallet['password2']
			]);

			if (!empty($wallets))
			{
				$service->setAutowired(FALSE);
			}

			$wallets[$name] = $serviceName;
		}

		return $wallets;
	}
}