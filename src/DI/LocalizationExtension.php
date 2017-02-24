<?php

namespace Thunbolt\Localization\DI;

use Nette\DI\CompilerExtension;
use Nette\Localization\ITranslator;
use Thunbolt\Localization\LocalizationException;
use Thunbolt\Localization\StartupTranslator;
use Thunbolt\Localization\Translator;
use Thunbolt\Localization\TranslatorProvider;

class LocalizationExtension extends CompilerExtension {

	/** @var array */
	private static $languages = [
		'cs' => TRUE,
	];

	/** @var array */
	public $defaults = [
		'lang' => 'cs',
		'enable' => TRUE,
		'startup' => TRUE,
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$def = $builder->addDefinition($this->prefix('translatorProvider'))
			->setClass(TranslatorProvider::class);

		if (!isset(self::$languages[$config['lang']])) {
			throw new LocalizationException("Language '{$config['lang']}' not exists.");
		}

		if ($config['enable']) {
			$builder->addDefinition($this->prefix('translator'))
				->setClass(ITranslator::class)
				->setFactory(Translator::class, [$config['lang']])
				->setAutowired(FALSE);

			$def->setArguments([$this->prefix('@translator')]);
		}

		if ($config['startup']) {
			$builder->addDefinition($this->prefix('startupTranslation'))
				->setClass(StartupTranslator::class)
				->addTag('run');
		}
	}

}
