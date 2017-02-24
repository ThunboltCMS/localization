<?php

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;

class Translator implements ITranslator {

	/** @var array */
	private $translations = [];

	/**
	 * @param string $lang
	 */
	public function __construct($lang) {
		$this->translations = require __DIR__ . '/sources/' . $lang . '.php';
	}

	/**
	 * @param string $message
	 * @param null|int $count
	 * @return string
	 */
	public function translate($message, $count = NULL) {
		if (isset($this->translations[$message])) {
			return $this->translations[$message];
		}

		return $message;
	}

}