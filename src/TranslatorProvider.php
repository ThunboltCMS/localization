<?php

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;

class TranslatorProvider {

	/** @var ITranslator */
	private $translator;

	/**
	 * @param ITranslator $translator
	 */
	public function __construct(ITranslator $translator) {
		$this->translator = $translator;
	}

	/**
	 * @return ITranslator
	 */
	public function getTranslator() {
		return $this->translator;
	}

	/**
	 * @return bool
	 */
	public function isCoreTranslator() {
		return $this->translator instanceof Translator;
	}

}
