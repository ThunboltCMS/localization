<?php

declare(strict_types=1);

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;

class Translator implements ITranslator {

	/** @var array */
	private $translations = [];

	public function __construct(string $lang) {
		$this->translations = require __DIR__ . '/sources/' . $lang . '.php';
	}

	/**
	 * @param string $message
	 * @param null|int $count
	 * @return string
	 */
	public function translate($message, ?int $count = NULL): string {
		if (isset($this->translations[$message])) {
			return $this->translations[$message];
		}

		return $message;
	}

}