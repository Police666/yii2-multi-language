<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    13/02/2016
 * @time    16:20 CH
 * @version 1.0.2
 */
namespace navatech\language\widgets;

use navatech\language\models\Language;
use navatech\language\MultiLanguage;
use navatech\language\MultiLanguageAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class LanguageWidget extends Widget {

	public $type;

	public $viewPath = null;

	public $size     = 30;

	/**@var \navatech\localeurls\UrlManager */
	private $urlManager;

	private $languages;

	private $current = [
		'code'    => 'en',
		'name'    => 'United States',
		'country' => 'us',
	];

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function init() {
		parent::init();
		MultiLanguageAsset::register($this->view);
		$this->urlManager = Yii::$app->urlManager;
		if (!$this->type) {
			$this->type = 'classic';
		}
		$this->languages = MultiLanguage::getLanguages();
	}

	/**
	 * @inheritdoc
	 * @return bool|string
	 */
	public function getViewPath() {
		if ($this->viewPath === null) {
			$name = explode("\\", $this->className());
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
	 * @inheritdoc
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
