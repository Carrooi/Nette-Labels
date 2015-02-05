<?php

namespace CarrooiTests\Labels;

use Nette\Configurator;
use Tester\FileMock;
use Tester\TestCase as BaseTestCase;

/**
 *
 * @author David Kudera
 */
class TestCase extends BaseTestCase
{


	/**
	 * @param string $customConfig
	 * @return \Nette\DI\Container
	 */
	protected function createContainer($customConfig = null)
	{
		copy(__DIR__. '/../LabelsApp/Model/database', TEMP_DIR. '/database');

		$config = new Configurator;
		$config->setTempDirectory(TEMP_DIR);
		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5($customConfig)]]);
		$config->addParameters(['appDir' => __DIR__. '/../LabelsApp']);
		$config->addConfig(__DIR__. '/../LabelsApp/config/config.neon');
		$config->addConfig(FileMock::create('parameters: {databasePath: %tempDir%/database}', 'neon'));

		if ($customConfig) {
			if (pathinfo($customConfig, PATHINFO_EXTENSION) !== 'neon') {
				$customConfig = __DIR__. '/../LabelsApp/config/'. $customConfig. '.neon';
			}

			$config->addConfig($customConfig);
		}

		return $config->createContainer();
	}

}
