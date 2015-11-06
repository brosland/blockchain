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
		'minConfirmations' => 3
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		$wallets = $this->loadWallets($config['wallet']);

		$builder->addDefinition($this->prefix('blockchain'))
			->setClass(\Brosland\Blockchain\Blockchain::class)
			->addSetup('injectServiceLocator')
			->addSetup('injectServiceMap', [$wallets]);
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	private function loadWallets($definitions)
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