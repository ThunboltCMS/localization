<?php

declare(strict_types=1);

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;
use Nette;
use Thunbolt\Administration\Localization;
use WebChemistry\Utils\Filters;
use WebChemistry;
use WebChemistry\Forms\Controls\Suggestion;

class StartupTranslator {

	protected const WEBCHEMISTRY_CONTROLS = 0;
	protected const WEBCHEMISTRY_UTILS = 1;

	/** @var ITranslator */
	private $translator;

	/** @var array */
	private $extensions = [
		self::WEBCHEMISTRY_CONTROLS => FALSE,
		self::WEBCHEMISTRY_UTILS => FALSE,
	];

	public function __construct(ITranslator $translator) {
		$this->translator = $translator;

		$this->extensions[self::WEBCHEMISTRY_CONTROLS] = class_exists(Suggestion::class);
		$this->extensions[self::WEBCHEMISTRY_UTILS] = class_exists(WebChemistry\Utils\DateTime::class);

		$this->translateForms();
		if ($this->extensions[self::WEBCHEMISTRY_UTILS]) {
			$this->translateDateTime();
			$this->translateStrings();
			$this->translateTimeAgo();
			$this->translateFilters();
		}
		if (class_exists(Localization::class)) {
			$this->translateAdministration();
		}
	}

	public function getTranslator(): ITranslator {
		return $this->translator;
	}

	protected function translateAdministration(): void {
		foreach (Localization::$translations as $name => $translation) {
			Localization::$translations[$name] = $this->translator->translate($translation);
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
		if ($this->extensions[self::WEBCHEMISTRY_CONTROLS]) {
			Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Date::VALID] = 'core.forms.webchemistry.date';
			Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Tags::VALID] = 'core.forms.webchemistry.tags';

			WebChemistry\Forms\Controls\Date::$dateFormat = 'core.date.datetime';
		}

		if (class_exists(WebChemistry\Images\Controls\Checkbox::class)) {
			WebChemistry\Images\Controls\Checkbox::$globalCaption = 'core.forms.deleteImage';
		}
	}

	protected function translateDateTime(): void {
		$translate = [
			1 => 'core.months.jan', 'core.months.feb', 'core.months.mar', 'core.months.apr', 'core.months.may',
			'core.months.june', 'core.months.july', 'core.months.aug', 'core.months.sep', 'core.months.oct',
			'core.months.nov', 'core.months.dec'
		];
		foreach ($translate as $index => $value) {
			WebChemistry\Utils\DateTime::$translatedMonths[$index] = $this->translator->translate($value);
		}
		WebChemistry\Utils\DateTime::$datetime = $this->translator->translate('core.date.datetime');
		WebChemistry\Utils\DateTime::$date = $this->translator->translate('core.date.date');
		WebChemistry\Utils\DateTime::$time = $this->translator->translate('core.date.time');

		// Days
		$translate = [
			'core.days.sun', 'core.days.mon', 'core.days.tue', 'core.days.wed', 'core.days.thu', 'core.days.fri',
			'core.days.sat'
		];
		foreach ($translate as $index => $value) {
			WebChemistry\Utils\DateTime::$translatedDays[$index] = $this->translator->translate($value);
		}
	}

	protected function translateStrings(): void {
		WebChemistry\Utils\Strings::$decPoint = $this->translator->translate('core.strings.decPoint');
		WebChemistry\Utils\Strings::$sepThousands = $this->translator->translate('core.strings.sepThousands');
	}

	protected function translateTimeAgo(): void {
		WebChemistry\Utils\DateTime::$timeAgoCallback = [$this, 'timeAgo'];
	}

	protected function translateFilters(): void {
		Filters::$booleans = [
			$this->translator->translate('core.filters.no'),
			$this->translator->translate('core.filters.yes')
		];
	}

}
