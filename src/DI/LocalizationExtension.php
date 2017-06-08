<?php

declare(strict_types=1);

namespace Thunbolt\Localization\DI;

use Nette\DI\CompilerExtension;
use Nette\Localization\ITranslator;
use Thunbolt\Localization\LocalizationException;
use Thunbolt\Localization\StartupTranslator;
use Thunbolt\Localization\Translator;

class LocalizationExtension extends CompilerExtension {

	/** @var array */
	private const LANGUAGES = [
		'cs' => TRUE,
	];

	/** @var array */
	public $defaults = [
		'lang' => 'cs',
		'enable' => TRUE,
		'startup' => TRUE,
	];

	public function loadConfiguration(): void {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		if (!isset(self::LANGUAGES[$config['lang']])) {
			throw new LocalizationException("Language '{$config['lang']}' not exists.");
		}

		if ($config['enable']) {
			$builder->addDefinition($this->prefix('translator'))
				->setClass(ITranslator::class)
				->setFactory(Translator::class, [$config['lang']])
				->setAutowired(FALSE);
		}

		if ($config['startup']) {
			$builder->addDefinition($this->prefix('startupTranslation'))
				->setClass(StartupTranslator::class)
				->addTag('run');
		}
	}

	public function beforeCompile(): void {
		$builder = $this->getContainerBuilder();

		if (!$builder->getByType(ITranslator::class) && $builder->hasDefinition($this->prefix('translator'))) {
			$builder->getDefinition($this->prefix('translator'))
				->setAutowired();
		}
	}

}
