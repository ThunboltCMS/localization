<?php

namespace Thunbolt\Localization;

use Nette;
use Thunbolt\Templates\Filters;
use WebChemistry;

class StartupTranslator {

	/** @var Nette\Localization\ITranslator */
	private $translator;

	/** @var array */
	public static $flashes = [
		'success' => 'core.flashes.success',
		'error' => 'core.flashes.error',
		'warning' => 'core.flashes.warning',
		'info' => 'core.flashes.info',
		'roll' => 'core.flashes.roll',
		'remove' => 'core.flashes.remove'
	];

	/** @var bool */
	private $isMock;

	public function __construct(TranslatorProvider $translatorProvider) {
		$this->translator = $translatorProvider->getTranslator();
		$this->isMock = $translatorProvider->isMock();

		$this->translateForms();
		$this->translateDateTime();
		$this->translateStrings();
		$this->translateTimeAgo();
		$this->translateFilters();

		foreach (self::$flashes as $key => $flash) {
			self::$flashes[$key] = $this->translator->translate($flash);
		}
	}

	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator() {
		return $this->translator;
	}

	protected function translateForms() {
		Nette\Forms\Validator::$messages = [
				Nette\Forms\Controls\CsrfProtection::PROTECTION => 'core.forms.protection',
				Nette\Application\UI\Form::EQUAL => 'core.forms.equal',
				Nette\Application\UI\Form::NOT_EQUAL => 'core.forms.notEqual',
				Nette\Application\UI\Form::FILLED => 'core.forms.filled',
				Nette\Application\UI\Form::BLANK => 'core.forms.blank',
				Nette\Application\UI\Form::MIN_LENGTH => 'core.forms.minLength',
				Nette\Application\UI\Form::MAX_LENGTH => 'core.forms.maxLength',
				Nette\Application\UI\Form::LENGTH => 'core.forms.length',
				Nette\Application\UI\Form::EMAIL => 'core.forms.email',
				Nette\Application\UI\Form::URL => 'core.forms.url',
				Nette\Application\UI\Form::INTEGER => 'core.forms.integer',
				Nette\Application\UI\Form::FLOAT => 'core.forms.float',
				Nette\Application\UI\Form::MIN => 'core.forms.min',
				Nette\Application\UI\Form::MAX => 'core.forms.max',
				Nette\Application\UI\Form::RANGE => 'core.forms.range',
				Nette\Application\UI\Form::MAX_FILE_SIZE => 'core.forms.maxFileSize',
				Nette\Application\UI\Form::MAX_POST_SIZE => 'core.forms.maxPostSize',
				Nette\Application\UI\Form::IMAGE => 'core.forms.image',
				Nette\Application\UI\Form::MIME_TYPE => 'core.forms.mimeType',
				Nette\Forms\Controls\SelectBox::VALID => 'core.forms.select.valid',
				WebChemistry\Forms\Controls\Date::VALID => 'core.forms.webchemistry.date',
				WebChemistry\Forms\Controls\Tags::VALID => 'core.forms.webchemistry.tags',
				Nette\Forms\Controls\UploadControl::VALID => 'core.forms.upload.valid'
			] + Nette\Forms\Validator::$messages;
		if (class_exists(WebChemistry\Images\Controls\Checkbox::class)) {
			WebChemistry\Images\Controls\Checkbox::$globalCaption = 'core.forms.deleteImage';
		}
		if (class_exists(WebChemistry\Forms\Controls\Date::class)) {
			WebChemistry\Forms\Controls\Date::$dateFormat = 'core.date.datetime';
		}
		if ($this->isMock) {
			foreach (Nette\Forms\Validator::$messages as $index => $message) {
				Nette\Forms\Validator::$messages[$index] = $this->translator->translate($message);
			}
			if (class_exists(WebChemistry\Images\Controls\Checkbox::class)) {
				WebChemistry\Images\Controls\Checkbox::$globalCaption = $this->translator->translate('core.forms.deleteImage');
			}
			if (class_exists(WebChemistry\Forms\Controls\Date::class)) {
				WebChemistry\Forms\Controls\Date::$dateFormat = $this->translator->translate('core.date.datetime');
			}
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
