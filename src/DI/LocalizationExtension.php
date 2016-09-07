<?php

namespace Thunbolt\Localization\DI;

use Nette\DI\CompilerExtension;
use Nette\Localization\ITranslator;
use Thunbolt\Localization\IMockTranslator;
use Thunbolt\Localization\LocalizationException;
use Thunbolt\Localization\StartupTranslator;
use Thunbolt\Localization\Translations\CzechTranslation;
use Thunbolt\Localization\TranslatorProvider;

class LocalizationExtension extends CompilerExtension {

	/** @var array */
	public static $translators = [
		'cs' => CzechTranslation::class,
	];

	/** @var array */
	public $defaults = [
		'lang' => 'cs'
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('mockTranslator'))
			->setClass(IMockTranslator::class)
			->setFactory($this->getTranslator($config['lang']))
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('translatorProvider'))
			->setClass(TranslatorProvider::class);

		$builder->addDefinition($this->prefix('startupTranslation'))
			->setClass(StartupTranslator::class)
			->addTag('run');
	}

	/**
	 * @param string $lang
	 * @throws LocalizationException
	 * @return string
	 */
	private function getTranslator($lang) {
		if (!isset(self::$translators[$lang])) {
			throw new LocalizationException("Translator for lang '$lang' not exists.");
		}

		return self::$translators[$lang];
	}

	public function beforeCompile() {
		$builder = $this->getContainerBuilder();

		if ($service = $builder->getByType(ITranslator::class)) {
			$service = '@' . $service;
		} else {
			$service = $this->prefix('@mockTranslator');
		}

		$builder->getDefinition($this->prefix('translatorProvider'))
			->setArguments([$service]);
	}

}
