<?php declare(strict_types = 1);

namespace Thunbolt\Localization;

interface IStartupTranslator {

	public function translate($message, int $count = null): string;

}
