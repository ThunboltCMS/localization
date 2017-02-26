<?php

namespace Thunbolt\Localization;

use Nette\Localization\ITranslator;
use Nette;
use Thunbolt\Administration\Localization;
use Thunbolt\Templates\Filters;
use WebChemistry;

class StartupTranslator {

	/** @var ITranslator */
	private $translator;

	public function __construct(ITranslator $translator) {
		$this->translator = $translator;

		$this->translateForms();
		$this->translateDateTime();
		$this->translateStrings();
		$this->translateTimeAgo();
		$this->translateFilters();
		if (class_exists(Localization::class)) {
			$this->translateAdministration();
		}
	}

	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator() {
		return $this->translator;
	}

	protected function translateAdministration() {
		foreach (Localization::$translations as $name => $translation) {
			Localization::$translations[$name] = $this->translator->translate($translation);
		}
	}

	protected function translateForms() {
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
		Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Date::VALID] = 'core.forms.webchemistry.date';
		Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Tags::VALID] = 'core.forms.webchemistry.tags';
		Nette\Forms\Validator::$messages[Nette\Forms\Controls\UploadControl::VALID] = 'core.forms.upload.valid';

		if (class_exists(WebChemistry\Images\Controls\Checkbox::class)) {
			WebChemistry\Images\Controls\Checkbox::$globalCaption = 'core.forms.deleteImage';
		}
		if (class_exists(WebChemistry\Forms\Controls\Date::class)) {
			WebChemistry\Forms\Controls\Date::$dateFormat = 'core.date.datetime';
		}
	}

	protected function translateDateTime() {
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

	protected function translateStrings() {
		WebChemistry\Utils\Strings::$decPoint = $this->translator->translate('core.strings.decPoint');
		WebChemistry\Utils\Strings::$sepThousands = $this->translator->translate('core.strings.sepThousands');
	}

	protected function translateTimeAgo() {
		WebChemistry\Utils\DateTime::$timeAgoCallback = [$this, 'timeAgo'];
	}

	protected function translateFilters() {
		Filters::$booleans = [
			$this->translator->translate('core.filters.no'),
			$this->translator->translate('core.filters.yes')
		];
	}

}
