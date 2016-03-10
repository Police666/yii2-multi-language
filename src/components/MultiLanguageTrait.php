<?php
/**
 * Created by Navatech.
 * @project yii2-multi-language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    03/03/2016
 * @time    12:47 SA
 */
namespace navatech\language\components;

use Yii;
use yii\db\ActiveQuery;

/**
 * Multilingual trait.
 * Modify ActiveRecord query for multilingual support
 *
 * @property array $with
 *
 * @method ActiveQuery with() with($_)
 */
trait MultiLanguageTrait {

	/**
	 * @var string the name of the lang field of the translation table. Default to 'language'.
	 */
	public $languageField = 'language';

	/**
	 * Scope for querying by languages
	 *
	 * @param $language
	 * @param $abridge
	 *
	 * @return $this
	 */
	public function localized($language = null, $abridge = true) {
		if (!$language) {
			$language = Yii::$app->language;
		}
		if (!isset($this->with['translations'])) {
			$this->with([
				'translation' => function($query) use ($language, $abridge) {
					/** @var ActiveQuery $query */
					$query->where([$this->languageField => $abridge ? substr($language, 0, 2) : $language]);
				},
			]);
		}
		return $this;
	}

	/**
	 * Scope for querying by all languages
	 * @return $this
	 */
	public function translate() {
		if (isset($this->with['translation'])) {
			unset($this->with['translation']);
		}
		$this->with('translations');
		return $this;
	}
}