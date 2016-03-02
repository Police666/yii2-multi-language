<?php
/**
 * Created by Navatech.
 * @project    Yii2 Multi Language
 * @author     Phuong
 * @email      phuong17889[at]gmail.com
 * @created    13/02/2016 16:20 CH
 * @updated    03/03/2016 00:40 SA
 * @since      2.0.0
 */
namespace navatech\language\widgets;

use navatech\language\helpers\MultiLanguageHelpers;
use navatech\language\models\Language;
use navatech\language\components\MultiLanguageAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class LanguageWidget extends Widget {

	public $type    = 'classic';

	public $viewDir = '@vendor/navatech/yii2-multi-language/src/views/LanguageWidget';

	public $size    = 30;

	/**@var \navatech\localeurls\UrlManager */
	private $urlManager;

	private $languages;

	private $current;

	/**
	 * Initializes the object.
	 * This method is invoked at the end of the constructor after the object is initialized with the
	 * given configuration.
	 * @throws \yii\base\Exception|\yii\base\InvalidParamException
	 */
	public function init() {
		parent::init();
		MultiLanguageAsset::register($this->view);
		$this->urlManager = Yii::$app->urlManager;
		$this->languages  = MultiLanguageHelpers::getLanguages();
		$this->current    = [
			'code'    => 'en',
			'name'    => 'United States',
			'country' => 'us',
		];
	}

	/**
	 * Returns the directory containing the view files for this widget.
	 * The default implementation returns the 'views' subdirectory under the directory containing the widget class file.
	 * @return string the directory containing the view files for this widget.
	 * @throws \yii\base\InvalidParamException
	 */
	public function getViewPath() {
		if ($this->viewPath === null) {
			$name = explode("\\", self::className());
			return Yii::getAlias(dirname(__DIR__ . '/../views') . DIRECTORY_SEPARATOR . end($name));
		}
		return $this->viewPath;
	}

	/**
	 * @return array
	 */
	protected function getData() {
		list($route, $params) = Yii::$app->getUrlManager()->parseRequest(Yii::$app->getRequest());
		$params = ArrayHelper::merge($_GET, $params);
		$data   = [0];
		foreach ($this->languages as $language) {
			if ($language['code'] === Yii::$app->language) {
				$this->current = ArrayHelper::merge([
					'url' => Yii::$app->urlManager->createUrl(ArrayHelper::merge($params, [
						array_key_exists('route', $params) ? $params['route'] : $route,
						'language' => $language['code'],
					])),
				], $language);
				$data[0]       = $this->current;
			} else {
				$data[] = ArrayHelper::merge([
					'url' => Yii::$app->urlManager->createUrl(ArrayHelper::merge($params, [
						array_key_exists('route', $params) ? $params['route'] : $route,
						'language' => $language['code'],
					])),
				], $language);
			}
		}
		if (!array_key_exists(0, $data) || $data[0] === 0) {
			/**@var  $currentLanguage Language */
			$currentLanguage = Language::findOne(['code' => Yii::$app->language]);
			if ($currentLanguage) {
				$this->current = [
					'code'    => $currentLanguage->code,
					'name'    => $currentLanguage->name,
					'country' => $currentLanguage->country,
				];
				$data[0]       = $this->current;
			}
		}
		return $data;
	}

	/**
	 * Executes the widget.
	 * @return string the result of widget execution to be outputted.
	 * @throws \yii\base\InvalidParamException
	 */
	public function run() {
		$renderView = 'languageClassic';
		if ($this->type === 'selector') {
			$renderView = 'languageSelector';
		}
		return $this->render($renderView, [
			'data'    => $this->getData(),
			'size'    => $this->size,
			'current' => $this->current,
		]);
	}
}
