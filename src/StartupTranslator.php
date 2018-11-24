<?php

declare(strict_types=1);

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;
use Nette;
use WebChemistry\Utils\Filters;
use WebChemistry;
use WebChemistry\Forms\Controls\Suggestion;

class StartupTranslator {

	/** @var ITranslator|null */
	private $translator;

	/** @var IStartupTranslator|null */
	private $startupTranslator;

	public function __construct(?IStartupTranslator $startupTranslator = null, ?ITranslator $translator = null) {
		$this->translator = $translator;
		$this->startupTranslator = $startupTranslator;

		$this->translateForms();
	}

	public function getTranslator(): ?ITranslator {
		return $this->translator;
	}

	private function translateArray(array &$array): void {
		if (!$this->startupTranslator || $this->translator) {
			return;
		}

		foreach ($array as $key => $value) {
			$array[$key] = $this->startupTranslator->translate($value);
		}
	}

	protected function translateForms(): void {
		Nette\Forms\Validator::$messages[Nette\Forms\Controls\CsrfProtection::PROTECTION] = 'core.forms.protection';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::EQUAL] = 'core.forms.equal';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::NOT_EQUAL] = 'core.forms.notEqual';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::FILLED] = 'core.forms.filled';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::BLANK] = 'core.forms.blank';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MIN_LENGTH] = 'core.forms.minLength';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MAX_LENGTH] = 'core.forms.maxLength';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::LENGTH] = 'core.forms.length';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::EMAIL] = 'core.forms.email';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::URL] = 'core.forms.url';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::INTEGER] = 'core.forms.integer';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::FLOAT] = 'core.forms.float';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MIN] = 'core.forms.min';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MAX] = 'core.forms.max';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::RANGE] = 'core.forms.range';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MAX_FILE_SIZE] = 'core.forms.maxFileSize';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MAX_POST_SIZE] = 'core.forms.maxPostSize';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::IMAGE] = 'core.forms.image';
		Nette\Forms\Validator::$messages[Nette\Application\UI\Form::MIME_TYPE] = 'core.forms.mimeType';
		Nette\Forms\Validator::$messages[Nette\Forms\Controls\SelectBox::VALID] = 'core.forms.select.valid';
		Nette\Forms\Validator::$messages[Nette\Forms\Controls\UploadControl::VALID] = 'core.forms.upload.valid';

		$this->translateArray(Nette\Forms\Validator::$messages);
	}

}
